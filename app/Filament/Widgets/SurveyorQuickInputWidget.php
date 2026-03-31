<?php

namespace App\Filament\Widgets;

class SurveyorQuickInputWidget extends QuickActionButtonWidget
{
    protected static ?int $sort = 1;
    protected int | string | array $columnSpan = 1;

    public ?string $label       = 'Input Sensus Baru';
    public ?string $description = 'Mulai input data pelanggan';
    public ?string $icon        = 'heroicon-o-plus-circle';
    public ?string $color       = 'success';
    public ?string $url         = '/surveyor/sensus/create';
}
