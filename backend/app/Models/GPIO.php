<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use PiPHP\GPIO\GPIO as PI_GPIO;
use PiPHP\GPIO\Pin\PinInterface;

class GPIO extends Model
{
    use HasFactory;

    protected PI_GPIO $gpio;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->gpio = new PI_GPIO();
    }

    public function status ($pin)
    {
        return $this->gpio->getOutputPin($pin)->getValue();
    }

    public function setHigh ($pin)
    {
        $this->gpio->getOutputPin($pin)->setValue(PinInterface::VALUE_HIGH);
        return true;
    }

    public function setLow ($pin)
    {
        $this->gpio->getOutputPin($pin)->setValue(PinInterface::VALUE_LOW);
        return true;
    }
}
