<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'slug',
        'subsidiary_id'
    ];

    public function subsidiary()
    {
        return $this->belongsTo(Subsidiary::class);
    }

    public function subareas()
    {
        return $this->hasMany(Subarea::class);
    }

}
