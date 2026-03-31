<?php

namespace App\Filament\Widgets;

use App\Models\SurveyResponse;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentSensusTable extends BaseWidget
{
    protected static ?string $heading = 'Sensus Terbaru Saya';
    protected static ?int $sort = 6;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                SurveyResponse::query()
                    ->where('surveyor_id', auth()->id())
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('created_at')->label('Waktu')->dateTime('d/m/Y H:i')->sortable(),
                Tables\Columns\TextColumn::make('customer.nama')->label('Pelanggan'),
                Tables\Columns\TextColumn::make('customer.id_pelanggan')->label('ID Pelanggan'),
                Tables\Columns\TextColumn::make('total_points')->label('Poin')->badge()->color('info'),
                Tables\Columns\TextColumn::make('census_status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'valid' => 'success',
                        'pending' => 'warning',
                        'revisi' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->paginated(false);
    }
}
