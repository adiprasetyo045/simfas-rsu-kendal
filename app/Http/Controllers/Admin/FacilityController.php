<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Facility;
use App\Models\Room;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index(Request $request)
    {
        $query = Facility::with(['room', 'category']);

        if ($request->filled('room_id')) {
            $query->where('room_id', $request->room_id);
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%");
            });
        }

        $facilities = $query->latest()->paginate(15)->withQueryString();
        $rooms = Room::active()->orderBy('name')->get();
        $categories = Category::active()->orderBy('name')->get();

        return view('admin.facilities.index', compact('facilities', 'rooms', 'categories'));
    }

    public function create()
    {
        $rooms = Room::active()->orderBy('name')->get();
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.facilities.form', compact('rooms', 'categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:facilities',
            'room_id' => 'required|exists:rooms,id',
            'category_id' => 'required|exists:categories,id',
            'condition' => 'nullable|string|max:50',
            'installation_date' => 'nullable|date',
            'description' => 'nullable|string|max:1000',
        ]);

        Facility::create($request->only([
            'name', 'code', 'room_id', 'category_id',
            'condition', 'installation_date', 'description',
        ]));

        return redirect()->route('admin.facilities.index')
            ->with('success', 'Fasilitas berhasil ditambahkan.');
    }

    public function edit(Facility $facility)
    {
        $rooms = Room::active()->orderBy('name')->get();
        $categories = Category::active()->orderBy('name')->get();
        return view('admin.facilities.form', compact('facility', 'rooms', 'categories'));
    }

    public function update(Request $request, Facility $facility)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:facilities,code,' . $facility->id,
            'room_id' => 'required|exists:rooms,id',
            'category_id' => 'required|exists:categories,id',
            'condition' => 'nullable|string|max:50',
            'installation_date' => 'nullable|date',
            'description' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $data = $request->only(['name', 'code', 'room_id', 'category_id', 'condition', 'installation_date', 'description']);
        $data['is_active'] = $request->boolean('is_active');
        $facility->update($data);

        return redirect()->route('admin.facilities.index')
            ->with('success', 'Data fasilitas berhasil diperbarui.');
    }

    public function destroy(Facility $facility)
    {
        $facility->delete();
        return redirect()->route('admin.facilities.index')
            ->with('success', 'Fasilitas berhasil dihapus.');
    }
}
