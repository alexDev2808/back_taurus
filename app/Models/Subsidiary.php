<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Subsidiary extends Model
{
    use HasUuids;

    protected $fillable = [
        'name',
        'slug'
    ];


    public function areas() {
        return $this->hasMany(Area::class);
    }
}
