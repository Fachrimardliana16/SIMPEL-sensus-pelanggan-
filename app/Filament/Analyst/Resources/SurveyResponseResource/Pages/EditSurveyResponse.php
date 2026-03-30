<?php

namespace App\Filament\Analyst\Resources\SurveyResponseResource\Pages;

use App\Filament\Analyst\Resources\SurveyResponseResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSurveyResponse extends EditRecord
{
    protected static string $resource = SurveyResponseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
