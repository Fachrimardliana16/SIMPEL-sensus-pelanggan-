<?php

namespace App\Filament\Analyst\Resources\SurveyResponseResource\Pages;

use App\Filament\Analyst\Resources\SurveyResponseResource;
use App\Filament\Widgets\AnalystStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

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
                ->url(fn () => route('export.sensus.pdf')),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            AnalystStats::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('SemuaData'),
            'pending' => Tab::make('⏳ Pending')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('census_status', 'pending')),
            'valid' => Tab::make('✅ Valid')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('census_status', 'valid')),
            'revisi' => Tab::make('❌ Revisi')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('census_status', 'revisi')),
        ];
    }
}
