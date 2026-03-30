<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasUuids;

    protected $guarded = [];

    protected $casts = [
        'opsi' => 'json',
        'wajib' => 'boolean',
        'is_active' => 'boolean',
    ];
}
