<?php

namespace App\Http\Controllers;

use App\Models\Zone;
use App\Models\ZoneType;
use App\Models\TemperatureLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class ZoneController extends Controller
{
    public function index ()
    {
        $zones = Zone::with('zoneType')->with('relay:id,name')->with('sensor:uuid,name');
        if(Auth::user()->role != 'admin')
            $zones = $zones->where('user_id', Auth::id());

        $zones = $zones->get();

        foreach($zones as $zone)
        {
            if($zone->kpi_treshold != 0 && $zone->kpi_treshold != NULL)
                $zone['kpi'] = $zone->temperature / $zone->kpi_treshold * 100;
        }

        return response()->json($zones);
    }
    public function store (Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'temperature' => 'required|numeric',
            'zone_type_id' => 'required|numeric',
            'main_sensor_uuid' => 'nullable|string',
            'relay_device_id' => 'nullable|numeric',
            'description' => 'nullable|string',
            'user_id' => 'nullable|numeric'
        ]);

        if ($validator->fails()) return response()->json($validator->errors(), 403);

        $validated = $validator->validated();

        $zone = Zone::create($validated);

        return response()->json($zone);
    }

    public function show ($id)
    {

        $zone = Zone::with('sensor')
            ->with('relay:id,name')
            ->with('sensor:uuid,name')
            ->with('zoneType')
            ->find($id);
        if(Auth::user()->role != 'admin' && $zone->user_id != Auth::id())
            return response()->json([], 404);
        return response()->json($zone);
    }

    public function update (Request $request)
    {
        if (!Zone::find($request->id)) return response()->json(['error' => 'Zone not found'], 404);

        $zone = Zone::find($request->id);
        if(Auth::user()->role == 'admin'){
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'temperature' => 'required|numeric',
                'zone_type_id' => 'required|numeric',
                'main_sensor_uuid' => 'nullable|string',
                'relay_device_id' => 'nullable|numeric',
                'auto_heating' => 'required|numeric',
                'description' => 'nullable|string',
                'kpi_treshold' => 'nullable|numeric',
                'user_id' => 'nullable|numeric',
                'use_program' => 'required|numeric'
            ]);
        } else {
            if($zone->user_id != Auth::id()) return response()->json("Permission denied", 403);
            $validator = Validator::make($request->all(), [
                'temperature' => 'required|numeric',
                'auto_heating' => 'required|numeric'
            ]);
        }

        if ($validator->fails()) {
            return response()->json($validator->errors(), 403);
        }
        $data = $validator->validated();

        $zone->update($data);

        return $this->show($zone->id);
    }

    public function destroy ($id)
    {
        $zone = Zone::find($id);
        if ($zone->delete()) return response()->json('ok',200);
        else return response()->json(['error' => 'Unable to delete'], 500);
    }

    public function kpi()
    {
        $effectivity = 0;

        if(Auth::user()->role == 'admin')
            $zones = Zone::where('kpi_treshold', '!=', NULL)->get();
        else
            $zones = Zone::where('kpi_treshold', '!=', NULL)->where('user_id', Auth::id())->get();



        foreach($zones as $zone)
        {
            $effectivity +=  $zone->temperature / $zone->kpi_treshold ;

        }

        return  $effectivity / $zones->count() * 100;

    }
}
