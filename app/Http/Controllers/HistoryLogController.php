<?php

namespace App\Http\Controllers;

use App\Models\HistoryLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HistoryLogController extends Controller
{
    public function index(): JsonResponse
    {
        try {
            $data = HistoryLog::all();
            return response()->json($data, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'summary_id' => 'required|exists:daily_summaries,id',
            'date' => 'required|date',
            'log_description' => 'required|string',
        ]);

        $log = HistoryLog::create($request->all());
        return response()->json($log, 201);
    }

    public function show($date)
    {
        return response()->json(HistoryLog::where('date', $date)->get());
    }
}
