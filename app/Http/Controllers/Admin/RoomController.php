<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::withCount('facilities', 'reports')->latest()->paginate(15);
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('admin.rooms.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'floor' => 'nullable|string|max:100',
            'building' => 'nullable|string|max:100',
            'unit' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
        ]);

        Room::create($request->only(['name', 'floor', 'building', 'unit', 'description']));

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Ruangan berhasil ditambahkan.');
    }

    public function edit(Room $room)
    {
        return view('admin.rooms.form', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'floor' => 'nullable|string|max:100',
            'building' => 'nullable|string|max:100',
            'unit' => 'nullable|string|max:100',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['name', 'floor', 'building', 'unit', 'description']);
        $data['is_active'] = $request->boolean('is_active');
        $room->update($data);

        return redirect()->route('admin.rooms.index')
            ->with('success', 'Data ruangan berhasil diperbarui.');
    }

    public function destroy(Room $room)
    {
        if ($room->reports()->exists()) {
            return back()->with('error', 'Ruangan tidak dapat dihapus karena memiliki laporan terkait.');
        }
        $room->delete();
        return redirect()->route('admin.rooms.index')
            ->with('success', 'Ruangan berhasil dihapus.');
    }
}
