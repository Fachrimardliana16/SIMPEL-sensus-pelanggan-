<?php

namespace App\Filament\Resources\Spatie\Permission\Models\PermissionResource\Pages;

use App\Filament\Resources\Spatie\Permission\Models\PermissionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewPermission extends ViewRecord
{
    protected static string $resource = PermissionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
