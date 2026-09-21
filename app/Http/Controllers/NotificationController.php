<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::forUser(auth()->id())
            ->with('report')
            ->latest()
            ->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            abort(403);
        }

        $notification->markAsRead();

        if ($notification->report_id) {
            $user = auth()->user();
            if ($user->isAdmin() || $user->isSupervisor()) {
                return redirect()->route('admin.tickets.show', $notification->report_id);
            } elseif ($user->isTeknisi()) {
                $assignment = $notification->report->assignment;
                if ($assignment && $assignment->technician_id === $user->id) {
                    return redirect()->route('technician.tasks.show', $assignment);
                }
            } else {
                return redirect()->route('reporter.reports.show', $notification->report_id);
            }
        }

        return redirect()->route('notifications.index');
    }

    public function markAllRead()
    {
        Notification::forUser(auth()->id())
            ->unread()
            ->update(['is_read' => true, 'read_at' => now()]);

        return redirect()->route('notifications.index')
            ->with('success', 'Semua notifikasi telah ditandai dibaca.');
    }

    public function unreadCount()
    {
        $count = Notification::forUser(auth()->id())->unread()->count();
        return response()->json(['count' => $count]);
    }
}
