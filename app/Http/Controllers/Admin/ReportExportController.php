<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportExportController extends Controller
{
    public function exportPdf(Request $request)
    {
        // Query base untuk mengambil laporan beserta relasinya
        $query = Report::with(['user', 'room', 'category', 'assignment.technician'])->latest('reported_at');

        // Terapkan filter yang sama dengan halaman Daftar Tiket
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('reported_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('reported_at', '<=', $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', function($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $reports = $query->get();

        // Hitung statistik untuk header laporan
        $stats = [
            'total' => $reports->count(),
            'selesai' => $reports->where('status', \App\Enums\ReportStatus::COMPLETED)->count(),
            'diproses' => $reports->where('status', \App\Enums\ReportStatus::IN_PROGRESS)->count(),
            'menunggu' => $reports->where('status', \App\Enums\ReportStatus::PENDING)->count(),
            'ditugaskan' => $reports->where('status', \App\Enums\ReportStatus::ASSIGNED)->count(),
        ];

        $pdf = Pdf::loadView('admin.exports.tickets_pdf', compact('reports', 'stats', 'request'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('Laporan_SIMFAS_' . date('Ymd_His') . '.pdf');
    }
}
