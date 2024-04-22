<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\GPIO;
use http\Exception\RuntimeException;
use Illuminate\Http\Request;

class GPIOController extends Controller
{
    private $gpio;
    public function __construct()
    {
        $this->gpio = new GPIO();
    }

    public function status (Request $request): \Illuminate\Http\JsonResponse
    {
        $arr = [];
        foreach ($request->pins as $pin){
            $arr[$pin] = $this->gpio->status($pin);
        }
        return response()->json($arr);
    }

    /**
     * @param $deviceId
     * @return void
     */
    public function relayOn ($deviceId): void
    {
        $device = Device::with('deviceType')->find($deviceId);
        if ($device->deviceType['name'] != 'relay') {
            throw new RuntimeException('Invalid device passed to relayOn');
        }
        $this->gpio->setLow($device['pinout']);
    }


    /**
     * @param $deviceId
     * @return void
     */
    public function relayOff ($deviceId): void
    {
        $device = Device::with('deviceType')->find($deviceId);
        if ($device->deviceType['name'] != 'relay') {
            throw new RuntimeException('Invalid device passed to relayOn');
        }
        $this->gpio->setHigh($device['pinout']);
    }

    public function switchRelay (Request $request)
    {
        $relay = Device::find($request->relay_id);
        ($request->new_state == 0) ?
            $this->gpio->setHigh($relay['pinout']) :
            $this->gpio->setLow($relay['pinout']);
    }
}
