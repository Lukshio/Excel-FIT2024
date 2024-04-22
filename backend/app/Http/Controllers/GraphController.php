<?php

namespace App\Http\Controllers;

use App\Models\TemperatureLog;
use App\Models\Zone;
use Illuminate\Http\Request;

class GraphController extends Controller
{
    public function show(Request $request)
    {

        $isZone = $request->is_zone;
        $graphData = TemperatureLog::where('created_at', '>=', $request->from)->where('created_at', '<=', $request->to);
        if($isZone)
            $graphData->where('sensor_uuid', Zone::find($request->id)->main_sensor_uuid)->paginate(30);
        else
            $graphData->where('sensor_uuid', $request->id)->paginate(30);


        return $graphData->orderBy('created_at')->get(["value", "unit","created_at"]);

    }
}
