<?php

namespace App\Http\Controllers\Technician;

use App\Http\Controllers\Controller;
use App\Enums\ReportStatus;
use App\Models\Assignment;
use App\Models\ProgressHistory;
use App\Models\ProgressImage;
use App\Models\StatusHistory;
use App\Services\NotificationService;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $assignments = auth()->user()->assignments()
            ->with(['report.room', 'report.category'])
            ->latest('assigned_at')
            ->paginate(15);

        return view('technician.tasks.index', compact('assignments'));
    }

    public function show(Assignment $assignment)
    {
        if ($assignment->technician_id !== auth()->id()) {
            abort(403);
        }

        $assignment->load([
            'report.room', 'report.category', 'report.facility', 'report.images',
            'report.statusHistories.changedBy',
            'progressHistories.images',
            'assignedBy',
        ]);

        return view('technician.tasks.show', compact('assignment'));
    }

    public function store(Request $request, Assignment $assignment)
    {
        if ($assignment->technician_id !== auth()->id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|string',
            'description' => 'required|string|max:2000',
            'progress_percentage' => 'required|integer|min:0|max:100',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
            'image_type' => 'nullable|string|in:before,after,progress',
        ]);

        // Create progress history
        $progress = ProgressHistory::create([
            'assignment_id' => $assignment->id,
            'technician_id' => auth()->id(),
            'status' => $request->status,
            'description' => $request->description,
            'progress_percentage' => $request->progress_percentage,
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('progress-images/' . $assignment->id, 'public');
                ProgressImage::create([
                    'progress_history_id' => $progress->id,
                    'image_path' => $path,
                    'type' => $request->image_type ?? 'progress',
                ]);
            }
        }

        // Update assignment status
        $assignment->update(['status' => $request->status === 'selesai' ? 'selesai' : 'dikerjakan']);

        // Start work tracking
        if (!$assignment->actual_start) {
            $assignment->update(['actual_start' => now()]);
        }

        // Update report status
        $report = $assignment->report;
        $oldStatus = $report->status->value;

        $newReportStatus = match ($request->status) {
            'dalam_perbaikan' => ReportStatus::DALAM_PERBAIKAN->value,
            'menunggu_sparepart' => ReportStatus::MENUNGGU_SPAREPART->value,
            'selesai' => ReportStatus::SELESAI->value,
            default => $report->status->value,
        };

        if ($oldStatus !== $newReportStatus) {
            $report->update(['status' => $newReportStatus]);

            StatusHistory::create([
                'report_id' => $report->id,
                'changed_by' => auth()->id(),
                'old_status' => $oldStatus,
                'new_status' => $newReportStatus,
                'notes' => $request->description,
            ]);

            $this->notificationService->notifyStatusChanged($report->fresh(), $oldStatus, $newReportStatus);
        }

        // Handle completion
        if ($request->status === 'selesai') {
            $assignment->update([
                'actual_completion' => now(),
                'status' => 'selesai',
            ]);
            $assignment->calculateOnTime();
            $this->notificationService->notifyWorkCompleted($report);
        }

        return redirect()->route('technician.tasks.show', $assignment)
            ->with('success', 'Progress berhasil diperbarui.');
    }
}
