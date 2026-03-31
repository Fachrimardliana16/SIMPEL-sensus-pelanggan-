<?php

namespace App\Filament\Resources\PelangganResource\Widgets;

use App\Models\Customer;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class PelangganStatsWidget extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Pelanggan', Customer::count())
                ->description('Di Database')
                ->color('primary'),
            Stat::make('Sudah Sensus', Customer::whereHas('surveyResponses', fn ($q) => $q->where('census_status', 'valid'))->count())
                ->description('Status Sensus Valid')
                ->color('success'),
            Stat::make('Sensus Pending/Revisi', Customer::whereHas('surveyResponses', fn ($q) => $q->whereIn('census_status', ['pending', 'revisi']))->count())
                ->description('Proses Review Analyst')
                ->color('warning'),
            Stat::make('Belum Disurvey', Customer::doesntHave('surveyResponses')->count())
                ->description('Menunggu didata')
                ->color('danger'),
        ];
    }
}
