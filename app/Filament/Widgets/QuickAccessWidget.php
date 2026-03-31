<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class QuickAccessWidget extends Widget
{
    protected static string $view = 'filament.widgets.quick-access-widget';
    
    protected static ?int $sort = -2; // Show at the top

    protected function getViewData(): array
    {
        $panelId = filament()->getCurrentPanel()->getId();
        $actions = [];

        if ($panelId === 'surveyor') {
            $actions[] = [
                'label' => 'Input Sensus Baru',
                'url' => '/surveyor/sensus/create',
                'icon' => 'heroicon-o-plus-circle',
                'color' => 'success',
            ];
        }

        if ($panelId === 'analyst') {
            $actions[] = [
                'label' => 'Review Sensus Pending',
                'url' => '/analyst/survey-responses?tableFilters[census_status][value]=pending',
                'icon' => 'heroicon-o-magnifying-glass-circle',
                'color' => 'warning',
            ];
        }

        $actions[] = [
            'label' => 'Profil Saya',
            'url' => '/' . $panelId . '/profile',
            'icon' => 'heroicon-o-user-circle',
            'color' => 'primary',
        ];

        return [
            'actions' => $actions,
        ];
    }
}
