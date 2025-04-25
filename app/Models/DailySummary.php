<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailySummary extends Model
{
    protected $table = 'daily_summary';
    protected $fillable = ['date', 'avg_temperature', 'avg_ppm', 'avg_water_level'];
}
