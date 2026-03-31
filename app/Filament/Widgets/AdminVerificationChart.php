<?php

namespace App\Filament\Widgets;

use App\Models\SurveyResponse;
use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\DB;

class AdminVerificationChart extends ChartWidget
{
    protected static ?string $heading = 'Aktivitas Verifikasi Analyst (7 Hari)';
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 1;

    protected function getData(): array
    {
        // We track 'valid' status as a proxy for verification activity
        $data = SurveyResponse::select(DB::raw('date(updated_at) as date'), DB::raw('count(*) as count'))
            ->where('census_status', 'valid')
            ->where('updated_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'datasets' => [
                [
                    'label' => 'Data Terverifikasi',
                    'data' => $data->pluck('count')->toArray(),
                    'backgroundColor' => '#10b981',
                    'borderColor' => '#10b981',
                ],
            ],
            'labels' => $data->pluck('date')->toArray(),
        ];
    }

    protected function getType(): string
    {
        return 'line';
    }
}
