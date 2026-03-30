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
                ->descriptionIcon('heroicon-m-user')
                ->color('info'),
            Stat::make('⏳ Menunggu', (clone $query)->where('census_status', 'pending')->count())
                ->description('Sedang di-review')
                ->color('warning'),
            Stat::make('✅ Disetujui', (clone $query)->where('census_status', 'valid')->count())
                ->description('Sudah valid')
                ->color('success'),
            Stat::make('❌ Revisi', (clone $query)->where('census_status', 'revisi')->count())
                ->description('Butuh perbaikan')
                ->color('danger'),
        ];
    }
}
