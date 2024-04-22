<?php

namespace App\Http\Controllers;

use App\Models\DeviceType;
use App\Models\HeatingModes;
use App\Models\Settings;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class HeatingModesController extends Controller
{
    public function index (): \Illuminate\Http\JsonResponse
    {
        return response()->json(HeatingModes::all());
    }

    public function show (): \Illuminate\Http\JsonResponse
    {
        return response()->json(HeatingModes::find(Settings::heatingMode()));
    }

    public function update (Request $request): \Illuminate\Http\JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'id' => 'integer|required'
        ]);

        if($validator->fails())
            return response()->json($validator->errors(), 403);

        $record = Settings::where('name', 'heating_mode')->first();
        $validated = $validator->validated();
        $record->update(['value' => $validated['id']]);

        return $this->show();
    }
}
