<?php

namespace App\Abstracts;

use App\Models\Device;


/**
 * @abstract
 */
abstract class AbstractTemperatureSensorModel
{
    protected $sensors = null;
    protected bool $test_mode = false;

    public function __construct() {
        $this->sensors = Device::whereHas('deviceType', function ($query) {
            $query->where('name', 'temperature_sensor');
        })->get();

        $this->test_mode = (bool) env('GENERATE_TEMPERATURE_MODE');
    }

    /**
     * @param $uuid
     * @return float|null returns temperature in celsius of sensor with $uuid
     */
    abstract public function getCelsiusTemperature($uuid) : ?float;

    /**
     * @return array of all sensors with temperatures with uuid, temperature and sensor info
     */
    abstract public function getAllTemperatures($sensors) : array;

    /**
     * @return array of available sensors
     */
    abstract public function listAvailableSensors() : array;
}
