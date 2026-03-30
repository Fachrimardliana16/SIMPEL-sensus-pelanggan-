<?php

namespace App\Filament\Analyst\Resources\SurveyResponseResource\Pages;

use App\Filament\Analyst\Resources\SurveyResponseResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewSurveyResponse extends ViewRecord
{
    protected static string $resource = SurveyResponseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('approve')
                ->label('Terima (Approve)')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->action(fn ($record) => $record->update(['census_status' => 'valid']))
                ->visible(fn ($record) => $record->census_status !== 'valid'),
            Actions\Action::make('revisi')
                ->label('Minta Revisi')
                ->icon('heroicon-o-arrow-uturn-left')
                ->color('danger')
                ->requiresConfirmation()
                ->action(fn ($record) => $record->update(['census_status' => 'revisi']))
                ->visible(fn ($record) => $record->census_status !== 'revisi'),
            Actions\EditAction::make()->label('Ubah Detail'),
        ];
    }
}
