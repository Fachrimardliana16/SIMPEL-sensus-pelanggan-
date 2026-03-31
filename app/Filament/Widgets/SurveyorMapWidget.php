<?php

namespace App\Filament\Widgets;

use App\Models\SurveyResponse;
use Filament\Widgets\Widget;

class SurveyorMapWidget extends Widget
{
    protected static string $view = 'filament.widgets.surveyor-map-widget';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 20;

    protected function getViewData(): array
    {
        $userId = auth()->id();

        $locations = SurveyResponse::where('surveyor_id', $userId)
            ->whereNotNull('lati')
            ->whereNotNull('longi')
            ->where('lati', '!=', 0)
            ->where('longi', '!=', 0)
            ->get()
            ->map(function ($item) {
                $lat = (float) $item->lati;
                $lng = (float) $item->longi;
                if (abs($lat) > 1000) $lat = $lat / 1000000000000000;
                if (abs($lng) > 1000) $lng = $lng / 1000000000000000;

                $statusColor = match($item->census_status) {
                    'valid'  => 'green',
                    'revisi' => 'red',
                    default  => 'orange',
                };

                return [
                    'lat'    => $lat,
                    'lng'    => $lng,
                    'title'  => $item->nama . ' (' . $item->nolangg . ')',
                    'status' => $item->census_status ?? 'pending',
                    'color'  => $statusColor,
                ];
            })->values()->toArray();

        return [
            'locations'    => $locations,
            'mapCenterLat' => -7.3906,
            'mapCenterLng' => 109.3649,
        ];
    }
}
