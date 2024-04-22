<?php

namespace App\Http\Controllers;

use App\Models\DallasSensorModelModel;
use App\Models\Device;
use App\Models\DeviceType;
use App\Models\MqttSensorModelModel;
use App\Models\Protocols;
use azolee\DS18B20;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function index ()
    {
        return response()->json(Device::with('deviceType')->with('protocol')->get());
    }

    public function store (Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'uuid' => 'nullable|string',
            'pinout' => 'nullable|numeric',
            'device_type_id' => 'required|numeric',
            'protocol_id' => 'required|numeric',
            'description' => 'nullable|string',
            'heating_water_sensor' => 'required|numeric',
            'heating_source' => 'required|numeric',
            'fireplace_open' => 'required|numeric',
            'overheat_protection' => 'required|numeric'
        ]);

        $type = DeviceType::find($request->device_type_id);
        $protocol = Protocols::find($request->protocol_id);

        // Invalid combinations handling
        if($validator->fails())
            return response()->json($validator->errors(), 403);

        else if ($type->name == 'relay' && $protocol->name == 'gpio' && $request->pinout == null)
            return response()->json(['error' => 'Pinout is null'], 403);

        else if ($request->heating_source == 1 && $type->name != 'relay')
            return response()->json(['error' => 'Heating source must be relay'], 403);

        else if ($request->overheat_protection == 1 && $type->name != 'temperature_sensor')
            return response()->json(['error' => 'Overheat protection must be sensor'], 403);

        else if ($request->fireplace_open == 1 && $type->name != 'relay')
            return response()->json(['error' => 'fireplace open must be relay'], 403);


        $validated = $validator->validated();

        $device = Device::create($validated);

        return response()->json($device);
    }

    public function show ($id): \Illuminate\Http\JsonResponse
    {
        return response()->json(Device::find($id));
    }

    public function update (Request $request): \Illuminate\Http\JsonResponse
    {
        $old = Device::find($request->id);
        if (!$old) return response()->json(['error' => 'Device not found'], 404);

        $device = Device::find($request->id);

        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'uuid' => 'nullable|string',
            'pinout' => 'nullable|numeric',
            'device_type_id' => 'required|numeric',
            'protocol_id' => 'required|numeric',
            'description' => 'nullable|string',
            'heating_water_sensor' => 'required|numeric',
            'heating_source' => 'required|numeric',
            'fireplace_open' => 'required|numeric',
            'overheat_protection' => 'required|numeric'
        ]);

        // If other heating source exists, return error
        if (
            $request->heating_source == 1 &&
            Device::where('heating_source', 1)->exists() &&
            $old['heating_source'] != 1
        ) {
            return response()->json(['error' => 'Another heating source already exists'], 403);
        }

        $type = DeviceType::find($request->device_type_id);
        $protocol = Protocols::find($request->protocol_id);

        // Invalid combinations handling
        if($validator->fails())
            return response()->json($validator->errors(), 403);

        else if ($type->name == 'relay' && $protocol->name == 'gpio' && $request->pinout == null)
            return response()->json(['error' => 'Pinout is null'], 403);

        else if ($request->heating_source == 1 && $type->name != 'relay')
            return response()->json(['error' => 'Heating source must be relay'], 403);

        else if ($request->overheat_protection == 1 && $type->name != 'temperature_sensor')
            return response()->json(['error' => 'Overheat protection must be sensor'], 403);

        else if ($request->fireplace_open == 1 && $type->name != 'relay')
            return response()->json(['error' => 'fireplace open must be relay'], 403);


        $validated = $validator->validated();

        $device->update($validated);

        return $this->show($device->id);
    }

    public function destroy ($id): \Illuminate\Http\JsonResponse
    {
        $device = Device::find($id);

        if ($device->zoneSensor != null ||
            $device->zoneRelay != null ||
            $device->heatingSource != 0 ||
            $device->heatingWaterSensor != 0
        ) {
            return response()->json(['error' => 'Device is in use'], 403);
        } else {
            $device->delete();
            return response()->json('');
        }
    }

    public function getActiveRelays (): \Illuminate\Http\JsonResponse
    {;
        $relays = Device::whereHas('deviceType', function ($query){
            $query->where('name', 'relay');
        })->with(['zoneRelay' => function ($query) {
            $query->select('relay_device_id', 'auto_heating');
        }])->get();

        return response()->json($relays);
    }

    public function findDS18B20Sensors (): \Illuminate\Http\JsonResponse
    {
        $ds18b20Sensors = (new DallasSensorModelModel)->listAvailableSensors();
        if (empty($ds18b20Sensors)) return response()->json(['error' => 'No devices found'], 404);

        foreach ($ds18b20Sensors as $key=>$sensor){
            if (Device::where('uuid', $key)->first()) continue;
            $data = [
                'name' => $key,
                'uuid' => $key,
                'device_type_id' => 1, // temp sensor
                'protocol_id' => 1 //1-wire
            ];
            Device::create($data);
        }

        return response()->json($this->index());
    }
}
