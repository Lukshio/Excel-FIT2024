<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Zone extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'temperature',
        'main_sensor_uuid',
        'description',
        'relay_device_id',
        'zone_type_id',
        'auto_heating',
        'kpi_treshold',
        'user_id',
        'use_program'
    ];

    public function zoneType () {
        return $this->belongsTo(ZoneType::class, 'zone_type_id');
    }

    public function scheduler () {
        return $this->hasMany(Scheduler::class, 'id');
    }

    public function sensor () {
        return $this->belongsTo(Device::class, 'main_sensor_uuid', 'uuid');
    }

    public function relay () {
        return $this->belongsTo(Device::class, 'relay_device_id', 'id');
    }

    public static function heatingZones ()
    {
        return self::whereHas('zoneType', function ($query){
            $query->where('name', 'heating_zone');
        })->with('sensor')->with('relay')->get();
    }

    public static function waterSwitchZones ()
    {
        return self::whereHas('zoneType', function ($query){
            $query->where('name', 'water_switch');
        })->with('sensor')->with('relay')->get();
    }

    public static function otherZones ()
    {
        return self::whereHas('zoneType', function ($query){
            $query->where('name', 'empty_zone');
        })->with('sensor')->with('relay')->get();
    }

    public static function boilerZone ()
    {
        return self::whereHas('zoneType', function ($query){
            $query->where('name', 'boiler');
        })->with('sensor')->with('relay')->get();
    }
}
