<?php

namespace App\Http\Controllers;

use App\Models\DallasSensorModelModel;
use App\Models\Device;
use App\Models\MqttSensorModelModel;

class TemperatureController extends Controller
{
    public DallasSensorModelModel $dallasModel;
    public MqttSensorModelModel $mqttModel;

    public function __construct()
    {
        $this->dallasModel = new DallasSensorModelModel();
        $this->mqttModel = new MqttSensorModelModel();
    }

    public function getAllTemps (): array
    {
        $dallas = Device::whereHas('deviceType', function ($query) {
            $query->where('name', 'temperature_sensor');
        })->whereHas('protocol', function ($query){
            $query->where('name', '1wire');
        })->get();

        $mqtt = Device::whereHas('deviceType', function ($query) {
                $query->where('name', 'temperature_sensor');
            })->whereHas('protocol', function ($query){
            $query->where('name', 'mqtt');
        })->get();

        $temps = [];
        if ($dallas->isNotEmpty()) $temps = array_merge($temps, $this->dallasModel->getAllTemperatures($dallas));
        if ($mqtt->isNotEmpty()) $temps = array_merge($temps, $this->mqttModel->getAllTemperatures($mqtt));

        return $temps;
    }

    /**
     * @throws \Exception
     */
    public function getSingleTemp ($uuid): ?float
    {
        $sensor = Device::whereHas('deviceType', function ($query) {
            $query->where('name', 'temperature_sensor');
        })->where('uuid', $uuid)->with('protocol')->first();

        if ($sensor->count() == 0) throw new \Exception("Sensor not found");

        if ($sensor->protocol['name'] === '1wire') return $this->dallasModel->getCelsiusTemperature($sensor->uuid);
        if ($sensor->protocol['name'] === 'mqtt') return $this->mqttModel->getCelsiusTemperature($sensor->uuid);

        return null;
    }


    public function realtimeTemp (): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->getAllTemps());
    }
}
