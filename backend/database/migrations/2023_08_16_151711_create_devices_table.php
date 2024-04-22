<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id()->autoIncrement();
            $table->integer('pinout')->unsigned()->nullable();
            $table->string('uuid')->unique()->nullable();
            $table->string('name')->unique();
            $table->integer('device_type_id');
            $table->integer('protocol_id')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('description')->nullable();
            $table->tinyInteger('active')->default(1);
            $table->string('gpio_state')->nullable();
            $table->tinyInteger('heating_source')->default(0);
            $table->tinyInteger('heating_water_sensor')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
