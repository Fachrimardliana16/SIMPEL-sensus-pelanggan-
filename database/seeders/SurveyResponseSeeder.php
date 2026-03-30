<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Survey;
use App\Models\SurveyResponse;
use App\Models\Customer;
use App\Models\User;
use App\Models\Question;

class SurveyResponseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pastikan ada Survei Master
        $survey = Survey::firstOrCreate(
            ['slug' => 'sensus-2026'],
            [
                'title' => 'Sensus Pelanggan PDAM 2026',
                'description' => 'Survei teknis dan profil pelanggan tahap 1.',
                'status' => 'published',
            ]
        );

        // 2. Ambil Surveyor (User pertama)
        $surveyor = User::first(); 
        if (!$surveyor) return;

        // 3. Ambil 10 Pelanggan secara acak
        $customers = Customer::inRandomOrder()->limit(10)->get();

        // 4. Ambil Pertanyaan Aktif
        $questions = Question::where('is_active', true)->get();

        foreach ($customers as $customer) {
            $answers = [];
            foreach ($questions as $q) {
                // Generate dummy answer
                if ($q->tipe === 'single_choice') {
                    $opsi = $q->opsi ?? [];
                    if (!empty($opsi)) {
                        $answers["q_{$q->id}"] = $opsi[array_rand($opsi)]['value'];
                    }
                } elseif ($q->tipe === 'rating') {
                    $answers["q_{$q->id}"] = rand(4, 5);
                } else {
                    $answers["q_{$q->id}"] = "Observasi: Kondisi lapangan baik.";
                }
            }

            SurveyResponse::create([
                'survey_id' => $survey->id,
                'surveyor_id' => $surveyor->id,
                'customer_id' => $customer->id,
                'nolangg' => $customer->nolangg,
                'nama' => $customer->nama,
                'alamat' => $customer->alamat,
                'telepon' => $customer->telepon,
                'kode_unit' => $customer->kode_unit,
                'tarif' => $customer->tarif,
                'nometer' => $customer->nometer,
                'merk_meter' => $customer->merk_meter,
                'diameter' => $customer->diameter,
                'lati' => $customer->lati ?? ('-7.38' . rand(1000, 9000)),
                'longi' => $customer->longi ?? ('109.35' . rand(1000, 9000)),
                'alti' => $customer->alti ?? rand(50, 150),
                'pdam_status' => 'aktif',
                'status' => 'completed',
                'census_status' => 'pending',
                'answers' => $answers,
                'started_at' => now()->subDays(rand(0, 5)),
                'completed_at' => now(),
            ]);
        }
    }
}
