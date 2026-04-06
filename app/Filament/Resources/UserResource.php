<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';
    protected static ?string $navigationGroup = 'Manajemen Akun';

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::where('is_verified', false)->count() ?: null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'danger';
    }

    public static function canViewAny(): bool
    {
        return auth()->user()?->hasRole(['Super Admin', 'Admin']) ?? false;
    }

    public static function canCreate(): bool
    {
        return auth()->user()?->hasRole(['Super Admin', 'Admin']) ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return auth()->user()?->hasRole(['Super Admin', 'Admin']) ?? false;
    }

    public static function canDelete(Model $record): bool
    {
        return auth()->user()?->hasRole(['Super Admin', 'Admin']) ?? false;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Split::make([
                    Forms\Components\Section::make('General Information')
                        ->description('Basic user details.')
                        ->icon('heroicon-o-user')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('email')
                                ->email()
                                ->unique(ignoreRecord: true)
                                ->required()
                                ->maxLength(255),
                            Forms\Components\TextInput::make('password')
                                ->password()
                                ->dehydrated(fn ($state) => filled($state))
                                ->required(fn (string $context): bool => $context === 'create')
                                ->maxLength(255),
                        ])->columns(2),
                    
                    Forms\Components\Group::make([
                        Forms\Components\Section::make('Roles & Permissions')
                            ->description('Assign access levels.')
                            ->icon('heroicon-o-shield-check')
                            ->schema([
                                Forms\Components\Select::make('roles')
                                    ->multiple()
                                    ->relationship('roles', 'name')
                                    ->preload(),
                            ]),
                        
                        Forms\Components\Section::make('Account Status')
                            ->icon('heroicon-o-check-circle')
                            ->schema([
                                Forms\Components\DateTimePicker::make('email_verified_at')
                                    ->native(false),
                                Forms\Components\Toggle::make('is_verified')
                                    ->required()
                                    ->label('Status Verifikasi'),
                            ]),
                    ]),
                ])->from('md')->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->searchable(),
                Tables\Columns\TextColumn::make('roles.name')
                    ->badge()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_verified')
                    ->boolean()
                    ->label('Verified')
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\Action::make('verify')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->hidden(fn (User $record): bool => $record->is_verified)
                    ->action(function (User $record) {
                        $record->update(['is_verified' => true]);
                        
                        \Filament\Notifications\Notification::make()
                            ->title('User Berhasil Diverifikasi')
                            ->success()
                            ->send();
                    })
                    ->requiresConfirmation(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\BulkAction::make('verify')
                        ->label('Verifikasi Terpilih')
                        ->icon('heroicon-o-check-badge')
                        ->color('success')
                        ->action(fn (\Illuminate\Support\Collection $records) => $records->each->update(['is_verified' => true]))
                        ->requiresConfirmation()
                        ->deselectRecordsAfterCompletion(),
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
