<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Enums\ReportStatus;
use App\Models\Report;
use App\Models\User;
use App\Models\StatusHistory;
use App\Models\Assignment;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index(Request $request)
    {
        $query = Report::with(['user', 'room', 'category', 'assignment.technician']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by priority
        if ($request->filled('priority')) {
            $query->where('priority', $request->priority);
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filter by room
        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('ticket_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhereHas('user', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
            });
        }

        // Date range
        if ($request->filled('date_from')) {
            $query->whereDate('reported_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('reported_at', '<=', $request->date_to);
        }

        $reports = $query->latest('reported_at')->paginate(15)->withQueryString();
        $technicians = User::technicians()->get();
        $statuses = ReportStatus::cases();

        return view('admin.tickets.index', compact('reports', 'technicians', 'statuses'));
    }

    public function show(Report $ticket)
    {
        $ticket->load([
            'user', 'room', 'category', 'facility', 'images',
            'assignment.technician', 'assignment.progressHistories.images',
            'statusHistories.changedBy',
        ]);

        $technicians = User::technicians()->where('is_on_shift', true)->get();

        return view('admin.tickets.show', compact('ticket', 'technicians'));
    }

    public function update(Request $request, Report $ticket)
    {
        $request->validate([
            'status' => 'required|string',
            'notes' => 'nullable|string|max:1000',
            'technician_id' => 'nullable|exists:users,id',
            'estimated_start' => 'nullable|date',
            'estimated_completion' => 'nullable|date|after_or_equal:estimated_start',
            'admin_notes' => 'nullable|string|max:1000',
        ]);

        $oldStatus = $ticket->status->value;
        $newStatus = $request->status;

        // Update report status
        $ticket->update(['status' => $newStatus]);

        // Log status change
        StatusHistory::create([
            'report_id' => $ticket->id,
            'changed_by' => auth()->id(),
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'notes' => $request->notes,
        ]);

        // Handle technician assignment
        if ($request->filled('technician_id') && $newStatus === ReportStatus::DIPROSES->value) {
            $assignment = Assignment::updateOrCreate(
                ['report_id' => $ticket->id],
                [
                    'technician_id' => $request->technician_id,
                    'assigned_by' => auth()->id(),
                    'assigned_at' => now(),
                    'estimated_start' => $request->estimated_start,
                    'estimated_completion' => $request->estimated_completion,
                    'admin_notes' => $request->admin_notes,
                    'status' => 'ditugaskan',
                ]
            );

            $technician = User::find($request->technician_id);
            $this->notificationService->notifyTechnicianAssigned($ticket, $technician);
            $this->notificationService->notifyReportVerified($ticket);
        }

        // Handle rejection
        if ($newStatus === ReportStatus::DITOLAK->value) {
            $this->notificationService->notifyReportRejected($ticket, $request->notes ?? 'Tidak ada alasan.');
        }

        // Notify status change
        if ($oldStatus !== $newStatus && $newStatus !== ReportStatus::DITOLAK->value && $newStatus !== ReportStatus::DIPROSES->value) {
            $this->notificationService->notifyStatusChanged($ticket->fresh(), $oldStatus, $newStatus);
        }

        // Handle completion
        if ($newStatus === ReportStatus::SELESAI->value) {
            $assignment = $ticket->assignment;
            if ($assignment) {
                $assignment->update([
                    'actual_completion' => now(),
                    'status' => 'selesai',
                ]);
                $assignment->calculateOnTime();
            }
            $this->notificationService->notifyWorkCompleted($ticket);
        }

        return redirect()->route('admin.tickets.show', $ticket)
            ->with('success', 'Status tiket berhasil diperbarui.');
    }
}
