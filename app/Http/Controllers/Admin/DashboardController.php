<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\ReportStatus;
use App\Models\Report;
use App\Models\Category;
use App\Models\Room;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today = now()->toDateString();

        // Stat cards
        $stats = [
            'today' => Report::whereDate('reported_at', $today)->count(),
            'processing' => Report::whereIn('status', [
                ReportStatus::DIPROSES->value,
                ReportStatus::DALAM_PERBAIKAN->value,
                ReportStatus::MENUNGGU_SPAREPART->value,
            ])->count(),
            'completed' => Report::where('status', ReportStatus::SELESAI->value)->count(),
            'overdue' => Report::whereHas('assignment', function ($q) {
                $q->whereNotNull('estimated_completion')
                  ->where('estimated_completion', '<', now())
                  ->whereNull('actual_completion');
            })->whereNotIn('status', [
                ReportStatus::SELESAI->value,
                ReportStatus::DITOLAK->value,
            ])->count(),
            'pending' => Report::where('status', ReportStatus::MENUNGGU_VERIFIKASI->value)->count(),
        ];

        // Chart: Reports by category
        $byCategory = Report::select('category_id', DB::raw('count(*) as total'))
            ->groupBy('category_id')
            ->with('category')
            ->get()
            ->map(fn($r) => [
                'label' => $r->category->name ?? 'N/A',
                'total' => $r->total,
            ]);

        // Chart: Reports by month (last 6 months)
        $byMonth = Report::select(
                DB::raw("DATE_FORMAT(reported_at, '%Y-%m') as month"),
                DB::raw('count(*) as total')
            )
            ->where('reported_at', '>=', now()->subMonths(6))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // Recent reports
        $recentReports = Report::with(['user', 'room', 'category'])
            ->latest('reported_at')
            ->take(10)
            ->get();

        // Stats by room
        $byRoom = Report::select('room_id', DB::raw('count(*) as total'))
            ->groupBy('room_id')
            ->with('room')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        return view('admin.dashboard', compact(
            'stats', 'byCategory', 'byMonth', 'recentReports', 'byRoom'
        ));
    }
}
