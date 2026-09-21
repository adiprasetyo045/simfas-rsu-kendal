@extends('layouts.app')
@section('title', 'Kelola Pengguna')
@section('page-title', 'Kelola Pengguna')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <form method="GET" class="d-flex gap-2">
        <input type="text" name="search" class="form-control form-control-sm" placeholder="Cari nama/email..." value="{{ request('search') }}" style="width:200px">
        <select name="role" class="form-select form-select-sm" style="width:150px">
            <option value="">Semua Role</option>
            @foreach($roles as $r)<option value="{{ $r->name }}" {{ request('role')==$r->name?'selected':'' }}>{{ $r->display_name }}</option>@endforeach
        </select>
        <button class="btn btn-sm btn-primary"><i class="bi bi-search"></i></button>
    </form>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> Tambah Pengguna</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light"><tr><th>Nama</th><th>Email</th><th>Role</th><th>Unit</th><th>Status</th><th>Login Terakhir</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($users as $u)
                    <tr>
                        <td><div class="d-flex align-items-center gap-2"><div class="sidebar-user-avatar" style="width:32px;height:32px;font-size:0.7rem">{{ $u->initials }}</div><strong>{{ $u->name }}</strong></div></td>
                        <td>{{ $u->email }}</td>
                        <td><span class="badge bg-primary bg-opacity-10 text-primary">{{ $u->role?->display_name ?? '-' }}</span></td>
                        <td><small>{{ $u->unit ?? '-' }}</small></td>
                        <td>@if($u->is_active)<span class="badge bg-success">Aktif</span>@else<span class="badge bg-danger">Nonaktif</span>@endif</td>
                        <td><small>{{ $u->last_login_at?->diffForHumans() ?? 'Belum login' }}</small></td>
                        <td>
                            <a href="{{ route('admin.users.edit', $u) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                            @if($u->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $u) }}" method="POST" class="d-inline">@csrf @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" data-confirm="Yakin hapus pengguna ini?"><i class="bi bi-trash"></i></button>
                            </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-3 text-muted">Tidak ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())<div class="card-footer">{{ $users->links() }}</div>@endif
</div>
@endsection
