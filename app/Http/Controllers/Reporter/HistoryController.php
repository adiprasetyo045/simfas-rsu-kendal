<?php

namespace App\Http\Controllers\Reporter;

use App\Http\Controllers\Controller;
use App\Models\Report;

class HistoryController extends Controller
{
    public function index()
    {
        $reports = auth()->user()->reports()
            ->with(['room', 'category', 'assignment.technician'])
            ->latest('reported_at')
            ->paginate(20);

        return view('reporter.history.index', compact('reports'));
    }

    public function show(Report $report)
    {
        if ($report->user_id !== auth()->id()) {
            abort(403);
        }

        $report->load([
            'room', 'category', 'facility', 'images',
            'assignment.technician', 'assignment.progressHistories.images',
            'statusHistories.changedBy',
        ]);

        return view('reporter.reports.show', compact('report'));
    }
}
