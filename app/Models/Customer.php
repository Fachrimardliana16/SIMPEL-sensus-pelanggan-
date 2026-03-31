<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Customer extends Model
{
    use HasUuids, SoftDeletes, LogsActivity;

    protected $fillable = [
        'id_pelanggan', 'nolangg', 'tahun', 'nama', 'alamat', 'telepon',
        'KEL', 'kecamatan', 'kode_unit', 'nometer', 'merk_meter', 'diameter',
        'tarif', 'jenis_pelayanan', 'kode_alamat', 'kas', 'status',
        'BApasang', 'BAtutup', 'BAbuka',
        'tglPasang', 'tglTutup', 'tglBuka', 'tglBongkar',
        'lati', 'longi', 'alti',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs();
    }

    protected $casts = [
        'tglPasang' => 'date',
        'tglTutup' => 'date',
        'tglBuka' => 'date',
        'tglBongkar' => 'date',
    ];

    public function statusRel()
    {
        return $this->belongsTo(Status::class, 'status', 'kode');
    }

    public function unitRel()
    {
        return $this->belongsTo(Unit::class, 'kode_unit', 'kode');
    }

    public function tarifRel()
    {
        return $this->belongsTo(Tarif::class, 'tarif', 'id_tarif');
    }

    public function surveyResponses()
    {
        return $this->hasMany(SurveyResponse::class, 'customer_id');
    }
}
