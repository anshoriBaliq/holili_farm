<?php

namespace App\Http\Controllers;

use App\Models\DailySummary;
use App\Models\SensorReading;
use App\Models\HistoryLog;
use DB;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB as FacadesDB;

class DailySummaryController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $data = DailySummary::all();
            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request) {
        $request->validate([
            'date' => 'required|date',
            'avg_temperature' => 'required|numeric',
            'avg_ppm' => 'required|integer',
            'avg_water_level' => 'required|numeric',
        ]);

        $summary = DailySummary::create($request->all());
        return response()->json($summary, 201);
    }

    public function show($date)
    {
        return response()->json(DailySummary::where('date', $date)->firstOrFail());
    }


    public function calculateDailyAverage(Request $request)
    {
        $tanggal = $request->input('tanggal', Carbon::today()->toDateString());

        $data = SensorReading::whereDate('recorded_at', $tanggal)->get();

        if ($data->isEmpty()) {
            return response()->json(['message' => 'No data found for ' . $tanggal], 404);
        }

        DailySummary::updateOrCreate(
            ['date' => $tanggal],
            [
                'avg_temperature' => $data->avg('temperature'),
                'avg_ppm' => $data->avg('ppm'),
                'avg_water_level' => $data->avg('water_level')
            ]
        );

        return response()->json(['message' => 'Daily average calculated', 'date' => $tanggal]);
    }


    public function calculateTwoHourAverage(Request $request)
    {
        $tanggal = $request->input('tanggal', Carbon::today()->toDateString());

        for ($jam = 0; $jam < 24; $jam += 2) {
            $start = Carbon::createFromTime($jam, 0);
            $end = Carbon::createFromTime($jam + 2, 0);

            $data = SensorReading::whereDate('recorded_at', $tanggal)
                ->whereTime('recorded_at', '>=', $start->format('H:i:s'))
                ->whereTime('recorded_at', '<', $end->format('H:i:s'))
                ->get();

            if ($data->isEmpty()) continue;

            HistoryLog::updateOrCreate(
                [
                    'date' => $tanggal,
                    'time_start' => $start->format('H:i:s'),
                    'time_end' => $end->format('H:i:s')
                ],
                [
                    'avg_temperature' => $data->avg('temperature'),
                    'avg_ppm' => $data->avg('ppm'),
                    'avg_water_level' => $data->avg('water_level')
                ]
            );
        }

        return response()->json(['message' => '2-hourly averages calculated for ' . $tanggal]);
    }


    public function seedDummyData()
    {
        $today = Carbon::today();

        for ($day = 0; $day < 7; $day++) {
            $date = $today->copy()->subDays($day)->toDateString();

            for ($hour = 0; $hour < 24; $hour++) {
                for ($i = 0; $i < 2; $i++) {
                    $minute = $i * 30;
                    $time = sprintf('%02d:%02d:00', $hour, $minute);

                    FacadesDB::table('sensor_readings')->insert([ 
                        'temperature' => rand(250, 300) / 10, // 25.0 - 30.0
                        'ppm' => rand(1000, 1600),
                        'water_level' => rand(3, 10), // cm
                        'created_at' => Carbon::parse("$date $time"),
                        'updated_at' => now(),
                    ]);
                }
            }
        }

    return response()->json(['message' => 'Dummy data inserted to sensor_readings']);
    }

    public function runAutomaticSummaries()
    {
        $dates = SensorReading::selectRaw('DATE(recorded_at) as tanggal')->distinct()->pluck('tanggal');

        foreach ($dates as $tanggal) {
            request()->merge(['tanggal' => $tanggal]);
            $this->calculateDailyAverage(request());
            $this->calculateTwoHourAverage(request());
        }

        return response()->json(['message' => 'All summaries updated.']);
    }

}
