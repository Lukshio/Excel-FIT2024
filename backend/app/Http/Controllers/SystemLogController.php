<?php

namespace App\Http\Controllers;

use App\Models\SystemLog;
use Illuminate\Http\Request;

class SystemLogController extends Controller
{

    public static function newLog ($source, $text, $type)
    {
        $data = [
            'name' => $source,
            'text' => $text,
            'type' => $type
        ];
        SystemLog::create($data);
    }
    public function index ()
    {
        return response()->json(SystemLog::all());
    }

    public function showType ($type){
        return response()->json(SystemLog::where('type', $type)->get());
    }
}
