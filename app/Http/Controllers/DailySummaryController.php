<?php

namespace App\Http\Controllers;

use App\Models\DailySummary;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;


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
}
