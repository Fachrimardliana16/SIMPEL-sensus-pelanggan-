<?php

namespace App\Filament\Widgets;

class SurveyorProfileWidget extends QuickActionButtonWidget
{
    protected static ?int $sort = 2;
    protected int | string | array $columnSpan = 1;

    public ?string $label       = 'Profil Saya';
    public ?string $description = 'Ubah data dan kata sandi akun';
    public ?string $icon        = 'heroicon-o-user-circle';
    public ?string $color       = 'primary';
    public ?string $url         = '/surveyor/profile';
}
