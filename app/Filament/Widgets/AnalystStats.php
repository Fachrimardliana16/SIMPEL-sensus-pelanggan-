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
                ->descriptionIcon('heroicon-m-clipboard-document-list')
                ->color('info'),
            Stat::make('⏳ Pending', SurveyResponse::where('census_status', 'pending')->count())
                ->description('Belum divalidasi')
                ->color('warning'),
            Stat::make('✅ Valid', SurveyResponse::where('census_status', 'valid')->count())
                ->description('Telah disetujui')
                ->color('success'),
            Stat::make('❌ Revisi', SurveyResponse::where('census_status', 'revisi')->count())
                ->description('Perlu perbaikan')
                ->color('danger'),
        ];
    }
}
