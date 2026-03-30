<?php

namespace App\Filament\Analyst\Resources\SurveyResponseResource\Pages;

use App\Filament\Analyst\Resources\SurveyResponseResource;
use App\Filament\Widgets\AnalystStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSurveyResponses extends ListRecords
{
    protected static string $resource = SurveyResponseResource::class;

    protected function getHeaderActions(): array
    {
        return [
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
            AnalystStats::class,
        ];
    }
}
