<?php

namespace App\Console;

use App\Http\Controllers\AutoHeatingController;
use App\Http\Controllers\TemperatureLogController;
use App\Models\Settings;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
        $tempInterval = Settings::where('name', 'temperature_log_interval')->value('value');
        $tempInterval = $tempInterval == 0 ? 1 : $tempInterval;

        $heatingInterval = Settings::where('name', 'thermostat_check_interval')->value('value');
        $heatingInterval = $heatingInterval == 0 ? 1 : $heatingInterval;

        $schedule->call(function (){
            $ah = new AutoHeatingController();
            $ah->autoHeating();
            $ah->waterHeating();
            $ah->waterSwitch();
            $ah->otherZones();
        })->everyMinute();

//        $schedule->call(function () {
//            $tlc = new TemperatureLogController();
//            $tlc->logDS18B20Temps();
//        })->everyMinute()
//            ->when(function () use ($tempInterval) {
//                return (now()->minute % $tempInterval) === 0;
//            });
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
