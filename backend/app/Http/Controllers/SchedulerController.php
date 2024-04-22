<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Scheduler;

class SchedulerController extends Controller
{
    public function show($id)
    {
        $schedulerLogs = Scheduler::where('zone_id', $id)->get(['day','start', 'end'])->groupBy('day');
        $result = [];
        foreach($schedulerLogs as $key => $value)
        {
            foreach($value as $time){
                $result[$key][]=[$time->start, $time->end];
            }
        }

        return response()->json($result);
    }

    public function update(Request $request)
    {
        Scheduler::where('zone_id', $request->zone_id)->delete();


        foreach($request->data as $key => $value)
        {
            if(empty($value)) continue;
            foreach($value as $time)
            {
                $schedulerLog = new Scheduler();

                $schedulerLog->start = $time[0];
                $schedulerLog->end = $time[1];
                $schedulerLog->day = $key;
                $schedulerLog->zone_id = $request->zone_id;
                $schedulerLog->save();
            }

        }

        return $this->show($request->zone_id);
    }
}
