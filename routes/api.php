<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SensorReadingController;
use App\Http\Controllers\DailySummaryController;
use App\Http\Controllers\HistoryLogController;


    // Route::get('/sensor-readings', [SensorReadingController::class, 'index']);
    // Route::get('/daily-summaries', [DailySummaryController::class, 'index']);
    // Route::get('/history-logs', [HistoryLogController::class, 'index']);

    Route::get('/sensor-readings', [SensorReadingController::class, 'index']);
    Route::post('/sensor-readings', [SensorReadingController::class, 'store']);
    Route::get('/sensor-readings/{id}', [SensorReadingController::class, 'show']);

    Route::get('/daily-summaries', [DailySummaryController::class, 'index']);
    Route::post('/daily-summaries', [DailySummaryController::class, 'store']);
    Route::get('/daily-summaries/{date}', [DailySummaryController::class, 'show']);

    Route::get('/history-logs', [HistoryLogController::class, 'index']);
    Route::post('/history-logs', [HistoryLogController::class, 'store']);
    Route::get('/history-logs/{date}', [HistoryLogController::class, 'show']);

    Route::get('/test-api', function () {
        return response()->json(['message' => 'API works!']);
    }); 
