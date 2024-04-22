<?php

namespace App\Models;

use App\Abstracts\AbstractTemperatureSensorModel;

class MqttSensorModelModel extends AbstractTemperatureSensorModel
{

    public function getCelsiusTemperature($uuid): float
    {
        // TODO: Implement getCelsiusTemperature() method.
    }

    public function getAllTemperatures($sensors): array
    {
        // TODO: Implement getAllTemperatures() method.
    }

    public function listAvailableSensors(): array
    {
        // TODO: Implement listAvailableSensors() method.
    }
}
