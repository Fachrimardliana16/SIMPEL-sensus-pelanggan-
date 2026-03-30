<?php

namespace App\Filament\Surveyor\Resources\SensusResource\Pages;

use App\Filament\Surveyor\Resources\SensusResource;
use App\Filament\Widgets\SurveyorStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;
use Illuminate\Database\Eloquent\Builder;

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
        return [
            'all' => Tab::make('Semua Sensus'),
            'pending' => Tab::make('⏳ Menunggu')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('census_status', 'pending')),
            'valid' => Tab::make('✅ Disetujui')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('census_status', 'valid')),
            'revisi' => Tab::make('❌ Perlu Revisi')
                ->modifyQueryUsing(fn (Builder $query) => $query->where('census_status', 'revisi')),
        ];
    }
}
