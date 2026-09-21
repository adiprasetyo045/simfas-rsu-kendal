@extends('layouts.app')
@section('title', 'Kelola Kategori')
@section('page-title', 'Kelola Kategori')
@section('content')
<div class="d-flex justify-content-end mb-3"><a href="{{ route('admin.categories.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> Tambah Kategori</a></div>
<div class="card"><div class="card-body p-0"><div class="table-responsive">
    <table class="table table-hover mb-0">
        <thead class="table-light"><tr><th>Icon</th><th>Nama</th><th>Deskripsi</th><th>Laporan</th><th>Status</th><th>Aksi</th></tr></thead>
        <tbody>
            @forelse($categories as $cat)
            <tr>
                <td><i class="{{ $cat->icon ?? 'bi-tag' }} fs-5 text-primary"></i></td><td><strong>{{ $cat->name }}</strong></td><td><small>{{ Str::limit($cat->description, 60) }}</small></td>
                <td><span class="badge bg-primary">{{ $cat->reports_count }}</span></td>
                <td>@if($cat->is_active)<span class="badge bg-success">Aktif</span>@else<span class="badge bg-danger">Nonaktif</span>@endif</td>
                <td>
                    <a href="{{ route('admin.categories.edit', $cat) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                    <form action="{{ route('admin.categories.destroy', $cat) }}" method="POST" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" data-confirm="Yakin hapus?"><i class="bi bi-trash"></i></button></form>
                </td>
            </tr>
            @empty<tr><td colspan="6" class="text-center py-3 text-muted">Tidak ada data.</td></tr>@endforelse
        </tbody>
    </table>
</div></div>@if($categories->hasPages())<div class="card-footer">{{ $categories->links() }}</div>@endif</div>
@endsection
