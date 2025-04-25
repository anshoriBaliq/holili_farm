<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('history_logs', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('summary_id')->constrained('daily_summaries');
            // $table->date('date');
            // $table->text('log_description');
            $table->date('date');
            $table->time('time_start');
            $table->time('time_end');
            $table->float('avg_temperature');
            $table->float('avg_ppm');
            $table->float('avg_water_level');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_logs');
    }
};
