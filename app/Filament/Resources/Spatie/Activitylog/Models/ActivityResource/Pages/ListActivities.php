<?php

namespace App\Filament\Resources\Spatie\Activitylog\Models\ActivityResource\Pages;

use App\Filament\Resources\Spatie\Activitylog\Models\ActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListActivities extends ListRecords
{
    protected static string $resource = ActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
