<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PertanyaanResource\Pages;
use App\Models\Question;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PertanyaanResource extends Resource
{
    protected static ?string $model = Question::class;

    protected static ?string $navigationLabel = 'Pertanyaan Sensus';
    protected static ?string $pluralModelLabel = 'Pertanyaan Sensus';
    protected static ?string $modelLabel = 'Pertanyaan';
    protected static ?string $navigationIcon = 'heroicon-o-question-mark-circle';
    protected static ?string $navigationGroup = 'Sensus & Kuesioner';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Data Pertanyaan')
                    ->icon('heroicon-o-question-mark-circle')
                    ->schema([
                        Forms\Components\Select::make('tema')
                            ->label('Tema / Kategori')
                            ->options([
                                'Kualitas Air' => 'Kualitas Air',
                                'Tekanan & Aliran' => 'Tekanan & Aliran',
                                'Meteran & Peralatan' => 'Meteran & Peralatan',
                                'Tagihan & Pembayaran' => 'Tagihan & Pembayaran',
                                'Pelayanan' => 'Pelayanan',
                                'Umum' => 'Umum',
                            ])
                            ->required()
                            ->searchable()
                            ->createOptionForm([
                                Forms\Components\TextInput::make('tema')->label('Tema baru')->required(),
                            ])
                            ->createOptionUsing(fn (array $data) => $data['tema']),
                        Forms\Components\Textarea::make('pertanyaan')
                            ->label('Teks Pertanyaan')
                            ->required()
                            ->rows(2)
                            ->columnSpanFull(),
                        Forms\Components\Select::make('tipe')
                            ->label('Tipe Jawaban')
                            ->options([
                                'single_choice' => 'Pilihan Tunggal',
                                'multiple_choice' => 'Pilihan Ganda',
                                'text' => 'Teks Bebas',
                                'rating' => 'Rating (1-5)',
                            ])
                            ->required()
                            ->live(),
                        Forms\Components\TextInput::make('poin')
                            ->label('Poin / Bobot')
                            ->numeric()
                            ->default(0),
                        Forms\Components\TextInput::make('urutan')
                            ->label('Urutan Tampil')
                            ->numeric()
                            ->default(0),
                        Forms\Components\Toggle::make('wajib')
                            ->label('Wajib Diisi')
                            ->default(true),
                        Forms\Components\Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true),
                    ])->columns(2),

                Forms\Components\Section::make('Pilihan Jawaban')
                    ->icon('heroicon-o-list-bullet')
                    ->visible(fn (Forms\Get $get) => in_array($get('tipe'), ['single_choice', 'multiple_choice']))
                    ->schema([
                        Forms\Components\Repeater::make('opsi')
                            ->label('')
                            ->schema([
                                Forms\Components\TextInput::make('label')->label('Teks Jawaban')->required(),
                                Forms\Components\TextInput::make('value')->label('Value')->required(),
                            ])
                            ->columns(2)
                            ->addActionLabel('Tambah Pilihan'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('urutan')
            ->columns([
                Tables\Columns\TextColumn::make('urutan')
                    ->label('#')
                    ->sortable()
                    ->width('50px'),
                Tables\Columns\TextColumn::make('tema')
                    ->label('Tema')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('pertanyaan')
                    ->label('Pertanyaan')
                    ->searchable()
                    ->limit(60),
                Tables\Columns\TextColumn::make('tipe')
                    ->label('Tipe')
                    ->badge()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('poin')
                    ->label('Poin')
                    ->sortable(),
                Tables\Columns\IconColumn::make('wajib')
                    ->label('Wajib')
                    ->boolean(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Aktif')
                    ->boolean(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('tema')
                    ->options(fn () => Question::distinct()->pluck('tema', 'tema')->toArray()),
                Tables\Filters\TernaryFilter::make('is_active')->label('Status Aktif'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ])
            ->reorderable('urutan');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPertanyaans::route('/'),
            'create' => Pages\CreatePertanyaan::route('/create'),
            'edit' => Pages\EditPertanyaan::route('/{record}/edit'),
        ];
    }
}
