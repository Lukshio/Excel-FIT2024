<?php

namespace App\Http\Controllers;

use App\Models\ZoneType;
use Illuminate\Http\Request;

class ZoneTypeController extends Controller
{
    public function index ()
    {
        return response()->json(ZoneType::all());
    }
}
