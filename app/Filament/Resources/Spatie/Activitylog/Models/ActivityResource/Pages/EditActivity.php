<?php

namespace App\Filament\Resources\Spatie\Activitylog\Models\ActivityResource\Pages;

use App\Filament\Resources\Spatie\Activitylog\Models\ActivityResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditActivity extends EditRecord
{
    protected static string $resource = ActivityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
