<?php

namespace App\Filament\Concerns;

use Filament\Infolists\Components;

trait HasSensusInfolist
{
    /**
     * Build the shared infolist schema for viewing a SurveyResponse record.
     * Used by both Analyst and Surveyor panels to avoid code duplication.
     */
    protected static function buildSensusInfolistSchema(): array
    {
        return [
            Components\Split::make([
                // LEFT COLUMN — Data details
                Components\Grid::make(1)
                    ->schema([
                        Components\Section::make('Informasi Pelanggan & Teknis')
                            ->icon('heroicon-o-user-circle')
                            ->description('Detail data pelanggan dari database dan hasil sensus.')
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
                                    ]),
                            ]),

                        Components\Section::make('Lokasi Sensus')
                            ->icon('heroicon-o-map')
                            ->schema([
                                Components\TextEntry::make('location_map')
                                    ->label('Peta Lokasi Sensus')
                                    ->html()
                                    ->state(fn ($record) => $record->lati && $record->longi ? sprintf(
                                        '<iframe src="https://maps.google.com/maps?q=%s,%s&hl=id&z=15&output=embed" width="100%%" height="300" frameborder="0" style="border:0; border-radius: 8px;" allowfullscreen></iframe>',
                                        $record->lati,
                                        $record->longi
                                    ) : '<span class="text-gray-500 italic">Koordinat tidak tersedia</span>')
                                    ->columnSpanFull(),
                            ]),

                        Components\Section::make('Validasi & Hasil Sensus')
                            ->icon('heroicon-o-check-badge')
                            ->columns(2)
                            ->schema([
                                Components\TextEntry::make('census_status')
                                    ->label('Status Validasi')
                                    ->badge()
                                    ->color(fn (string $state): string => match ($state) {
                                        'valid' => 'success',
                                        'revisi' => 'danger',
                                        default => 'warning',
                                    }),
                                Components\TextEntry::make('total_points')->label('Skor Integritas')->badge()->color('info'),
                                Components\TextEntry::make('census_notes')->label('Catatan Review')->columnSpanFull()->placeholder('Tidak ada catatan'),
                            ]),

                        Components\Section::make('Hasil Wawancara (Kuesioner)')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                Components\Grid::make(1)
                                    ->schema(fn ($record) => static::buildQAEntries($record)),
                            ]),
                    ])->grow(),

                // RIGHT COLUMN — Photos
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
                                    ->height(200)
                                    ->label('Foto Depan Rumah'),
                                Components\ImageEntry::make('photo_meter')
                                    ->height(200)
                                    ->label('Foto Tampilan Meteran'),
                            ]),
                    ]),
            ])->from('md')->columnSpanFull()
        ];
    }

    /**
     * Build Q&A entries from survey answers.
     * Batch loads all questions in 1 query to avoid N+1 problem.
     */
    protected static function buildQAEntries($record): array
    {
        $answers = $record->answers ?? [];

        // Batch load all questions in 1 query
        $questionIds = collect($answers)->keys()
            ->filter(fn ($k) => str_starts_with($k, 'q_'))
            ->map(fn ($k) => str_replace('q_', '', $k))
            ->values();

        if ($questionIds->isEmpty()) {
            return [
                Components\TextEntry::make('no_answers')
                    ->label('Kuesioner')
                    ->state('Belum ada jawaban yang direkam.')
                    ->placeholder('—'),
            ];
        }

        $questions = \App\Models\Question::whereIn('id', $questionIds)
            ->get()
            ->keyBy('id');

        return collect($answers)
            ->map(function ($value, $key) use ($questions) {
                if (!str_starts_with($key, 'q_')) return null;
                $id = str_replace('q_', '', $key);
                $question = $questions->get($id);
                return [
                    'question' => $question?->pertanyaan ?? "Pertanyaan #{$id}",
                    'answer' => is_array($value) ? implode(', ', $value) : $value,
                    'urutan' => $question?->urutan ?? 99,
                ];
            })
            ->filter()
            ->sortBy('urutan')
            ->values()
            ->map(fn ($item, $index) =>
                Components\TextEntry::make("answer_q_{$index}")
                    ->label(($index + 1) . '. ' . $item['question'])
                    ->state($item['answer'])
                    ->prose()
            )
            ->toArray();
    }
}
