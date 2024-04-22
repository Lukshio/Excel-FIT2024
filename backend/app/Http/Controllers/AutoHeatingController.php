<?php

namespace App\Http\Controllers;

use App\Models\DallasSensorModelModel;
use App\Models\Device;
use App\Models\HeatingModes;
use App\Models\MqttSensorModelModel;
use App\Models\Scheduler;
use App\Models\Settings;
use App\Models\Zone;

class AutoHeatingController extends Controller
{
    public $heatingMode;
    public array|\Illuminate\Database\Eloquent\Collection $heatingZones;
    public array|\Illuminate\Database\Eloquent\Collection $otherZones;
    public array|\Illuminate\Database\Eloquent\Collection $waterSwitchZones;

    public array|\Illuminate\Database\Eloquent\Collection $boilerZone;
    public GpioController $gpioController;
    public TemperatureController $temperatureController;
    public $offset;
    public int $timeHour;
    public int $day;
    public function __construct()
    {
        $this->offset = Settings::where('name', 'offset')->value('value');
        $this->heatingMode = HeatingModes::find(Settings::heatingMode())->name;

        $this->heatingZones = Zone::heatingZones();
        $this->waterSwitchZones = Zone::waterSwitchZones();
        $this->otherZones = Zone::otherZones();
        $this->boilerZone = Zone::boilerZone();

        $this->gpioController = new GPIOController();
        $this->temperatureController = new TemperatureController();

        $this->timeHour = date("H");
        $this->day = date("N") - 1;
    }
    /**
     * @return void
     * @brief Function for cron schedule auto heating control
     * @throws \Exception
     */
    public function autoHeating (): void
    {
        $heatingStateOn = [];
        $heatingStateOff = [];

        $heatingSource = Device::where('heating_source', '1')->first();
        if (!$heatingSource) {
            SystemLogController::newLog('AutoHeating', 'NO HEATING SOURCE, ABORTING', 'ERROR');
            throw new \Exception('NO HEATING SOURCE, ABORTING');
        }

        // overheat protection
        $overheatProtection = Device::where('overheat_protection', 1)->first();
        if ($overheatProtection != null) {
            $lastTemp = $this->temperatureController->getSingleTemp($overheatProtection->uuid);
            $overheatProtectionTemperatureLimit = Settings::where('name','protection_temperature')->first()->value;

            if ($lastTemp > $overheatProtectionTemperatureLimit) {
                $this->gpioController->relayOff($heatingSource['id']);
                foreach ($this->heatingZones as $zone) {
                    $this->gpioController->relayOn($zone['relay_device_id']);
                }

                SystemLogController::newLog('AutoHeating', 'OVERHEAT PROTECTION ACTIVATED', 'ALERT');
                return;
            }
        }

        if ($this->heatingMode == 'turned_off') {
            $this->gpioController->relayOff($heatingSource['id']);
            return;
        }


        // main loop over all heating_zones
        foreach ($this->heatingZones as $zone) {
            // skip turned off zones
            if ($zone->auto_heating == 0) continue;

            if (!$this->scheduledHeating($zone)) {
                $heatingStateOff[] = $zone['relay_device_id'];
                continue;
            }

            if ($zone['main_sensor_uuid'] == NULL || $zone['relay_device_id'] == NULL) {
                SystemLogController::newLog('AutoHeating', 'NO DEVICE FOUND, SKIPPING>>>' . $zone->name, 'WARNING');
                continue;
            }

            $lastTemp = $this->temperatureController->getSingleTemp($zone->main_sensor_uuid);

            if ($lastTemp == null || ($lastTemp > (($zone['temperature'] + $this->offset)))) {

                $heatingStateOff[] = $zone;
                $zone->update(['current_heating' => 'off']);

                SystemLogController::newLog('AutoHeating', 'Relay turned off, ZONE: ' .$zone['name'], 'INFO');
            }
            else if ($lastTemp < (($zone['temperature'] - $this->offset))){

                $heatingStateOn[] = $zone;

                SystemLogController::newLog('AutoHeating', 'Relay turned on, ZONE: ' .$zone['name'], 'INFO');
            }
        }


        if ($this->heatingMode === 'fireplace')
            $this->fireplaceHeating($heatingSource);

        else if ($this->heatingMode == 'combined') {
            $waterHeatSensor = Device::where('heating_water_sensor', '1')->first();
            if ($waterHeatSensor == null) throw new \Exception('WATER HEAT SENSOR MUST BE SET');

            $waterHeatSensorTemp = $this->temperatureController->getSingleTemp($waterHeatSensor->uuid);
            $switchTemp = Settings::combined_min_temp();

            //TODO pokud se topi bojlerem skipni toto po dobu X z nastaveni -- prevence proti krátkým cyklům kotle
            if ($waterHeatSensorTemp > $switchTemp)
                $this->fireplaceHeating($heatingSource);

            else
                $this->gasHeating($heatingStateOn, $heatingStateOff, $heatingSource);

        } else if ($this->heatingMode == 'gas_boiler') {
            $this->gasHeating($heatingStateOn, $heatingStateOff, $heatingSource);
        } else throw new \Exception('INVALID HEATING MODE');
    }

