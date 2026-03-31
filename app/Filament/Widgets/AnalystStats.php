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
                ->description('Total masuk ke sistem')
                ->descriptionIcon('heroicon-m-document-duplicate')
                ->color('primary'),
            Stat::make('Pending', SurveyResponse::where('census_status', 'pending')->count())
                ->description('Belum divalidasi')
                ->descriptionIcon('heroicon-m-clock')
                ->color('primary'),
            Stat::make('Valid', SurveyResponse::where('census_status', 'valid')->count())
                ->description('Telah disetujui')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('primary'),
            Stat::make('Revisi', SurveyResponse::where('census_status', 'revisi')->count())
                ->description('Perlu perbaikan')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color('primary'),
        ];
    }
}
