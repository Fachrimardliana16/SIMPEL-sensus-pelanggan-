<?php

namespace App\Filament\Resources\PelangganResource\Pages;

use App\Filament\Resources\PelangganResource;
use App\Imports\PelangganImport;
use Filament\Resources\Pages\ListRecords;
use Filament\Actions;
use Filament\Forms;
use Filament\Notifications\Notification;
use Maatwebsite\Excel\Facades\Excel;

class ListPelanggans extends ListRecords
{
    protected static string $resource = PelangganResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->label('Tambah Pelanggan'),
            Actions\Action::make('import')
                ->label('Import Excel')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->form([
                    Forms\Components\FileUpload::make('file')
                        ->label('File Excel (.xlsx/.csv)')
                        ->disk('public')
                        ->directory('temp-imports')
                        ->visibility('public')
                        ->required(),
                ])
                ->action(function (array $data) {
                    try {
                        ini_set('memory_limit', '1024M');
                        ini_set('max_execution_time', '0');
                        
                        $filePath = storage_path('app/public/' . $data['file']);
                        Excel::import(new PelangganImport, $filePath);
                        \Illuminate\Support\Facades\Storage::disk('public')->delete($data['file']);
                        
                        Notification::make()->title('Import berhasil!')->success()->send();
                    } catch (\Exception $e) {
                        \Illuminate\Support\Facades\Log::error('Gagal Import Pelanggan: ' . $e->getMessage(), [
                            'exception' => $e,
                            'file' => $data['file']
                        ]);
                        Notification::make()
                            ->title('Gagal import!')
                            ->body('Error: ' . $e->getMessage() . '. Cek log untuk detail.')
                            ->danger()
                            ->send();
                    }
                }),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            \App\Filament\Resources\PelangganResource\Widgets\PelangganStatsWidget::class,
        ];
    }

    public function getTabs(): array
    {
        return [
            'all' => \Filament\Resources\Components\Tab::make('Semua Pelanggan')->badge(\App\Models\Customer::count()),
            'surveyed' => \Filament\Resources\Components\Tab::make('Sensus Valid')
                ->badge(\App\Models\Customer::whereHas('surveyResponses', fn ($q) => $q->where('census_status', 'valid'))->count())
                ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => 
                    $query->whereHas('surveyResponses', fn ($q) => $q->where('census_status', 'valid'))
                ),
            'not_surveyed' => \Filament\Resources\Components\Tab::make('Belum Sensus/Valid')
                ->badge(\App\Models\Customer::whereDoesntHave('surveyResponses', fn ($q) => $q->where('census_status', 'valid'))->count())
                ->modifyQueryUsing(fn (\Illuminate\Database\Eloquent\Builder $query) => 
                    $query->whereDoesntHave('surveyResponses', fn ($q) => $q->where('census_status', 'valid'))
                ),
        ];
    }
}