    public function gasHeating ($hsOn, $hsOff, $hs): void
    {
        if (!empty($hsOn)) {
            // open valves
            foreach ($hsOn as $zone) {
                $zone->update(['current_heating' => 'on']);
                $this->gpioController->relayOn($zone->relay_device_id);
            }

            // close valves
            foreach ($hsOff as $zone) {
                $zone->update(['current_heating' => 'on']);
                $this->gpioController->relayOff($zone->relay_device_id);
            }

            //Turn on heating source
            $this->gpioController->relayOn($hs['id']);
        } else $this->gpioController->relayOff($hs['id']);
    }

    public function fireplaceHeating($hs): void
    {
        //Turn off heating source
        $this->gpioController->relayOff($hs['id']);

        // open valves
        $openRelays = Device::fireplaceOpen();
        foreach ($openRelays as $relay) {
            $fZone = Zone::where('main_sensor_uuid', $relay)->first();
            if ($fZone != null) $fZone->update(['current_heating' => 'fireplace']);
            $this->gpioController->relayOn($relay['id']);
        }
    }

    public function scheduledHeating ($zone): bool
    {
        $heatOn = false;
        if ($zone->use_program == 1) {
            $scheduler = Scheduler::where('zone_id', $zone->id)->get()->toArray();
            foreach ($scheduler as $schedule) {
                if ($schedule['day'] != $this->day) continue;
                if ($schedule['start'] < $this->timeHour && $schedule['end'] > $this->timeHour)
                    $heatOn = true;
            }
            if (!$heatOn) return false;
        }
        return true;
    }
    /**
     * @throws \Exception
     */
    public function waterHeating (): void
    {
        // water tank electric heating
        $waterHeatOn = Settings::elHeating();
        foreach ($this->boilerZone as $zone) {
            if ($zone['auto_heating'] == 0) continue;

            if (!$this->scheduledHeating($zone)) {
                $this->gpioController->relayOff($zone['relay_device_id']);
                continue;
            }

            $lastTemp = $this->temperatureController->getSingleTemp($zone['main_sensor_uuid']);

            if (
                (($this->heatingMode == 'gas_boiler' || $this->heatingMode == 'turned_off') && $waterHeatOn) &&
                ($lastTemp < ($zone['temperature'] + $this->offset))
            )
                $this->gpioController->relayOn($zone['relay_device_id']);
            else
                $this->gpioController->relayOff($zone['relay_device_id']);
        }
    }


    /**
     * @throws \Exception
     */
    public function waterSwitch (): void {
        // water switch between sources
        foreach ($this->waterSwitchZones as $zone) {
            if ($zone['auto_heating'] == 0) continue;

            if (!$this->scheduledHeating($zone)) {
                $this->gpioController->relayOff($zone['relay_device_id']);
                continue;
            }

            $lastTemp = $this->temperatureController->getSingleTemp($zone['main_sensor_uuid']);

            if ($lastTemp < ($zone['temperature'] + $this->offset))
                $this->gpioController->relayOn($zone['relay_device_id']);
            else
                $this->gpioController->relayOff($zone['relay_device_id']);
        }
    }

    /**
     * @throws \Exception
     */
    public function otherZones (): void {
        // water switch between sources
        foreach ($this->otherZones as $zone) {
            if ($zone['auto_heating'] == 0) continue;

            if (!$this->scheduledHeating($zone)) {
                $this->gpioController->relayOff($zone['relay_device_id']);
                $zone->update(['current_heating' => 'off']);
                continue;
            }

            $lastTemp = $this->temperatureController->getSingleTemp($zone['main_sensor_uuid']);

            if ($lastTemp < ($zone['temperature'] + $this->offset)) {
                $this->gpioController->relayOn($zone['relay_device_id']);
                $zone->update(['current_heating' => 'on']);
            }
            else
                $this->gpioController->relayOff($zone['relay_device_id']);
        }
    }

}
