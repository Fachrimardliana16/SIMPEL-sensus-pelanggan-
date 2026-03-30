<?php

namespace App\Filament\Widgets;

use App\Models\SurveyResponse;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class LatestSensusWidget extends BaseWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 'full';
    protected static ?string $heading = '📥 Input Sensus Terbaru';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                SurveyResponse::query()->latest()->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('nolangg')
                    ->label('No. Pel'),
                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama Pelanggan'),
                Tables\Columns\TextColumn::make('surveyor.name')
                    ->label('Petugas'),
                Tables\Columns\TextColumn::make('census_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'valid' => 'success',
                        'revisi' => 'danger',
                        default => 'warning',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Waktu')
                    ->since(),
            ]);
    }
}
