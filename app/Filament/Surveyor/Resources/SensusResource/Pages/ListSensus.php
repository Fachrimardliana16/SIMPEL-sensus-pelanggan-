<?php

namespace App\Filament\Surveyor\Resources\SensusResource\Pages;

use App\Filament\Surveyor\Resources\SensusResource;
use App\Filament\Widgets\SurveyorStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

use App\Models\SurveyResponse;

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
                ->url(fn () => route('export.sensus.pdf')),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            SurveyorStats::class,
        ];
    }

    public function getTabs(): array
    {
        $userId = auth()->id();
        return [
            'all' => Tab::make('Semua Sensus')->badge(SurveyResponse::where('surveyor_id', $userId)->count()),
            'pending' => Tab::make('Menunggu')
                ->badge(SurveyResponse::where('surveyor_id', $userId)->where('census_status', 'pending')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('census_status', 'pending')),
            'valid' => Tab::make('Disetujui')
                ->badge(SurveyResponse::where('surveyor_id', $userId)->where('census_status', 'valid')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('census_status', 'valid')),
            'revisi' => Tab::make('Perlu Revisi')
                ->badge(SurveyResponse::where('surveyor_id', $userId)->where('census_status', 'revisi')->count())
                ->modifyQueryUsing(fn (Builder $query) => $query->where('census_status', 'revisi')),
        ];
    }
}
