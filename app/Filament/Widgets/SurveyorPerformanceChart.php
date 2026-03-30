<?php

namespace App\Filament\Widgets;

use App\Models\SurveyResponse;
use App\Models\User;
use Filament\Widgets\ChartWidget;

class SurveyorPerformanceChart extends ChartWidget
{
    protected static ?string $heading = 'Performa Surveyor (Top 10)';
    protected static ?int $sort = 3;

    protected function getData(): array
    {
        $data = User::role('Surveyor')
            ->withCount('surveyResponses')
            ->orderByDesc('survey_responses_count')
            ->limit(10)
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Total Sensus',
                    'data' => $data->pluck('survey_responses_count')->toArray(),
                    'backgroundColor' => '#3b82f6',
                ],
            ],
            'labels' => $data->pluck('name')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'bar';
    }
}
