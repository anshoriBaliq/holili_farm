<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorReading extends BaseModel
{
    protected $table = 'sensor_readings';
    protected $fillable = ['temperature', 'ppm', 'water_level'];
}
