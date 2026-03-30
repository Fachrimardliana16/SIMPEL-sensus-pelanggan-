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
                ->description('Jumlah kiriman yang telah Anda buat')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('info'),
            Stat::make('Sensus Valid (Approved)', (clone $query)->where('census_status', 'valid')->count())
                ->description('Sensus sudah disetujui Analyst')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
            Stat::make('Perlu Revisi / Ditolak', (clone $query)->where('census_status', 'revisi')->count())
                ->description('Harap periksa kembali kiriman Anda')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('danger'),
        ];
    }
}
