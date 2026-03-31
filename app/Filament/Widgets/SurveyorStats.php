<?php

namespace App\Filament\Widgets;

use App\Models\SurveyResponse;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class SurveyorStats extends BaseWidget
{
    protected function getStats(): array
    {
        $query = SurveyResponse::where('surveyor_id', auth()->id());
        
        return [
            Stat::make('Total Sensus Anda', $query->count())
                ->description('Jumlah kiriman')
                ->descriptionIcon('heroicon-m-document-duplicate')
                ->color('primary'),
            Stat::make('Menunggu', (clone $query)->where('census_status', 'pending')->count())
                ->description('Sedang di-review')
                ->descriptionIcon('heroicon-m-clock')
                ->color('primary'),
            Stat::make('Disetujui', (clone $query)->where('census_status', 'valid')->count())
                ->description('Sudah valid')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('primary'),
            Stat::make('Revisi', (clone $query)->where('census_status', 'revisi')->count())
                ->description('Butuh perbaikan')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('primary'),
        ];
    }
}
