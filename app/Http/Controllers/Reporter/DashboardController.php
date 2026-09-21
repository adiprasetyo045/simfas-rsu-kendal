<?php

namespace App\Http\Controllers\Reporter;

use App\Http\Controllers\Controller;
use App\Enums\ReportStatus;
use App\Models\Report;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        $stats = [
            'total' => $user->reports()->count(),
            'active' => $user->reports()->active()->count(),
            'completed' => $user->reports()->where('status', ReportStatus::SELESAI->value)->count(),
            'pending' => $user->reports()->where('status', ReportStatus::MENUNGGU_VERIFIKASI->value)->count(),
        ];

        $recentReports = $user->reports()
            ->with(['room', 'category', 'assignment'])
            ->latest('reported_at')
            ->take(5)
            ->get();

        return view('reporter.dashboard', compact('stats', 'recentReports'));
    }
}
