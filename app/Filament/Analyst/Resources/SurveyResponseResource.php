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
                Forms\Components\Tabs::make('Census Review')
                    ->tabs([
                        Forms\Components\Tabs\Tab::make('Identitas Pelanggan')
                            ->icon('heroicon-o-identification')
                            ->schema([
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('nolangg')->label('No. Langganan')->disabled(),
                                        Forms\Components\TextInput::make('nama')->label('Nama Lengkap')->disabled(),
                                        Forms\Components\TextInput::make('telepon')->label('Telepon')->disabled(),
                                    ]),
                                Forms\Components\Textarea::make('alamat')->label('Alamat Lengkap')->disabled()->columnSpanFull(),
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('telepon')->label('Telepon')->disabled(),
                                        Forms\Components\TextInput::make('KEL')->label('Kelurahan')->disabled(),
                                        Forms\Components\TextInput::make('kode_unit')->label('Kode Unit')->disabled(),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Info Teknis')
                            ->icon('heroicon-o-wrench-screwdriver')
                            ->schema([
                                Forms\Components\Grid::make(3)
                                    ->schema([
                                        Forms\Components\TextInput::make('nometer')->label('No. Meter')->disabled(),
                                        Forms\Components\TextInput::make('merk_meter')->label('Merk Meter')->disabled(),
                                        Forms\Components\TextInput::make('tarif')->label('Tarif')->disabled(),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Media')
                            ->icon('heroicon-o-camera')
                            ->schema([
                                Forms\Components\Grid::make(2)
                                    ->schema([
                                        Forms\Components\FileUpload::make('photo_home')
                                            ->label('Foto Rumah')
                                            ->disabled()
                                            ->image(),
                                        Forms\Components\FileUpload::make('photo_meter')
                                            ->label('Foto Meteran')
                                            ->disabled()
                                            ->image(),
                                    ]),
                            ]),

                        Forms\Components\Tabs\Tab::make('Jawaban Kuesioner')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Forms\Components\KeyValue::make('answers')
                                    ->label('Jawaban')
                                    ->disabled()
                                    ->columnSpanFull(),
                            ]),

                        Forms\Components\Tabs\Tab::make('Validasi Analyst')
                            ->icon('heroicon-o-check-badge')
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
                                    ->columnSpanFull(),
                            ]),
                    ])->columnSpanFull()
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
                Tables\Actions\Action::make('approve')
                    ->label('Terima (Approve)')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->button()
                    ->action(fn (SurveyResponse $record) => $record->update(['census_status' => 'valid']))
                    ->visible(fn (SurveyResponse $record) => $record->census_status !== 'valid'),
                Tables\Actions\Action::make('revisi')
                    ->label('Minta Revisi')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->button()
                    ->action(fn (SurveyResponse $record) => $record->update(['census_status' => 'revisi']))
                    ->visible(fn (SurveyResponse $record) => $record->census_status !== 'revisi'),
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                ]),
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
