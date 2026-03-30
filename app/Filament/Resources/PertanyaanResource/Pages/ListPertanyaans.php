<?php

namespace App\Filament\Resources\PertanyaanResource\Pages;

use App\Filament\Resources\PertanyaanResource;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;

class ListPertanyaans extends ListRecords
{
    protected static string $resource = PertanyaanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Pertanyaan'),
        ];
    }
}
