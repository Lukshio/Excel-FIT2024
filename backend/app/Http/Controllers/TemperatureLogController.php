<?php

namespace App\Http\Controllers;

use App\Models\TemperatureLog;

class TemperatureLogController extends Controller
{
    public function logDS18B20Temps ()
    {
        $loadedTemps = TemperatureController::getDS18B20Temps();

        $allData = [];
        foreach ($loadedTemps as $sensor => $value){
            $data = [
                'sensor_uuid' => $sensor,
                'value' => $value['celsius'],
                'unit' => 'celsius'
            ];
            TemperatureLog::create($data);
            $allData[] = $data;
        }

        return response()->json($allData);
    }

    public function index (): \Illuminate\Http\JsonResponse
    {
        $logs = TemperatureLog::with(['sensor' => function ($query) {
            $query->select('uuid','name')->get();
        }])->get();

        return response()->json($logs);
    }

    public function show ($uuid)
    {
        return TemperatureLog::where('sensor_uuid', $uuid)->latest('created_at')->first();
    }
}
