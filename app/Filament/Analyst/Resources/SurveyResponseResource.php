<?php

namespace App\Filament\Analyst\Resources;

use App\Filament\Analyst\Resources\SurveyResponseResource\Pages;
use App\Models\SurveyResponse;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class SurveyResponseResource extends Resource
{
    protected static ?string $model = SurveyResponse::class;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?string $navigationLabel = 'Review Sensus';
    protected static ?string $modelLabel = 'Data Sensus';
    protected static ?string $pluralModelLabel = 'Review Sensus';
    protected static ?string $breadcrumb = 'Review';

    // Analyst cannot create new census entries
    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Identitas Pelanggan')
                    ->icon('heroicon-o-identification')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('nolangg')->label('No. Langganan')->disabled(),
                        Forms\Components\TextInput::make('nama')->label('Nama Lengkap')->disabled(),
                        Forms\Components\TextInput::make('telepon')->label('Telepon')->disabled(),
                        Forms\Components\Textarea::make('alamat')->label('Alamat Lengkap')->disabled()->columnSpanFull(),
                        Forms\Components\TextInput::make('KEL')->label('Kelurahan')->disabled(),
                        Forms\Components\TextInput::make('kode_unit')->label('Kode Unit')->disabled(),
                    ]),

                Forms\Components\Section::make('Info Teknis')
                    ->icon('heroicon-o-wrench-screwdriver')
                    ->columns(3)
                    ->schema([
                        Forms\Components\TextInput::make('nometer')->label('No. Meter')->disabled(),
                        Forms\Components\TextInput::make('merk_meter')->label('Merk Meter')->disabled(),
                        Forms\Components\TextInput::make('tarif')->label('Tarif')->disabled(),
                        Forms\Components\TextInput::make('diameter')->label('Diameter')->disabled(),
                        Forms\Components\TextInput::make('jenis_pelayanan')->label('Jenis Pelayanan')->disabled(),
                    ]),

                Forms\Components\Section::make('Media & Dokumentasi')
                    ->icon('heroicon-o-camera')
                    ->columns(2)
                    ->schema([
                        Forms\Components\FileUpload::make('foto')
                            ->label('Foto Kunjungan')
                            ->disabled()
                            ->image()
                            ->columnSpanFull(),
                        Forms\Components\FileUpload::make('photo_home')
                            ->label('Foto Rumah')
                            ->disabled()
                            ->image(),
                        Forms\Components\FileUpload::make('photo_meter')
                            ->label('Foto Meteran')
                            ->disabled()
                            ->image(),
                    ]),

                Forms\Components\Section::make('Jawaban Kuesioner')
                    ->icon('heroicon-o-document-text')
                    ->schema([
                        Forms\Components\KeyValue::make('answers')
                            ->label('Jawaban')
                            ->disabled()
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Section::make('Validasi Analyst')
                    ->icon('heroicon-o-check-badge')
                    ->columns(1)
                    ->schema([
                        Forms\Components\Select::make('census_status')
                            ->label('Status Validasi')
                            ->options([
                                'pending' => '⏳ Pending',
                                'valid' => '✅ Valid / Memadai',
                                'revisi' => '❌ Perlu Revisi',
                            ])
                            ->required(),
                        Forms\Components\Textarea::make('census_notes')
                            ->label('Catatan Reviewer')
                            ->placeholder('Berikan alasan jika perlu revisi...')
                            ->columnSpanFull(),
                    ]),
            ]);
    }

    public static function infolist(\Filament\Infolists\Infolist $infolist): \Filament\Infolists\Infolist
    {
        return $infolist
            ->schema([
                \Filament\Infolists\Components\Split::make([
                    \Filament\Infolists\Components\Grid::make(1)
                        ->schema([
                            \Filament\Infolists\Components\Section::make('Informasi Pelanggan & Teknis')
                                ->icon('heroicon-o-user-circle')
                                ->description('Detail data pelanggan dari database dan hasil sensus.')
                                ->schema([
                                    \Filament\Infolists\Components\Grid::make(2)->schema([
                                        \Filament\Infolists\Components\TextEntry::make('nolangg')->label('No. Langganan')->weight('bold')->color('primary'),
                                        \Filament\Infolists\Components\TextEntry::make('nama')->label('Nama Lengkap')->weight('bold'),
                                        \Filament\Infolists\Components\TextEntry::make('telepon')->label('Telepon')->icon('heroicon-o-phone'),
                                        \Filament\Infolists\Components\TextEntry::make('pdam_status')
                                            ->label('Status Pelayanan')
                                            ->badge()
                                            ->color(fn ($state) => $state === 'aktif' ? 'success' : 'danger'),
                                    ]),
                                    \Filament\Infolists\Components\TextEntry::make('alamat')->label('Alamat')->columnSpanFull(),
                                    
                                    \Filament\Infolists\Components\Fieldset::make('Detail Meter & Tarif')
                                        ->columns(3)
                                        ->schema([
                                            \Filament\Infolists\Components\TextEntry::make('nometer')->label('No. Meter'),
                                            \Filament\Infolists\Components\TextEntry::make('merk_meter')->label('Merk Meter'),
                                            \Filament\Infolists\Components\TextEntry::make('diameter')->label('Diameter'),
                                            \Filament\Infolists\Components\TextEntry::make('tarif')->label('Tarif'),
                                        ]),
                                ]),

                            \Filament\Infolists\Components\Section::make('Validasi & Hasil Sensus')
                                ->icon('heroicon-o-check-badge')
                                ->columns(2)
                                ->schema([
                                    \Filament\Infolists\Components\TextEntry::make('census_status')
                                        ->label('Status Validasi')
                                        ->badge()
                                        ->color(fn (string $state): string => match ($state) {
                                            'valid' => 'success',
                                            'revisi' => 'danger',
                                            default => 'warning',
                                        }),
                                    \Filament\Infolists\Components\TextEntry::make('total_points')->label('Skor Integritas')->badge()->color('info'),
                                    \Filament\Infolists\Components\TextEntry::make('census_notes')->label('Catatan Review')->columnSpanFull()->placeholder('Tidak ada catatan'),
                                ]),

                            \Filament\Infolists\Components\Section::make('Hasil Wawancara (Kuesioner)')
                                ->icon('heroicon-o-document-text')
                                ->schema([
                                    \Filament\Infolists\Components\Grid::make(1)
                                        ->schema(fn ($record) => 
                                            collect($record->answers ?? [])
                                                ->map(function ($value, $key) {
                                                    if (!str_starts_with($key, 'q_')) return null;
                                                    $id = str_replace('q_', '', $key);
                                                    $question = \App\Models\Question::find($id);
                                                    return [
                                                        'question' => $question?->pertanyaan ?? "Pertanyaan #{$id}",
                                                        'answer' => is_array($value) ? implode(', ', $value) : $value,
                                                        'urutan' => $question?->urutan ?? 99,
                                                    ];
                                                })
                                                ->filter()
                                                ->sortBy('urutan')
                                                ->values()
                                                ->map(fn ($item, $index) => 
                                                    \Filament\Infolists\Components\TextEntry::make("answer_q_{$index}")
                                                        ->label(($index + 1) . '. ' . $item['question'])
                                                        ->state($item['answer'])
                                                )
                                                ->toArray()
                                        ),
                                ]),
                        ])->grow(),

                    \Filament\Infolists\Components\Grid::make(1)
                        ->schema([
                            \Filament\Infolists\Components\Section::make('Dokumentasi Foto')
                                ->icon('heroicon-o-camera')
                                ->schema([
                                    \Filament\Infolists\Components\ImageEntry::make('foto')
                                        ->label('Foto Kunjungan')
                                        ->height(200)
                                        ->columnSpanFull(),
                                    \Filament\Infolists\Components\ImageEntry::make('photo_home')
                                        ->height(200)
                                        ->label('Foto Depan Rumah'),
                                    \Filament\Infolists\Components\ImageEntry::make('photo_meter')
                                        ->height(200)
                                        ->label('Foto Tampilan Meteran'),
                                ]),
                        ]),
                ])->from('md')->columnSpanFull()
            ]);
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
                    ->label('Total Poin')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                Tables\Columns\TextColumn::make('census_status')
                    ->label('Validasi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'valid' => 'success',
                        'revisi' => 'danger',
                        default => 'warning',
                    })
                    ->sortable(),
                Tables\Columns\TextColumn::make('surveyor.name')
                    ->label('Surveyor')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tgl Input')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('census_status')
                    ->label('Status Validasi')
                    ->options([
                        'pending' => 'Pending',
                        'valid' => 'Valid',
                        'revisi' => 'Revisi',
                    ]),
            ])
            ->actions([
                Tables\Actions\Action::make('approve_edit')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->button()
                    ->modalHeading('Finalisasi Data Sensus')
                    ->modalDescription('Anda dapat menyesuaikan data sebelum menerima sensus ini sebagai VALID.')
                    ->form([
                        Forms\Components\Grid::make(3)->schema([
                            Forms\Components\TextInput::make('nolangg')->label('No. Langganan')->required(),
                            Forms\Components\TextInput::make('nama')->label('Nama Pelanggan')->required(),
                            Forms\Components\TextInput::make('telepon')->label('Telepon'),
                            Forms\Components\TextInput::make('KEL')->label('Kelurahan'),
                            Forms\Components\TextInput::make('kode_unit')->label('Kode Unit'),
                            Forms\Components\Select::make('pdam_status')
                                ->label('Status Pelayanan')
                                ->options(['aktif' => 'Aktif', 'tutup' => 'Tutup', 'bongkar' => 'Bongkar'])
                                ->required(),
                            Forms\Components\TextInput::make('nometer')->label('No. Meter'),
                            Forms\Components\TextInput::make('merk_meter')->label('Merk Meter'),
                            Forms\Components\TextInput::make('diameter')->label('Diameter'),
                        ]),
                        Forms\Components\Textarea::make('alamat')->label('Alamat Lengkap')->columnSpanFull()->rows(2),
                        Forms\Components\Textarea::make('census_notes')
                            ->label('Catatan Verifikasi (Opsional)')
                            ->rows(2)
                            ->columnSpanFull(),
                    ])
                    ->fillForm(fn (SurveyResponse $record): array => [
                        'nolangg' => $record->nolangg,
                        'nama' => $record->nama,
                        'telepon' => $record->telepon,
                        'KEL' => $record->KEL,
                        'kode_unit' => $record->kode_unit,
                        'pdam_status' => $record->pdam_status,
                        'nometer' => $record->nometer,
                        'merk_meter' => $record->merk_meter,
                        'diameter' => $record->diameter,
                        'alamat' => $record->alamat,
                        'census_notes' => $record->census_notes,
                    ])
                    ->action(function (SurveyResponse $record, array $data): void {
                        $record->update([
                            'nolangg' => $data['nolangg'],
                            'nama' => $data['nama'],
                            'telepon' => $data['telepon'],
                            'KEL' => $data['KEL'],
                            'kode_unit' => $data['kode_unit'],
                            'pdam_status' => $data['pdam_status'],
                            'nometer' => $data['nometer'],
                            'merk_meter' => $data['merk_meter'],
                            'diameter' => $data['diameter'],
                            'alamat' => $data['alamat'],
                            'census_notes' => $data['census_notes'],
                            'census_status' => 'valid',
                        ]);
                        \Filament\Notifications\Notification::make()->title('Sensus berhasil divalidasi')->success()->send();
                    })
                    ->visible(fn (SurveyResponse $record) => $record->census_status !== 'valid'),
                
                Tables\Actions\Action::make('request_revisi')
                    ->label('Ulang')
                    ->icon('heroicon-o-arrow-path')
                    ->color('danger')
                    ->button()
                    ->modalHeading('Permintaan Revisi Sensus')
                    ->form([
                        Forms\Components\Textarea::make('census_notes')
                            ->label('Alasan Revisi')
                            ->placeholder('Jelaskan bagian mana yang perlu diperbaiki oleh surveyor...')
                            ->required()
                            ->rows(3),
                    ])
                    ->action(function (SurveyResponse $record, array $data): void {
                        $record->update([
                            'census_notes' => $data['census_notes'],
                            'census_status' => 'revisi',
                        ]);
                        \Filament\Notifications\Notification::make()->title('Permintaan revisi dikirim')->danger()->send();
                    })
                    ->visible(fn (SurveyResponse $record) => $record->census_status !== 'revisi'),

                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                ]),
            ])
            ->headerActions([
                // Removed redundant header export button
            ])
            ->recordUrl(fn (SurveyResponse $record): string => Pages\ViewSurveyResponse::getUrl(['record' => $record]))
            ->bulkActions([]);
    }

    // Analyst sees ALL census data (no surveyor scoping)
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery();
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSurveyResponses::route('/'),
            'view' => Pages\ViewSurveyResponse::route('/{record}'),
            'edit' => Pages\EditSurveyResponse::route('/{record}/edit'),
        ];
    }
}
