<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorReading extends Model
{
    protected $table = 'sensor_readings';
    protected $fillable = ['date', 'temperature', 'ppm', 'water_level', 'recorded_at'];
}
