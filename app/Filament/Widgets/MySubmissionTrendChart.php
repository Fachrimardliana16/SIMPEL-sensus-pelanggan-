<?php

namespace App\Filament\Widgets;

use App\Models\SurveyResponse;
use Filament\Widgets\ChartWidget;
use Flowframe\Trend\Trend;
use Flowframe\Trend\TrendValue;
use Illuminate\Support\Carbon;

class MySubmissionTrendChart extends ChartWidget
{
    protected static ?string $heading = 'Tren Kiriman Saya';
    protected static ?int $sort = 5;
    protected static ?string $maxHeight = '250px';
    protected int | string | array $columnSpan = 'full';

    public ?string $filter = 'daily';

    protected function getFilters(): ?array
    {
        return [
            'daily' => '7 Hari Terakhir',
            'monthly' => '6 Bulan Terakhir',
        ];
    }

    protected function getData(): array
    {
        $user = auth()->user();
        $filter = $this->filter;
        
        $data = [];
        $labels = [];

        if ($filter === 'monthly') {
            for ($i = 5; $i >= 0; $i--) {
                $month = Carbon::today()->startOfMonth()->subMonths($i);
                $count = SurveyResponse::where('surveyor_id', $user->id)
                    ->whereMonth('created_at', $month->month)
                    ->whereYear('created_at', $month->year)
                    ->count();
                
                $data[] = $count;
                $labels[] = $month->format('M Y');
            }
        } else {
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::today()->subDays($i);
                $count = SurveyResponse::where('surveyor_id', $user->id)
                    ->whereDate('created_at', $date)
                    ->count();
                
                $data[] = $count;
                $labels[] = $date->format('d M');
            }
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
