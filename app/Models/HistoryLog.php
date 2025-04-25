<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HistoryLog extends Model
{
    protected $table = 'history_logs';
    protected $fillable = ['date', 'time_start', 'time_end', 'avg_temperature', 'avg_ppm', 'avg_water_level'];
}
