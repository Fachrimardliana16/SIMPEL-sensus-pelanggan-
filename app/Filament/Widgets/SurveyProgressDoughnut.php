<?php

namespace App\Filament\Widgets;

use App\Models\Customer;
use App\Models\SurveyResponse;
use Filament\Widgets\ChartWidget;

class SurveyProgressDoughnut extends ChartWidget
{
    protected static ?string $heading = 'Progres Sensus Keseluruhan';
    protected static ?int $sort = 1;
    protected static ?string $maxHeight = '250px';
    protected int | string | array $columnSpan = 'full';

    protected function getOptions(): array
    {
        return [
            'scales' => [
                'x' => ['display' => false],
                'y' => ['display' => false],
            ],
        ];
    }

    protected function getData(): array
    {
        $totalCustomers = Customer::count();
        $surveyed = SurveyResponse::where('census_status', 'valid')->distinct('customer_id')->count();
        $remaining = max(0, $totalCustomers - $surveyed);

        return [
            'datasets' => [
                [
                    'label' => 'Jumlah Pelanggan',
                    'data' => [$surveyed, $remaining],
                    'backgroundColor' => ['#3b82f6', '#e2e8f0'],
                ],
            ],
            'labels' => ['Sudah Sensus', 'Belum Sensus'],
        ];
    }

    protected function getType(): string
    {
        return 'doughnut';
    }
}
