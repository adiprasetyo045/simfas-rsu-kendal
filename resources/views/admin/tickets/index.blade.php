@extends('layouts.app')
@section('title', 'Daftar Tiket')
@section('page-title', 'Manajemen Tiket')

@section('content')
<!-- Filter Section -->
<div class="filter-section">
    <form method="GET" action="{{ route('admin.tickets.index') }}" class="row g-2 align-items-end">
        <div class="col-md-2">
            <label class="form-label">Status</label>
            <select name="status" class="form-select form-select-sm">
                <option value="">Semua Status</option>
                @foreach($statuses as $status)
                <option value="{{ $status->value }}" {{ request('status') == $status->value ? 'selected' : '' }}>
                    {{ $status->label() }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Prioritas</label>
            <select name="priority" class="form-select form-select-sm">
                <option value="">Semua</option>
                @foreach(\App\Enums\ReportPriority::cases() as $priority)
                <option value="{{ $priority->value }}" {{ request('priority') == $priority->value ? 'selected' : '' }}>
                    {{ $priority->label() }}
                </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">Dari Tanggal</label>
            <input type="date" name="date_from" class="form-control form-control-sm" value="{{ request('date_from') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label">Sampai</label>
            <input type="date" name="date_to" class="form-control form-control-sm" value="{{ request('date_to') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label">Cari</label>
            <input type="text" name="search" class="form-control form-control-sm" placeholder="Tiket, nama..." value="{{ request('search') }}">
        </div>
        <div class="col-md-2 d-flex gap-1">
            <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-search"></i> Filter</button>
            <a href="{{ route('admin.tickets.index') }}" class="btn btn-outline-secondary btn-sm"><i class="bi bi-x-lg"></i></a>
        </div>
    </form>
</div>

<!-- Tickets Table -->
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No. Tiket</th>
                        <th>Pelapor</th>
                        <th>Kategori</th>
                        <th>Ruangan</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Teknisi</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                    <tr>
                        <td>
                            <a href="{{ route('admin.tickets.show', $report) }}" class="fw-semibold text-primary">
                                {{ $report->ticket_number }}
                            </a>
                        </td>
                        <td>{{ $report->user->name }}</td>
                        <td><small><i class="{{ $report->category->icon ?? 'bi-tag' }} me-1"></i>{{ $report->category->name }}</small></td>
                        <td><small>{{ $report->room->name }}</small></td>
                        <td>
                            <span class="badge bg-{{ $report->priority->color() }}">
                                {{ $report->priority->label() }}
                            </span>
                        </td>
                        <td>
                            <span class="badge-status bg-{{ $report->status->color() }} bg-opacity-10 text-{{ $report->status->color() }}">
                                <i class="{{ $report->status->icon() }}"></i> {{ $report->status->label() }}
                            </span>
                        </td>
                        <td><small>{{ $report->assignment?->technician?->name ?? '-' }}</small></td>
                        <td><small class="text-muted">{{ $report->reported_at->format('d/m/Y H:i') }}</small></td>
                        <td>
                            <a href="{{ route('admin.tickets.show', $report) }}" class="btn btn-sm btn-outline-primary" data-bs-toggle="tooltip" title="Lihat Detail Tiket">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-4">
                            <div class="empty-state">
                                <i class="bi bi-inbox"></i>
                                <h5>Tidak ada tiket</h5>
                                <p class="text-muted">Belum ada laporan yang masuk.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($reports->hasPages())
    <div class="card-footer">
        {{ $reports->links() }}
    </div>
    @endif
</div>
@endsection
