<?php

namespace App\Models;

use App\Abstracts\AbstractTemperatureSensorModel;
use azolee\DataFormat\CelsiusDataProcessor;
use azolee\DS18B20;

class DallasSensorModelModel extends AbstractTemperatureSensorModel
{
    public $sensors;
    public function __construct()
    {
        parent::__construct();

        // setup data processor
        $celsiusDP = new CelsiusDataProcessor();
        $celsiusDP->setPrecision(2);

        $this->sensors = DS18B20::loadSensors($celsiusDP);
    }

    /**
     * @inheritdoc
     * @param $uuid
     * @return float
     * @throws \Exception
     */
    public function getCelsiusTemperature($uuid) : ?float
    {
        if ($this->test_mode) return rand(15, 25);

        if (!isset($this->sensors[$uuid]['celsius'])) return null;
        else return $this->sensors[$uuid]['celsius'];
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function getAllTemperatures ($sensors): array
    {
        $combinedArray = [];

        if ($this->test_mode) {
            $wireS = Device::whereHas('protocol', function ($query){
                $query->where('name', '1wire');
            })->get(['name', 'description', 'uuid']);
            foreach ($wireS as $sensor){
                $combinedArray[] =[
                    'uuid' => $sensor->uuid,
                    'temperature' => rand(15, 25),
                    'sensor' => $sensor
                ];
            }
        } else {
            foreach ($this->sensors as $uuid=>$temperature){
                $sensor = Device::where('uuid', $uuid)->first(['name', 'description']) ?? $uuid;
                $combinedArray[] =[
                    'uuid' => $uuid,
                    'temperature' => $temperature['celsius'],
                    'sensor' => $sensor
                ];
            }
        }

        return $combinedArray;
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function listAvailableSensors() : array
    {
        return DS18B20::loadSensors();
    }
}
