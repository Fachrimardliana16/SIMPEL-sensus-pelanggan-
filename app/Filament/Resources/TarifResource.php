<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TarifResource\Pages;
use App\Filament\Resources\TarifResource\RelationManagers;
use App\Models\Tarif;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TarifResource extends Resource
{
    protected static ?string $model = Tarif::class;

    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';
    protected static ?string $navigationGroup = 'Data Master';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('id_tarif'),
                Forms\Components\TextInput::make('kode_tarif'),
                Forms\Components\TextInput::make('golongan')
                    ->required(),
                Forms\Components\TextInput::make('batas1')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('batas2')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('batas3')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('rp1')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('rp2')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('rp3')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('rp4')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('minimun')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('jasameter')
                    ->required()
                    ->numeric()
                    ->default(0),
                Forms\Components\TextInput::make('kas1'),
                Forms\Components\TextInput::make('kas2'),
                Forms\Components\TextInput::make('kas3'),
                Forms\Components\TextInput::make('denda')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('id_tarif')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kode_tarif')
                    ->searchable(),
                Tables\Columns\TextColumn::make('golongan')
                    ->searchable(),
                Tables\Columns\TextColumn::make('batas1')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('batas2')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('batas3')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rp1')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rp2')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rp3')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rp4')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('minimun')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('jasameter')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('kas1')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kas2')
                    ->searchable(),
                Tables\Columns\TextColumn::make('kas3')
                    ->searchable(),
                Tables\Columns\TextColumn::make('denda')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTarifs::route('/'),
            'create' => Pages\CreateTarif::route('/create'),
            'edit' => Pages\EditTarif::route('/{record}/edit'),
        ];
    }
}
