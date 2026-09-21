<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Report;
use App\Models\Room;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ExportController extends Controller
{
    public function index()
    {
        $categories = Category::active()->get();
        $rooms = Room::active()->get();
        $technicians = User::technicians()->get();

        return view('admin.exports.index', compact('categories', 'rooms', 'technicians'));
    }

    public function generate(Request $request)
    {
        $request->validate([
            'date_from' => 'required|date',
            'date_to' => 'required|date|after_or_equal:date_from',
            'format' => 'required|in:pdf,excel',
        ]);

        $query = Report::with(['user', 'room', 'category', 'assignment.technician'])
            ->whereBetween('reported_at', [$request->date_from, $request->date_to . ' 23:59:59']);

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }
        if ($request->filled('technician_id')) {
            $query->whereHas('assignment', fn($q) => $q->where('technician_id', $request->technician_id));
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->orderBy('reported_at', 'desc')->get();

        // Statistics
        $stats = [
            'total' => $reports->count(),
            'completed' => $reports->where('status', 'selesai')->count(),
            'in_progress' => $reports->whereIn('status', ['diproses', 'dalam_perbaikan', 'menunggu_sparepart'])->count(),
            'rejected' => $reports->where('status', 'ditolak')->count(),
            'pending' => $reports->where('status', 'menunggu_verifikasi')->count(),
        ];

        $data = [
            'reports' => $reports,
            'stats' => $stats,
            'dateFrom' => $request->date_from,
            'dateTo' => $request->date_to,
            'generatedAt' => now()->format('d/m/Y H:i'),
        ];

        if ($request->format === 'pdf') {
            $pdf = Pdf::loadView('admin.exports.report-pdf', $data);
            $pdf->setPaper('a4', 'landscape');
            return $pdf->download("laporan-kerusakan-{$request->date_from}-{$request->date_to}.pdf");
        }

        // Excel export using simple CSV for now (Maatwebsite needs to be installed)
        $filename = "laporan-kerusakan-{$request->date_from}-{$request->date_to}.csv";
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($reports) {
            $file = fopen('php://output', 'w');
            // UTF-8 BOM for Excel compatibility
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, [
                'No. Tiket', 'Tanggal', 'Pelapor', 'Ruangan', 'Kategori',
                'Prioritas', 'Status', 'Teknisi', 'Deskripsi',
            ]);

            foreach ($reports as $report) {
                fputcsv($file, [
                    $report->ticket_number,
                    $report->reported_at->format('d/m/Y H:i'),
                    $report->user->name,
                    $report->room->name,
                    $report->category->name,
                    $report->priority->label(),
                    $report->status->label(),
                    $report->assignment?->technician?->name ?? '-',
                    $report->description,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
