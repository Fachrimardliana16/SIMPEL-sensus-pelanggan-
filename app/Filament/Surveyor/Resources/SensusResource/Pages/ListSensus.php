<?php

namespace App\Filament\Surveyor\Resources\SensusResource\Pages;

use App\Filament\Surveyor\Resources\SensusResource;
use App\Filament\Widgets\SurveyorStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSensus extends ListRecords
{
    protected static string $resource = SensusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Input Sensus Baru'),
            Actions\Action::make('export_pdf')
                ->label('Cetak PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->color('danger')
                ->url(fn () => route('reports.census-pdf')),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            SurveyorStats::class,
        ];
    }
}
