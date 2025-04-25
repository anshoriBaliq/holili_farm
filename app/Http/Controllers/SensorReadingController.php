<?php

namespace App\Http\Controllers;

use App\Models\SensorReading;
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
            'recorded_at' => 'required|date',
        ]);

        $reading = SensorReading::create($request->all());
        return response()->json($reading, 201);
    }

    public function show($id)
    {
        return response()->json(SensorReading::findOrFail($id));
    }
}
