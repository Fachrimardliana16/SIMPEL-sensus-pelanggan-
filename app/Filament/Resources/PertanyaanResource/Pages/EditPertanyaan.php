<?php

namespace App\Filament\Resources\PertanyaanResource\Pages;

use App\Filament\Resources\PertanyaanResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Actions;

class EditPertanyaan extends EditRecord
{
    protected static string $resource = PertanyaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
