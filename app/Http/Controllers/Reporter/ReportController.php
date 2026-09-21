<?php

namespace App\Http\Controllers\Reporter;

use App\Http\Controllers\Controller;
use App\Enums\ReportPriority;
use App\Models\Category;
use App\Models\Facility;
use App\Models\Report;
use App\Models\ReportImage;
use App\Models\Room;
use App\Models\StatusHistory;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportController extends Controller
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function index()
    {
        $reports = auth()->user()->reports()
            ->with(['room', 'category', 'assignment.technician'])
            ->latest('reported_at')
            ->paginate(15);

        return view('reporter.reports.index', compact('reports'));
    }

    public function create(Request $request)
    {
        $rooms = Room::active()->orderBy('name')->get();
        $categories = Category::active()->orderBy('name')->get();
        $priorities = ReportPriority::cases();

        $prefillFacility = null;
        if ($request->has('facility_id')) {
            $prefillFacility = Facility::find($request->facility_id);
        }

        return view('reporter.reports.create', compact('rooms', 'categories', 'priorities', 'prefillFacility'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'category_id' => 'required|exists:categories,id',
            'facility_id' => 'nullable|exists:facilities,id',
            'priority' => 'required|string',
            'location_detail' => 'nullable|string|max:255',
            'description' => 'required|string|min:10|max:2000',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
        ], [
            'room_id.required' => 'Pilih ruangan.',
            'category_id.required' => 'Pilih kategori kerusakan.',
            'description.required' => 'Deskripsi kerusakan wajib diisi.',
            'description.min' => 'Deskripsi minimal 10 karakter.',
            'images.max' => 'Maksimal 5 foto.',
            'images.*.max' => 'Ukuran foto maksimal 10MB.',
        ]);

        $report = Report::create([
            'ticket_number' => Report::generateTicketNumber(),
            'user_id' => auth()->id(),
            'room_id' => $request->room_id,
            'category_id' => $request->category_id,
            'facility_id' => $request->facility_id,
            'priority' => $request->priority,
            'location_detail' => $request->location_detail,
            'description' => $request->description,
            'reported_at' => now(),
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('report-images/' . $report->id, 'public');
                ReportImage::create([
                    'report_id' => $report->id,
                    'image_path' => $path,
                ]);
            }
        }

        // Create initial status history
        StatusHistory::create([
            'report_id' => $report->id,
            'changed_by' => auth()->id(),
            'old_status' => null,
            'new_status' => 'menunggu_verifikasi',
            'notes' => 'Laporan baru dibuat.',
        ]);

        // Notify admins
        $this->notificationService->notifyNewReport($report);

        return redirect()->route('reporter.reports.show', $report)
            ->with('success', "Laporan berhasil dibuat dengan nomor tiket: {$report->ticket_number}");
    }

    public function show(Report $report)
    {
        // Ensure user can only see own reports
        if ($report->user_id !== auth()->id()) {
            abort(403);
        }

        $report->load([
            'room', 'category', 'facility', 'images',
            'assignment.technician', 'assignment.progressHistories.images',
            'statusHistories.changedBy',
        ]);

        return view('reporter.reports.show', compact('report'));
    }

    public function edit(Report $report)
    {
        if ($report->user_id !== auth()->id() || !$report->canBeEdited()) {
            abort(403, 'Laporan tidak dapat diedit.');
        }

        $rooms = Room::active()->orderBy('name')->get();
        $categories = Category::active()->orderBy('name')->get();
        $priorities = ReportPriority::cases();
        $facilities = Facility::where('room_id', $report->room_id)->active()->get();

        return view('reporter.reports.edit', compact('report', 'rooms', 'categories', 'priorities', 'facilities'));
    }

    public function update(Request $request, Report $report)
    {
        if ($report->user_id !== auth()->id() || !$report->canBeEdited()) {
            abort(403, 'Laporan tidak dapat diedit.');
        }

        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'category_id' => 'required|exists:categories,id',
            'facility_id' => 'nullable|exists:facilities,id',
            'priority' => 'required|string',
            'location_detail' => 'nullable|string|max:255',
            'description' => 'required|string|min:10|max:2000',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:10240',
        ]);

        $report->update($request->only([
            'room_id', 'category_id', 'facility_id', 'priority',
            'location_detail', 'description',
        ]));

        // Handle new image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('report-images/' . $report->id, 'public');
                ReportImage::create([
                    'report_id' => $report->id,
                    'image_path' => $path,
                ]);
            }
        }

        return redirect()->route('reporter.reports.show', $report)
            ->with('success', 'Laporan berhasil diperbarui.');
    }

    /**
     * AJAX: Get facilities by room
     */
    public function facilitiesByRoom(Room $room)
    {
        $facilities = $room->facilities()->active()->get(['id', 'name', 'code']);
        return response()->json($facilities);
    }
}
