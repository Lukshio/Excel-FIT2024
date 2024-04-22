<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'value',
        'description'
    ];

    public static function heatingMode () {
        return self::where('name', 'heating_mode')->first()->value;
    }

    public static function elHeating () {
        return (bool) self::where('name', 'el_heating')->first()->value;
    }

    public static function combined_min_temp () {
        return self::where('name', 'combined_switch_min_temp')->first()->value;
    }
}
