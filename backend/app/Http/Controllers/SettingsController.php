<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SettingsController extends Controller
{
    public function index ()
    {
        return response()->json(Settings::all());
    }

    public function show ($id)
    {
        return response()->json(Settings::find($id));
    }

    public function update (Request $request)
    {
        $allData = $request->all();
        foreach ($allData as $data) {
            if (!Settings::find($data['id'])) return response()->json(['error' => 'Setting not found'], 403);
            $validator = Validator::make($data, [
                'name' => 'required|string',
                'value' => 'required',
                'description' => 'nullable|string'
            ]);

            if($validator->fails()) return response()->json($validator->errors(), 403);

            $validated = $validator->validated();

            $setting = Settings::find($data['id']);
            $setting->update($validated);
        }

        return response()->json(['Data Saved']);
    }
}
