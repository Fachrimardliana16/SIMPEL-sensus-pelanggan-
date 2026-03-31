<?php

namespace App\Filament\Surveyor\Resources\SensusResource\Pages;

use App\Filament\Surveyor\Resources\SensusResource;
use App\Models\Question;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Filament\Infolists\Infolist;
use Filament\Infolists\Components;

class ViewSensus extends ViewRecord
{
    protected static string $resource = SensusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('print_pdf')
                ->label('Cetak PDF')
                ->icon('heroicon-o-printer')
                ->color('gray')
                ->url(fn ($record) => route('export.sensus.print', $record->id))
                ->openUrlInNewTab(),
            Actions\EditAction::make(),
        ];
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Components\Split::make([
                    // ─── LEFT COLUMN ───
                    Components\Grid::make(1)
                        ->schema([
                            Components\Section::make('Identitas Pelanggan')
                                ->icon('heroicon-o-user-circle')
                                ->description('Data pelanggan dan informasi teknis meter.')
                                ->schema([
                                    Components\Grid::make(2)->schema([
                                        Components\TextEntry::make('nolangg')->label('No. Langganan')->weight('bold')->color('primary'),
                                        Components\TextEntry::make('nama')->label('Nama Lengkap')->weight('bold'),
                                        Components\TextEntry::make('telepon')->label('Telepon')->icon('heroicon-o-phone'),
                                        Components\TextEntry::make('pdam_status')
                                            ->label('Status Pelayanan')
                                            ->badge()
                                            ->color(fn ($state) => $state === 'aktif' ? 'success' : 'danger'),
                                    ]),
                                    Components\TextEntry::make('alamat')->label('Alamat')->columnSpanFull(),
                                    Components\Fieldset::make('Detail Meter & Tarif')
                                        ->columns(3)
                                        ->schema([
                                            Components\TextEntry::make('nometer')->label('No. Meter'),
                                            Components\TextEntry::make('merk_meter')->label('Merk Meter'),
                                            Components\TextEntry::make('diameter')->label('Diameter'),
                                            Components\TextEntry::make('tarif')->label('Tarif'),
                                            Components\TextEntry::make('KEL')->label('Kelurahan'),
                                            Components\TextEntry::make('kecamatan')->label('Kecamatan'),
                                        ]),
                                ]),

                            Components\Section::make('Lokasi Sensus')
                                ->icon('heroicon-o-map-pin')
                                ->collapsible()
                                ->schema([
                                    Components\Grid::make(3)->schema([
                                        Components\TextEntry::make('lati')->label('Latitude'),
                                        Components\TextEntry::make('longi')->label('Longitude'),
                                        Components\TextEntry::make('alti')->label('Altitude'),
                                    ]),
                                    Components\TextEntry::make('location_map')
                                        ->label('Peta Lokasi')
                                        ->html()
                                        ->state(fn ($record) => $record->lati && $record->longi ? sprintf(
                                            '<iframe src="https://maps.google.com/maps?q=%s,%s&hl=id&z=15&output=embed" width="100%%" height="250" frameborder="0" style="border:0; border-radius: 8px;" allowfullscreen></iframe>',
                                            $record->lati, $record->longi
                                        ) : '<span class="text-gray-400 italic text-sm">Koordinat tidak tersedia.</span>')
                                        ->columnSpanFull(),
                                ]),

                            Components\Section::make('Validasi & Catatan')
                                ->icon('heroicon-o-check-badge')
                                ->columns(2)
                                ->schema([
                                    Components\TextEntry::make('census_status')
                                        ->label('Status Validasi')
                                        ->badge()
                                        ->color(fn ($state) => match ($state) {
                                            'valid'  => 'success',
                                            'revisi' => 'danger',
                                            default  => 'warning',
                                        }),
                                    Components\TextEntry::make('total_points')->label('Skor Integritas')->badge()->color('info'),
                                    Components\TextEntry::make('census_notes')->label('Catatan Reviewer')->columnSpanFull()->placeholder('Tidak ada catatan.'),
                                ]),

                            Components\Section::make('Hasil Wawancara (Kuesioner)')
                                ->icon('heroicon-o-document-text')
                                ->collapsible()
                                ->schema([
                                    Components\Grid::make(1)
                                        ->schema(fn ($record) =>
                                            collect($record->answers ?? [])
                                                ->map(function ($value, $key) {
                                                    if (!str_starts_with($key, 'q_')) return null;
                                                    $id = str_replace('q_', '', $key);
                                                    $question = Question::find($id);
                                                    return [
                                                        'question' => $question?->pertanyaan ?? "Pertanyaan #{$id}",
                                                        'answer'   => is_array($value) ? implode(', ', $value) : $value,
                                                        'urutan'   => $question?->urutan ?? 99,
                                                    ];
                                                })
                                                ->filter()
                                                ->sortBy('urutan')
                                                ->values()
                                                ->map(fn ($item, $index) =>
                                                    Components\TextEntry::make("answer_q_{$index}")
                                                        ->label(($index + 1) . '. ' . $item['question'])
                                                        ->state($item['answer'])
                                                )
                                                ->toArray()
                                        ),
                                ]),
                        ])->grow(),

                    // ─── RIGHT COLUMN ───
                    Components\Grid::make(1)
                        ->schema([
                            Components\Section::make('Dokumentasi Foto')
                                ->icon('heroicon-o-camera')
                                ->schema([
                                    Components\ImageEntry::make('foto')
                                        ->label('Foto Kunjungan')
                                        ->height(200)
                                        ->columnSpanFull(),
                                    Components\ImageEntry::make('photo_home')
                                        ->label('Foto Depan Rumah')
                                        ->height(180),
                                    Components\ImageEntry::make('photo_meter')
                                        ->label('Foto Meteran')
                                        ->height(180),
                                ]),
                        ]),
                ])->from('md')->columnSpanFull()
            ]);
    }
}
