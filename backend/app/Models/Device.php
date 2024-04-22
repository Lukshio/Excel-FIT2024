<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'protocol_id',
        'device_type_id',
        'description',
        'pinout',
        'uuid',
        'heating_source',
        'heating_water_sensor',
        'fireplace_open',
        'overheat_protection'
    ];

    public function deviceType () {
        return $this->belongsTo(DeviceType::class, 'device_type_id');
    }

    public function protocol () {
        return $this->belongsTo(Protocols::class, 'protocol_id');
    }

    public function user () {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function zoneSensor () {
        return $this->belongsTo(Zone::class, 'uuid', 'main_sensor_uuid');
    }

    public function zoneRelay () {
        return $this->belongsTo(Zone::class, 'id', 'relay_device_id');
    }

    public function temperatureLog () {
        return $this->hasMany(TemperatureLog::class, 'uuid');
    }

    public function getType () {
        return $this->deviceType();
    }

    public static function fireplaceOpen () {
        return self::where('fireplace_open', 1)->get();
    }

    public static function isEnabledOverheatProtection () {
        return self::where('overheat_protection', 1)->get()->isNotEmpty();
    }
}
