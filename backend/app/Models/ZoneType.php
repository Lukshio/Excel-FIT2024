<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ZoneType extends Model
{
    use HasFactory;

    protected $table = 'zone_types';

    protected $fillable = [
        'name'
    ];

    public function zones ()
    {
        return $this->hasMany(Zone::class, 'id');
    }
}
