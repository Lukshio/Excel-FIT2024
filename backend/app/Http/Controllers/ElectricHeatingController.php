<?php

namespace App\Http\Controllers;

use App\Models\HeatingModes;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ElectricHeatingController extends Controller
{
    public function index (): \Illuminate\Http\JsonResponse
    {
        return response()->json((new \App\Models\Settings)->elHeating());
    }
    public function update (Request $request): \Illuminate\Http\JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'id' => 'integer|required'
        ]);

        if($validator->fails())
            return response()->json($validator->errors(), 403);

        $record = Settings::where('name', 'el_heating')->first();
        $validated = $validator->validated();
        $record->update(['value' => $validated['id']]);

        return $this->index();
    }
}
