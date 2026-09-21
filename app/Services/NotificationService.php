<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\Report;
use App\Models\User;

class NotificationService
{
    /**
     * Notify admins about a new report
     */
    public function notifyNewReport(Report $report): void
    {
        $admins = User::byRole('admin')->active()->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'new_report',
                'title' => 'Laporan Baru',
                'message' => "Laporan baru #{$report->ticket_number} telah dibuat oleh {$report->user->name} untuk {$report->category->name} di {$report->room->name}.",
                'report_id' => $report->id,
            ]);
        }
    }

    /**
     * Notify reporter about report verification
     */
    public function notifyReportVerified(Report $report): void
    {
        Notification::create([
            'user_id' => $report->user_id,
            'type' => 'report_verified',
            'title' => 'Laporan Diverifikasi',
            'message' => "Laporan #{$report->ticket_number} telah diverifikasi dan sedang diproses.",
            'report_id' => $report->id,
        ]);
    }

    /**
     * Notify reporter about report rejection
     */
    public function notifyReportRejected(Report $report, string $reason): void
    {
        Notification::create([
            'user_id' => $report->user_id,
            'type' => 'report_rejected',
            'title' => 'Laporan Ditolak',
            'message' => "Laporan #{$report->ticket_number} ditolak. Alasan: {$reason}",
            'report_id' => $report->id,
        ]);
    }

    /**
     * Notify technician about new assignment
     */
    public function notifyTechnicianAssigned(Report $report, User $technician): void
    {
        Notification::create([
            'user_id' => $technician->id,
            'type' => 'assigned',
            'title' => 'Tugas Baru',
            'message' => "Anda ditugaskan untuk menangani laporan #{$report->ticket_number} - {$report->category->name} di {$report->room->name}.",
            'report_id' => $report->id,
        ]);
    }

    /**
     * Notify reporter about status change
     */
    public function notifyStatusChanged(Report $report, string $oldStatus, string $newStatus): void
    {
        $statusLabel = $report->status->label();

        Notification::create([
            'user_id' => $report->user_id,
            'type' => 'status_changed',
            'title' => 'Status Diperbarui',
            'message' => "Status laporan #{$report->ticket_number} berubah menjadi: {$statusLabel}.",
            'report_id' => $report->id,
        ]);
    }

    /**
     * Notify reporter and admin when work is completed
     */
    public function notifyWorkCompleted(Report $report): void
    {
        // Notify reporter
        Notification::create([
            'user_id' => $report->user_id,
            'type' => 'completed',
            'title' => 'Perbaikan Selesai',
            'message' => "Perbaikan untuk laporan #{$report->ticket_number} telah selesai.",
            'report_id' => $report->id,
        ]);

        // Notify admins
        $admins = User::byRole('admin')->active()->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'completed',
                'title' => 'Perbaikan Selesai',
                'message' => "Laporan #{$report->ticket_number} telah selesai diperbaiki oleh teknisi.",
                'report_id' => $report->id,
            ]);
        }
    }
}
