<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SensorReading extends Model
{
    protected $table = 'sensor_readings';
    protected $fillable = ['temperature', 'ppm', 'water_level', 'created_at', 'updated_at'];
}
