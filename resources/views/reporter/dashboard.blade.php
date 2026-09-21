@extends('layouts.app')
@section('title', 'Dashboard Pelapor')
@section('page-title', 'Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card stat-primary fade-in-up">
            <div class="stat-card-icon bg-primary-soft"><i class="bi bi-file-earmark-text"></i></div>
            <div class="stat-card-value">{{ $stats['total'] }}</div>
            <div class="stat-card-label">Total Laporan</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card stat-warning fade-in-up" style="animation-delay:0.1s">
            <div class="stat-card-icon bg-warning-soft"><i class="bi bi-hourglass-split"></i></div>
            <div class="stat-card-value">{{ $stats['pending'] }}</div>
            <div class="stat-card-label">Menunggu Verifikasi</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card stat-primary fade-in-up" style="animation-delay:0.2s">
            <div class="stat-card-icon bg-primary-soft"><i class="bi bi-gear-wide-connected"></i></div>
            <div class="stat-card-value">{{ $stats['active'] }}</div>
            <div class="stat-card-label">Sedang Diproses</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card stat-success fade-in-up" style="animation-delay:0.3s">
            <div class="stat-card-icon bg-success-soft"><i class="bi bi-check-circle"></i></div>
            <div class="stat-card-value">{{ $stats['completed'] }}</div>
            <div class="stat-card-label">Selesai</div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Laporan Terbaru Saya</h5>
    <a href="{{ route('reporter.reports.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-circle me-1"></i> Buat Laporan Baru
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No. Tiket</th>
                        <th>Kategori</th>
                        <th>Ruangan</th>
                        <th>Prioritas</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentReports as $report)
                    <tr>
                        <td><a href="{{ route('reporter.reports.show', $report) }}" class="fw-semibold text-primary">{{ $report->ticket_number }}</a></td>
                        <td><i class="{{ $report->category->icon ?? 'bi-tag' }} me-1"></i>{{ $report->category->name }}</td>
                        <td>{{ $report->room->name }}</td>
                        <td><span class="badge bg-{{ $report->priority->color() }}">{{ $report->priority->label() }}</span></td>
                        <td>
                            <span class="badge-status bg-{{ $report->status->color() }} bg-opacity-10 text-{{ $report->status->color() }}">
                                <i class="{{ $report->status->icon() }}"></i> {{ $report->status->label() }}
                            </span>
                        </td>
                        <td><small>{{ $report->reported_at->format('d/m/Y H:i') }}</small></td>
                        <td><a href="{{ route('reporter.reports.show', $report) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="empty-state">
                                <i class="bi bi-clipboard-plus"></i>
                                <h5>Belum Ada Laporan</h5>
                                <p>Buat laporan pertama Anda untuk memulai.</p>
                                <a href="{{ route('reporter.reports.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i> Buat Laporan</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
