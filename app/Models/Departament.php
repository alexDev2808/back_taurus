<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class Departament extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'subarea_id',
    ];


    public function subarea()
    {
        return $this->belongsTo(Subarea::class);
    }

}
