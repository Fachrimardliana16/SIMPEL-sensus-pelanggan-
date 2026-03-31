<?php

namespace App\Filament\Surveyor\Resources;

use App\Filament\Surveyor\Resources\SensusResource\Pages;
use App\Models\SurveyResponse;
use App\Models\Customer;
use App\Models\Question;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SensusResource extends Resource
{
    use \App\Filament\Concerns\HasSensusInfolist;

    protected static ?string $model = SurveyResponse::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Input Sensus';
    protected static ?string $modelLabel = 'Sensus';
    protected static ?string $pluralModelLabel = 'Data Sensus';
    protected static ?string $breadcrumb = 'Sensus';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(array_merge(
                // ===== SECTION 1: DATA PELANGGAN =====
                [
                    Forms\Components\Section::make('Data Pelanggan')
                        ->collapsible()
                        ->icon('heroicon-o-identification')
                        ->columns(3)
                        ->schema([
                            Forms\Components\Select::make('customer_id')
                                ->label('Cari Pelanggan')
                                ->relationship('customer', 'nama')
                                ->getOptionLabelFromRecordUsing(fn ($record) => "{$record->nolangg} - {$record->nama}")
                                ->searchable(['nolangg', 'nama'])
                                ->preload()
                                ->required()
                                ->unique(table: 'survey_responses', column: 'customer_id', ignoreRecord: true)
                                ->validationMessages([
                                    'unique' => 'Pelanggan ini sudah disensus sebelumnya.',
                                ])
                                ->live()
                                ->afterStateUpdated(function (Set $set, $state) {
                                    $customer = Customer::find($state);
                                    if ($customer) {
                                        $set('nolangg', $customer->nolangg);
                                        $set('nama', $customer->nama);
                                        $set('telepon', $customer->telepon);
                                        $set('alamat', $customer->alamat);
                                        $set('KEL', $customer->KEL);
                                        $set('kode_unit', $customer->kode_unit);
                                        $set('nometer', $customer->nometer);
                                        $set('merk_meter', $customer->merk_meter);
                                        $set('diameter', $customer->diameter);
                                        $set('tarif', $customer->tarif);
                                        $set('lati', $customer->lati);
                                        $set('longi', $customer->longi);
                                        $set('alti', $customer->alti);
                                    }
                                }),
                            Forms\Components\TextInput::make('nolangg')->label('No. Langganan')->disabled()->dehydrated(),
                            Forms\Components\TextInput::make('nama')->label('Nama Lengkap')->required(),
                            Forms\Components\TextInput::make('telepon')->label('No. HP/WA')->tel()->placeholder('08xx'),
                            Forms\Components\TextInput::make('KEL')->label('Kelurahan'),
                            Forms\Components\TextInput::make('kode_unit')->label('Kode Unit')->disabled()->dehydrated(),
                            Forms\Components\Textarea::make('alamat')->label('Alamat')->rows(2)->columnSpanFull(),

                            Forms\Components\Hidden::make('surveyor_id')->default(fn () => auth()->id()),
                            Forms\Components\Hidden::make('status')->default('completed'),
                            Forms\Components\Hidden::make('census_status')->default('pending'),
                        ]),

                    // ===== SECTION 2: TEKNIS & METER =====
                    Forms\Components\Section::make('Teknis & Meter')
                        ->collapsible()
                        ->icon('heroicon-o-wrench-screwdriver')
                        ->columns(3)
                        ->schema([
                            Forms\Components\TextInput::make('nometer')->label('No. Meter')->required(),
                            Forms\Components\TextInput::make('merk_meter')->label('Merk Meter'),
                            Forms\Components\TextInput::make('diameter')->label('Diameter'),
                            Forms\Components\TextInput::make('tarif')->label('Tarif/Golongan'),
                            Forms\Components\TextInput::make('jenis_pelayanan')->label('Jenis Pelayanan'),
                            Forms\Components\Select::make('pdam_status')->label('Status')
                                ->options(['aktif' => 'Aktif', 'tutup' => 'Tutup', 'bongkar' => 'Bongkar'])
                                ->default('aktif'),
                        ]),

                ],

                // ===== SECTION 3: KUESIONER (dynamic from questions table) =====
                static::buildKuesionerFields(),

                // ===== SECTION 4: LOKASI & MAP =====
                [
                    Forms\Components\Section::make('Koordinat Lokasi')
                        ->collapsible()
                        ->icon('heroicon-o-map-pin')
                        ->schema([
                            Forms\Components\Grid::make(3)->schema([
                                Forms\Components\TextInput::make('lati')
                                    ->label('Latitude')
                                    ->numeric()
                                    ->live()
                                    ->required(),
                                Forms\Components\TextInput::make('longi')
                                    ->label('Longitude')
                                    ->numeric()
                                    ->live()
                                    ->required(),
                                Forms\Components\TextInput::make('alti')
                                    ->label('Altitude')
                                    ->numeric()
                                    ->required(),
                            ]),
                            Forms\Components\ViewField::make('map_picker')
                                ->label('Peta Lokasi')
                                ->view('filament.forms.components.location-picker')
                                ->columnSpanFull()
                                ->statePath(''),
                        ]),

                    // ===== SECTION 5: DOKUMENTASI FOTO =====
                    Forms\Components\Section::make('Dokumentasi Foto')
                        ->collapsible()
                        ->description('Ambil foto setelah pembersihan/wawancara selesai.')
                        ->icon('heroicon-o-camera')
                        ->columns(2)
                        ->schema([
                            Forms\Components\FileUpload::make('foto')
                                ->label('📸 Foto Kunjungan Sensus')
                                ->image()
                                ->directory('census/visits')
                                ->columnSpanFull()
                                ->required(),
                            Forms\Components\FileUpload::make('photo_home')
                                ->label('📸 Foto Rumah')
                                ->image()
                                ->directory('census/houses')
                                ->visibility('public'),
                            Forms\Components\FileUpload::make('photo_meter')
                                ->label('📸 Foto Meteran')
                                ->image()
                                ->directory('census/meters')
                                ->visibility('public'),
                        ]),
                ]
            ));
    }

    protected static function buildKuesionerFields(): array
    {
        $questions = Question::where('is_active', true)->orderBy('urutan')->get();

        if ($questions->isEmpty()) {
            return [
                Forms\Components\Section::make('Kuesioner Sensus')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Forms\Components\Placeholder::make('no_q')->content('Belum ada pertanyaan. Hubungi Admin.'),
                    ]),
            ];
        }

        // Group questions by tema
        $grouped = $questions->groupBy('tema');
        $sections = [];

        foreach ($grouped as $tema => $temaQuestions) {
            $fields = [];
            foreach ($temaQuestions as $q) {
                $fieldName = "answers.q_{$q->id}";

                // Build options with poin values visible
                $input = match ($q->tipe) {
                    'text' => Forms\Components\Textarea::make($fieldName)
                        ->label($q->pertanyaan)
                        ->helperText($q->poin > 0 ? "Maks. {$q->poin} poin" : null)
                        ->rows(2),
                    'single_choice' => Forms\Components\Radio::make($fieldName)
                        ->label($q->pertanyaan)
                        ->helperText("Poin: {$q->poin}")
                        ->options(collect($q->opsi ?? [])->pluck('label', 'value')->toArray()),
                    'multiple_choice' => Forms\Components\CheckboxList::make($fieldName)
                        ->label($q->pertanyaan)
                        ->helperText("Poin: {$q->poin}")
                        ->options(collect($q->opsi ?? [])->pluck('label', 'value')->toArray()),
                    'rating' => Forms\Components\Radio::make($fieldName)
                        ->label($q->pertanyaan)
                        ->helperText("Poin: {$q->poin}")
                        ->options([1 => '⭐', 2 => '⭐⭐', 3 => '⭐⭐⭐', 4 => '⭐⭐⭐⭐', 5 => '⭐⭐⭐⭐⭐'])
                        ->inline(),
                    default => Forms\Components\TextInput::make($fieldName)
                        ->label($q->pertanyaan),
                };

                if ($q->wajib) $input->required();
                $fields[] = $input;
            }

            $totalPoin = $temaQuestions->sum('poin');
            $sections[] = Forms\Components\Section::make("📋 {$tema}")
                ->collapsible()
                ->description("Total poin tersedia: {$totalPoin}")
                ->icon('heroicon-o-document-text')
                ->schema($fields);
        }

        // Add notes at the end
        $sections[] = Forms\Components\Textarea::make('census_notes')
            ->label('Catatan Tambahan Surveyor')
            ->rows(2)
            ->columnSpanFull();

        return $sections;
    }

    public static function infolist(\Filament\Infolists\Infolist $infolist): \Filament\Infolists\Infolist
    {
        return $infolist->schema(static::buildSensusInfolistSchema());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nolangg')
                    ->label('No. Pel')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('total_points')
                    ->label('Poin')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                Tables\Columns\TextColumn::make('census_status')
                    ->label('Review')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'valid' => 'success',
                        'revisi' => 'danger',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tgl Input')
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordUrl(fn (SurveyResponse $record): string => Pages\ViewSensus::getUrl(['record' => $record]))
            ->headerActions([
                // Removed redundant header export button
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('surveyor_id', auth()->id());
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSensus::route('/'),
            'create' => Pages\CreateSensus::route('/create'),
            'view' => Pages\ViewSensus::route('/{record}'),
            'edit' => Pages\EditSensus::route('/{record}/edit'),
        ];
    }
}
