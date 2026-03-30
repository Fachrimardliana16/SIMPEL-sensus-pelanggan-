<?php

namespace App\Filament\Resources\Spatie\Activitylog\Models\ActivityResource\Pages;

use App\Filament\Resources\Spatie\Activitylog\Models\ActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateActivity extends CreateRecord
{
    protected static string $resource = ActivityResource::class;
}
