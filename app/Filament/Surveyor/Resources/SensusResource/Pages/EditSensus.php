<?php

namespace App\Filament\Surveyor\Resources\SensusResource\Pages;

use App\Filament\Surveyor\Resources\SensusResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSensus extends EditRecord
{
    protected static string $resource = SensusResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // If the census was marked as 'revisi', automatically set it back to 'pending' upon resubmission
        if ($this->record->census_status === 'revisi') {
            $data['census_status'] = 'pending';
            // Optional: clear the note since it has been addressed
            // $data['census_notes'] = null;
        }

        return $data;
    }
}
