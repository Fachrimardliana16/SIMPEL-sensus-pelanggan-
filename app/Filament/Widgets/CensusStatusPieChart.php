<?php

namespace App\Filament\Widgets;

use App\Models\SurveyResponse;
use Filament\Widgets\ChartWidget;

class CensusStatusPieChart extends ChartWidget
{
    protected static ?string $heading = 'Distribusi Status Sensus';
    protected static ?int $sort = 4;

    protected function getData(): array
    {
        $valid = SurveyResponse::where('census_status', 'valid')->count();
        $pending = SurveyResponse::where('census_status', 'pending')->count();
        $revisi = SurveyResponse::where('census_status', 'revisi')->count();

        return [
            'datasets' => [
                [
                    'label' => 'Status',
                    'data' => [$valid, $pending, $revisi],
                    'backgroundColor' => ['#22c55e', '#f59e0b', '#ef4444'],
                ],
            ],
            'labels' => ['Valid', 'Pending', 'Revisi'],
        ];
    }

    protected function getType(): string
    {
        return 'pie';
    }
}
