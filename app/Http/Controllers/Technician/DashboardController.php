<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Enums\ReportStatus;
use App\Models\Assignment;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total' => $user->assignments()->count(),
            'active' => $user->assignments()->whereIn('status', ['ditugaskan', 'dikerjakan'])->count(),
            'completed' => $user->assignments()->where('status', 'selesai')->count(),
            'overdue' => $user->assignments()
                ->whereNotNull('estimated_completion')
                ->where('estimated_completion', '<', now())
                ->whereNull('actual_completion')
                ->count(),
        ];

        $recentTasks = $user->assignments()
            ->with(['report.room', 'report.category'])
            ->latest('assigned_at')
            ->take(5)
            ->get();

        return view('technician.dashboard', compact('stats', 'recentTasks'));
    }

    public function toggleShift()
    {
        $user = auth()->user();
        $user->update(['is_on_shift' => !$user->is_on_shift]);

        $status = $user->is_on_shift ? 'dimulai' : 'diakhiri';
        return back()->with('success', "Shift berhasil {$status}.");
    }
}
