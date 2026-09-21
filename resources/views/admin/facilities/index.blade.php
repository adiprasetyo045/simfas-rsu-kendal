@extends('layouts.app')
@section('title', 'Kelola Fasilitas')
@section('page-title', 'Kelola Fasilitas')
@section('content')
<div class="filter-section">
    <form method="GET" class="row g-2 align-items-end">
        <div class="col-md-3"><label class="form-label">Ruangan</label><select name="room_id" class="form-select form-select-sm"><option value="">Semua</option>@foreach($rooms as $r)<option value="{{ $r->id }}" {{ request('room_id')==$r->id?'selected':'' }}>{{ $r->name }}</option>@endforeach</select></div>
        <div class="col-md-3"><label class="form-label">Kategori</label><select name="category_id" class="form-select form-select-sm"><option value="">Semua</option>@foreach($categories as $c)<option value="{{ $c->id }}" {{ request('category_id')==$c->id?'selected':'' }}>{{ $c->name }}</option>@endforeach</select></div>
        <div class="col-md-3"><label class="form-label">Cari</label><input type="text" name="search" class="form-control form-control-sm" value="{{ request('search') }}" placeholder="Nama/kode..."></div>
        <div class="col-md-3 d-flex gap-1"><button class="btn btn-sm btn-primary"><i class="bi bi-search"></i> Filter</button><a href="{{ route('admin.facilities.index') }}" class="btn btn-sm btn-outline-secondary"><i class="bi bi-x-lg"></i></a><a href="{{ route('admin.facilities.create') }}" class="btn btn-sm btn-success"><i class="bi bi-plus"></i> Tambah</a></div>
    </form>
</div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="table-light"><tr><th>Kode</th><th>Nama</th><th>Ruangan</th><th>Kategori</th><th>Kondisi</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($facilities as $f)
            <tr>
                <td><code>{{ $f->code }}</code></td><td><strong>{{ $f->name }}</strong></td><td>{{ $f->room->name }}</td><td>{{ $f->category->name }}</td>
                <td><span class="badge bg-{{ $f->condition==='baik'?'success':'warning' }}">{{ ucfirst($f->condition) }}</span></td>
                <td>@if($f->is_active)<span class="badge bg-success">Aktif</span>@else<span class="badge bg-danger">Nonaktif</span>@endif</td>
                <td>
                    <a href="https://api.qrserver.com/v1/create-qr-code/?size=300x300&data={{ urlencode(route('reporter.reports.create', ['facility_id' => $f->id])) }}" target="_blank" class="btn btn-sm btn-outline-info" title="Cetak QR Code"><i class="bi bi-qr-code"></i></a>
                    <a href="{{ route('admin.facilities.edit', $f) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('admin.facilities.destroy', $f) }}" method="POST" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" data-confirm="Yakin hapus?"><i class="bi bi-trash"></i></button></form>
                </td>
            </tr>
            @empty<tr><td colspan="7" class="text-center py-3 text-muted">Tidak ada data.</td></tr>@endforelse
        </tbody>
    </table>
</div></div>@if($facilities->hasPages())<div class="card-footer">{{ $facilities->links() }}</div>@endif</div>
@endsection
