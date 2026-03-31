<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveyResponse extends Model
{
    use HasUuids, SoftDeletes, LogsActivity;

    protected static function booted()
    {
        static::saving(function ($surveyResponse) {
            $surveyResponse->total_points = $surveyResponse->calculateTotalScore();
        });
    }

    protected $fillable = [
        'survey_id', 'surveyor_id', 'customer_id', 'session_id', 'ip_address', 'user_agent',
        'geolocation', 'status', 'started_at', 'completed_at', 'consent_given', 'answers',
        'id_pelanggan', 'tahun', 'nolangg', 'nama', 'alamat', 'telepon',
        'pdam_status', 'tarif', 'nometer', 'merk_meter', 'diameter',
        'BApasang', 'BAtutup', 'BAbuka', 'tglPasang', 'tglTutup', 'tglBuka', 'tglBongkar',
        'kas', 'kode_alamat', 'kode_unit', 'jenis_pelayanan', 'KEL', 'lastEdit', 'editBy',
        'lati', 'longi', 'alti', 'foto', 'photo_home', 'photo_meter',
        'census_status', 'census_notes', 'total_points',
    ];

    protected $casts = [
        'geolocation' => 'json',
        'answers' => 'json',
        'consent_given' => 'boolean',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logUnguarded()->logOnlyDirty();
    }

    public function surveyor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'surveyor_id');
    }

    public function survey(): BelongsTo
    {
        return $this->belongsTo(Survey::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function calculateTotalScore(): int
    {
        $total = 0;
        $answers = $this->answers ?? [];

        // Collect all question IDs first
        $questionIds = collect($answers)->keys()
            ->filter(fn ($k) => str_starts_with($k, 'q_'))
            ->map(fn ($k) => str_replace('q_', '', $k))
            ->values();

        if ($questionIds->isEmpty()) return 0;

        // Batch load all questions in 1 query instead of N queries
        $questions = \App\Models\Question::whereIn('id', $questionIds)->get()->keyBy('id');

        foreach ($answers as $key => $value) {
            if (str_starts_with($key, 'q_')) {
                $questionId = str_replace('q_', '', $key);
                $question = $questions->get($questionId);

                if ($question) {
                    if (empty($value)) continue;

                    if ($question->tipe === 'rating') {
                        $total += (int) $value;
                    } else {
                        $total += $question->poin;
                    }
                }
            }
        }

        return $total;
    }
}
