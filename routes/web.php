<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Reporter;
use App\Http\Controllers\Technician;
use Illuminate\Support\Facades\Route;

// ─── Auth Routes ────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ─── Authenticated Routes ───────────────────────────────────
Route::middleware(['auth', 'active'])->group(function () {

    // Root redirect
    Route::get('/', function () {
        $user = auth()->user();
        return match ($user->role?->name) {
            'admin', 'supervisor' => redirect()->route('admin.dashboard'),
            'pelapor' => redirect()->route('reporter.dashboard'),
            'teknisi' => redirect()->route('technician.dashboard'),
            default => redirect()->route('login'),
        };
    })->name('home');

    // ─── Notifications ──────────────────────────────────────
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{notification}/read', [NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount'])->name('notifications.unread-count');

    // ─── AJAX Endpoints ─────────────────────────────────────
    Route::get('/api/facilities-by-room/{room}', [Reporter\ReportController::class, 'facilitiesByRoom'])
        ->name('api.facilities-by-room');

    // ─── Reporter Routes ────────────────────────────────────
    Route::middleware('role:pelapor')->prefix('reporter')->name('reporter.')->group(function () {
        Route::get('/dashboard', [Reporter\DashboardController::class, 'index'])->name('dashboard');
        Route::resource('reports', Reporter\ReportController::class);
        Route::get('/history', [Reporter\HistoryController::class, 'index'])->name('history.index');
        Route::get('/history/{report}', [Reporter\HistoryController::class, 'show'])->name('history.show');
    });

    // ─── Admin Routes ───────────────────────────────────────
    Route::middleware('role:admin,supervisor')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Tickets
        Route::get('/tickets', [Admin\TicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/{ticket}', [Admin\TicketController::class, 'show'])->name('tickets.show');
        Route::put('/tickets/{ticket}', [Admin\TicketController::class, 'update'])->name('tickets.update');

        // Admin-only routes
        Route::middleware('role:admin')->group(function () {
            Route::resource('users', Admin\UserManagementController::class);
            Route::resource('rooms', Admin\RoomController::class);
            Route::resource('categories', Admin\CategoryController::class);
            Route::resource('facilities', Admin\FacilityController::class);

            // Export
            Route::get('/export', [Admin\ExportController::class, 'index'])->name('export.index');
            Route::post('/export/generate', [Admin\ExportController::class, 'generate'])->name('export.generate');
        });
    });

    // ─── Technician Routes ──────────────────────────────────
    Route::middleware('role:teknisi')->prefix('technician')->name('technician.')->group(function () {
        Route::get('/dashboard', [Technician\DashboardController::class, 'index'])->name('dashboard');
        Route::post('/toggle-shift', [Technician\DashboardController::class, 'toggleShift'])->name('toggle-shift');
        Route::get('/tasks', [Technician\ProgressController::class, 'index'])->name('tasks.index');
        Route::get('/tasks/{assignment}', [Technician\ProgressController::class, 'show'])->name('tasks.show');
        Route::post('/tasks/{assignment}/progress', [Technician\ProgressController::class, 'store'])->name('tasks.progress');
    });
});
