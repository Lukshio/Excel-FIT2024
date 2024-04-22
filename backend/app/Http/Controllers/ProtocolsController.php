<?php

namespace App\Http\Controllers;

use App\Models\Protocols;
use Illuminate\Http\Request;

class ProtocolsController extends Controller
{
    public function index ()
    {
        return response()->json(Protocols::all());
    }
}
