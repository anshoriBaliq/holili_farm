<?php

namespace App\Http\Controllers;

use App\Models\SensorReading;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SensorReadingController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $data = SensorReading::latest('id')->first();

            if (!$data) {
                return response()->json(['message' => 'No data found'], 404);
            }

            $data = SensorReading::all();
            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'temperature' => 'required|numeric',
            'ppm' => 'required|integer',
            'water_level' => 'required|numeric',
        ]);

        $reading = SensorReading::create($request->all());
        return response()->json($reading, 200);
    }

    public function show($id)
    {
        return response()->json(SensorReading::findOrFail($id));
    }

    public function weeklyDate()
    {
        $starDate = Carbon::now()->subDays(6)->startOfDay(); // 6 hari lalu mulai hari ini
        $endDate = Carbon::now()->endOfDay(); // hari ini

        $data = SensorReading::whereBetween('created_at', [$starDate, $endDate])
            ->orderBy('created_at')
            ->get()
            ->groupBy(function($item){
                return Carbon::parse($item->recorded_at)->format('Y-m-d');
            })
            ->map(function ($group) {
                return $group->sortByDesc('created_at')->values();
            })
            ->sortKeysDesc(); // urutkan tanggal dari terbaru

        return response()->json([
            'message' => 'Data Sensor 7 Hari Terakhir',
            'date' => $data
        ]);
    }


    public function byDate(Request $request)
        {
            $request->validate([
                'date' => 'required|date_format:Y-m-d',
            ]);

            $date = Carbon::parse($request->date);

            $data = SensorReading::whereDate('created_at', $date)
                ->orderByDesc('created_at')
                ->get();

            return response()->json([
                'message' => 'Data Sensor pada Tanggal ' . $date->format('Y-m-d'),
                'data' => $data
            ]);
        }


    public function today()
    {

        $date = Carbon::today();

        $data = SensorReading::whereDate('created_at', $date)
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'message' => 'Data Sensor pada Tanggal ' . $date->format('Y-m-d'),
            'data' => $data
        ]);
    } 
    
    public function showByDate(Request $request)
    {
        $request->validate([
            'date' => 'nullable|date_format:Y-m-d',
        ]);

        // Jika tidak ada input 'date', pakai tanggal hari ini
        $date = $request->date ? Carbon::parse($request->date) : Carbon::today();

        $data = SensorReading::whereDate('created_at', $date)
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'message' => 'Data Sensor pada Tanggal ' . $date->format('Y-m-d'),
            'data' => $data
        ]);
    }

}
