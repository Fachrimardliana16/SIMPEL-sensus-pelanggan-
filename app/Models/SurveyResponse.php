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

    protected $guarded = [];

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
        
        foreach ($answers as $key => $value) {
            if (str_starts_with($key, 'q_')) {
                $questionId = str_replace('q_', '', $key);
                $question = \App\Models\Question::find($questionId);
                
                if ($question) {
                    if (empty($value)) continue;

                    if ($question->tipe === 'rating') {
                        $total += (int) $value; 
                    } else {
                        // Default use question point value if answered
                        $total += $question->poin;
                    }
                }
            }
        }
        
        return $total;
    }
}
