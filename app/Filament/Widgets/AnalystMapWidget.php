<?php

namespace App\Filament\Widgets;

use App\Models\SurveyResponse;
use Filament\Widgets\Widget;

class AnalystMapWidget extends Widget
{
    protected static string $view = 'filament.widgets.analyst-map-widget';
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 15;

    protected function getViewData(): array
    {
        $locations = SurveyResponse::where('census_status', 'valid')
            ->whereNotNull('lati')
            ->whereNotNull('longi')
            ->where('lati', '!=', 0)
            ->where('longi', '!=', 0)
            ->get()
            ->map(function ($item) {
                // simple normalization if coordinates are weirdly large
                $lat = (float) $item->lati;
                $lng = (float) $item->longi;
                if (abs($lat) > 1000) $lat = $lat / 1000000000000000;
                if (abs($lng) > 1000) $lng = $lng / 1000000000000000;

                return [
                    'lat' => $lat,
                    'lng' => $lng,
                    'title' => $item->nama . ' (' . $item->nolangg . ')',
                ];
            })->values()->toArray();

        return [
            'locations'    => $locations,
            'mapCenterLat' => -7.3906,
            'mapCenterLng' => 109.3649,
        ];
    }
}
