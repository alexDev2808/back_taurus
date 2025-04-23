<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Subarea extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'area_id'
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
    }
}
