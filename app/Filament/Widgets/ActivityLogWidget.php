<?php

namespace App\Filament\Widgets;

use Spatie\Activitylog\Models\Activity;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class ActivityLogWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = '📜 Log Aktivitas Sistem';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Activity::query()->latest()->limit(10)
            )
            ->columns([
                Tables\Columns\TextColumn::make('causer.name')
                    ->label('User')
                    ->default('System'),
                Tables\Columns\TextColumn::make('description')
                    ->label('Aktivitas'),
                Tables\Columns\TextColumn::make('subject_type')
                    ->label('Modul')
                    ->formatStateUsing(fn ($state) => str_replace('App\\Models\\', '', $state)),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->dateTime('d/m/Y H:i'),
            ]);
    }
}
