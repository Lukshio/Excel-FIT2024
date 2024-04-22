<?php

namespace App\Http\Controllers;

use App\Models\DeviceType;
use Dotenv\Validator;
use Illuminate\Http\Request;

class DeviceTypeController extends Controller
{
    public function index ()
    {
        return response()->json(DeviceType::all());
    }

    public function show (Request $request)
    {
        return response()->json(DeviceType::find($request->id));
    }
}
