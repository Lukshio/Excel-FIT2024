<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\AutoHeatingController;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\ElectricHeatingController;
use App\Http\Controllers\GPIOController;
use App\Http\Controllers\GraphController;
use App\Http\Controllers\HeatingModesController;
use App\Http\Controllers\SchedulerController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\SystemLogController;
use App\Http\Controllers\TemperatureLogController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ZoneController;
use App\Http\Controllers\DeviceTypeController;
use App\Http\Controllers\ProtocolsController;
use App\Http\Controllers\TemperatureController;
use App\Http\Controllers\ZoneTypeController;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


//Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//    return $request->user();
//});



// AUTH
Route::post('login', [AuthController::class, 'login'])->name('login');

// MIDDLEWARE
Route::middleware('auth:sanctum')->group(function () {
    Route::post('logout', [AuthController::class, 'logout']);

    //USERS
    Route::controller(UserController::class)->prefix('user')->group(function () {
        Route::get('/', 'usersIndex')->middleware([AdminMiddleware::class]);
        Route::post('/', 'createUser')->middleware([AdminMiddleware::class]);
        Route::delete('/{id}', 'destroy')->middleware([AdminMiddleware::class]);
        Route::put('/{id}', 'update')->middleware([AdminMiddleware::class]);
    });

    // SCHEDULER
    Route::controller(SchedulerController::class)->prefix('scheduler')->group(function (){
       Route::get('/{id}', 'show');
       Route::put('/', 'update');
    });

    // GRAPHS
    Route::controller(GraphController::class)->prefix('graphs')->group(function () {
       Route::post('/', 'show');
    });

    // PROTOCOLS
    Route::controller(ProtocolsController::class)->prefix('protocols')->group(function () {
        Route::get('/', 'index');
    });

    // ZONE TYPE
    Route::controller(ZoneTypeController::class)->prefix('zone-types')->group(function () {
        Route::get('/', 'index');
    });

    // DEVICE TYPE
    Route::controller(DeviceTypeController::class)->prefix('device-types')->group(function () {
        Route::get('/', 'index');
    });

    // HEATING MODES
    Route::controller(HeatingModesController::class)->prefix('heating-mode')->group(function () {
        Route::get('/all', 'index');
        Route::get('/', 'show');
        Route::put('/', 'update');
    });

    // HEATING MODES
    Route::controller(ElectricHeatingController::class)->prefix('el-heating')->group(function () {
        Route::get('/', 'index');
        Route::put('/', 'update');
    });


    // DEVICES
    Route::controller(DeviceController::class)->prefix('device')->group(function (){
        Route::get('/relays', 'getActiveRelays');
        Route::get('/', 'index');
        Route::post('/', 'store');
        Route::put('/', 'update');
        Route::get('/{id}', 'show');
        Route::delete('/{id}', 'destroy');
        Route::get('/find/DS18B20', 'findDS18B20Sensors');
    });

    // ZONES
    Route::controller(ZoneController::class)->prefix('zone')->group(function () {
        Route::post('/', 'store');
        Route::get('/', 'index');
        Route::put('/','update');
        Route::get('/{id}', 'show');
        Route::delete('/{id}', 'destroy');
    });

    // TEMPERATURES
    Route::controller(TemperatureController::class)->prefix('temperature')->group(function (){
       Route::get('/rt','realtimeTemp');
    });

    // GPIO
    Route::controller(GPIOController::class)->prefix('gpio')->group(function (){
        Route::post('/relay/switch', 'switchRelay');
        Route::post('/status','status');
    });

    // SETTINGS
    Route::controller(SettingsController::class)->prefix('settings')->group(function () {
        Route::get('/', 'index');
        Route::put('/', 'update');
    });

    // SYSTEM LOGS
    Route::controller(SystemLogController::class)->prefix('system-log')->group(function () {
        Route::get('/','index');
    });

    // TEMPERATURE LOGS
    Route::controller(TemperatureLogController::class)->prefix('temperature-log')->group(function () {
        Route::get('/', 'index');
        Route::post('/test-templog-function', 'logDS18B20Temps');
    });

    // AUTO HEATING TESTS
    Route::controller(AutoHeatingController::class)->prefix('auto-heating-test')->group(function () {
        Route::post('/autoheating', 'autoHeating');
        Route::post('/waterswich', 'waterSwitch');
        Route::post('/waterheating', 'waterHeating');
        Route::post('/other', 'otherZones');
    });


});
