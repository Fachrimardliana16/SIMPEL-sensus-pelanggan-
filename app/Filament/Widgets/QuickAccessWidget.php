<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;

class QuickAccessWidget extends Widget
{
    protected static string $view = 'filament.widgets.quick-access-widget';
    
    protected static ?int $sort = -2; // Show at the top

    protected function getViewData(): array
    {
        $user = auth()->user();
        $actions = [];

        if ($user->hasRole('Surveyor')) {
            $actions[] = [
                'label' => 'Input Sensus Baru',
                'url' => '/surveyor/sensus/create',
                'icon' => 'heroicon-o-plus-circle',
                'color' => 'success',
            ];
        }

        if ($user->hasRole('Analyst')) {
            $actions[] = [
                'label' => 'Review Sensus Pending',
                'url' => '/analyst/review-sensus?tableFilters[census_status][value]=pending',
                'icon' => 'heroicon-o-magnifying-glass-circle',
                'color' => 'warning',
            ];
        }

        return [
            'actions' => $actions,
        ];
    }
}
