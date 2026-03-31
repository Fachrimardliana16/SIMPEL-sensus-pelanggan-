<?php

namespace App\Filament\Analyst\Resources\SurveyResponseResource\Pages;

use App\Filament\Analyst\Resources\SurveyResponseResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Forms;

class ViewSurveyResponse extends ViewRecord
{
    protected static string $resource = SurveyResponseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('print_pdf')
                ->label('Cetak PDF')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->url(fn ($record) => route('export.sensus.print', $record->id))
                ->openUrlInNewTab(),

            Actions\Action::make('approve_edit')
                ->label('Approve')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->modalHeading('Finalisasi Data Sensus')
                ->form([
                    Forms\Components\Grid::make(3)->schema([
                        Forms\Components\TextInput::make('nolangg')->label('No. Langganan')->required(),
                        Forms\Components\TextInput::make('nama')->label('Nama Pelanggan')->required(),
                        Forms\Components\TextInput::make('telepon')->label('Telepon'),
                        Forms\Components\TextInput::make('KEL')->label('Kelurahan'),
                        Forms\Components\TextInput::make('kode_unit')->label('Kode Unit'),
                        Forms\Components\Select::make('pdam_status')
                            ->label('Status PDAM')
                            ->options(['aktif' => 'Aktif', 'tutup' => 'Tutup', 'bongkar' => 'Bongkar'])
                            ->required(),
                        Forms\Components\TextInput::make('nometer')->label('No. Meter'),
                        Forms\Components\TextInput::make('merk_meter')->label('Merk Meter'),
                        Forms\Components\TextInput::make('diameter')->label('Diameter'),
                    ]),
                    Forms\Components\Textarea::make('alamat')->label('Alamat Lengkap')->columnSpanFull()->rows(2),
                    Forms\Components\Textarea::make('census_notes')
                        ->label('Catatan Verifikasi (Opsional)')
                        ->rows(2)
                        ->columnSpanFull(),
                ])
                ->fillForm(fn ($record): array => [
                    'nolangg' => $record->nolangg,
                    'nama' => $record->nama,
                    'telepon' => $record->telepon,
                    'KEL' => $record->KEL,
                    'kode_unit' => $record->kode_unit,
                    'pdam_status' => $record->pdam_status,
                    'nometer' => $record->nometer,
                    'merk_meter' => $record->merk_meter,
                    'diameter' => $record->diameter,
                    'alamat' => $record->alamat,
                    'census_notes' => $record->census_notes,
                ])
                ->action(function ($record, array $data): void {
                    $record->update([
                        'nolangg' => $data['nolangg'],
                        'nama' => $data['nama'],
                        'telepon' => $data['telepon'],
                        'KEL' => $data['KEL'],
                        'kode_unit' => $data['kode_unit'],
                        'pdam_status' => $data['pdam_status'],
                        'nometer' => $data['nometer'],
                        'merk_meter' => $data['merk_meter'],
                        'diameter' => $data['diameter'],
                        'alamat' => $data['alamat'],
                        'census_notes' => $data['census_notes'],
                        'census_status' => 'valid',
                    ]);
                    \Filament\Notifications\Notification::make()->title('Sensus berhasil divalidasi')->success()->send();
                })
                ->visible(fn ($record) => $record->census_status !== 'valid'),

            Actions\Action::make('request_revisi')
                ->label('Ulang')
                ->icon('heroicon-o-arrow-path')
                ->color('danger')
                ->modalHeading('Permintaan Revisi Sensus')
                ->form([
                    Forms\Components\Textarea::make('census_notes')
                        ->label('Alasan Revisi')
                        ->required()
                        ->rows(3),
                ])
                ->action(function ($record, array $data): void {
                    $record->update([
                        'census_notes' => $data['census_notes'],
                        'census_status' => 'revisi',
                    ]);
                    \Filament\Notifications\Notification::make()->title('Permintaan revisi dikirim')->danger()->send();
                })
                ->visible(fn ($record) => $record->census_status !== 'revisi'),
        ];
    }
}
