<?php

namespace App\Filament\Widgets;

class AnalystQuickReviewWidget extends QuickActionButtonWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 1;

    public ?string $label       = 'Review Sensus Pending';
    public ?string $description = 'Lihat dan validasi data masuk';
    public ?string $icon        = 'heroicon-o-magnifying-glass-circle';
    public ?string $color       = 'warning';
    public ?string $url         = '/analyst/survey-responses?tableFilters[census_status][value]=pending';
}
