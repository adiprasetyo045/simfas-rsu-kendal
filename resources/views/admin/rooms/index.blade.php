@extends('layouts.app')
@section('title', 'Kelola Ruangan')
@section('page-title', 'Kelola Ruangan')
@section('content')
<div class="d-flex justify-content-end mb-3">
    <a href="{{ route('admin.rooms.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> Tambah Ruangan</a>
</div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="table-light"><tr><th>Nama</th><th>Lantai</th><th>Gedung</th><th>Unit</th><th>Fasilitas</th><th>Laporan</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($rooms as $room)
            <tr>
                <td><strong>{{ $room->name }}</strong></td><td>{{ $room->floor ?? '-' }}</td><td>{{ $room->building ?? '-' }}</td><td>{{ $room->unit ?? '-' }}</td>
                <td><span class="badge bg-info">{{ $room->facilities_count }}</span></td><td><span class="badge bg-primary">{{ $room->reports_count }}</span></td>
                <td>@if($room->is_active)<span class="badge bg-success">Aktif</span>@else<span class="badge bg-danger">Nonaktif</span>@endif</td>
                <td>
                    <a href="{{ route('admin.rooms.edit', $room) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" data-confirm="Yakin hapus ruangan ini?"><i class="bi bi-trash"></i></button></form>
                </td>
            </tr>
            @empty<tr><td colspan="8" class="text-center py-3 text-muted">Tidak ada data.</td></tr>@endforelse
        </tbody>
    </table>
</div></div>@if($rooms->hasPages())<div class="card-footer">{{ $rooms->links() }}</div>@endif</div>
@endsection
