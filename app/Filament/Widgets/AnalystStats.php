<?php

namespace App\Filament\Widgets;

use App\Models\SurveyResponse;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AnalystStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Sensus', SurveyResponse::count())
                ->description('Total kiriman dari lapangan')
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('info'),
            Stat::make('Sensus Valid (Approved)', SurveyResponse::where('census_status', 'valid')->count())
                ->description('Sudah diverifikasi')
                ->descriptionIcon('heroicon-m-check-badge')
                ->color('success'),
            Stat::make('Perlu Revisi', SurveyResponse::where('census_status', 'revisi')->count())
                ->description('Ditolak / Perlu perbaikan')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
            Stat::make('Rata-rata Skor', number_format(SurveyResponse::avg('total_points') ?? 0, 1))
                ->description('Poin teknis rata-rata')
                ->descriptionIcon('heroicon-m-star')
                ->color('warning'),
        ];
    }
}
