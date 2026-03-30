<?php

namespace App\Filament\Widgets;

use App\Models\SurveyResponse;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;

class MySubmissionTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Tren Kiriman Saya (7 Hari Terakhir)';
    protected static ?int $sort = 5;

    protected function getData(): array
    {
        $user = auth()->user();
        
        // Manual trend calculation for speed/simplicity
        $data = [];
        $labels = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $count = SurveyResponse::where('surveyor_id', $user->id)
                ->whereDate('created_at', $date)
                ->count();
            
            $data[] = $count;
            $labels[] = $date->format('d M');
        }

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Sensus',
                    'data' => $data,
                    'fill' => 'start',
                    'borderColor' => '#3b82f6',
                    'backgroundColor' => 'rgba(59, 130, 246, 0.1)',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
