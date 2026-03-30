<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasUuids, SoftDeletes;

    protected $guarded = [];

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
}
