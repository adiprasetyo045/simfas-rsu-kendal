@extends('layouts.app')
@section('title', 'Dashboard Admin')
@section('page-title', 'Dashboard')

@section('content')
<!-- Stat Cards -->
<div class="row g-3 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-primary fade-in-up">
            <div class="stat-card-icon bg-primary-soft"><i class="bi bi-clipboard-plus"></i></div>
            <div class="stat-card-value">{{ $stats['today'] }}</div>
            <div class="stat-card-label">Laporan Hari Ini</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-warning fade-in-up" style="animation-delay:0.1s">
            <div class="stat-card-icon bg-warning-soft"><i class="bi bi-gear-wide-connected"></i></div>
            <div class="stat-card-value">{{ $stats['processing'] }}</div>
            <div class="stat-card-label">Sedang Diproses</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-success fade-in-up" style="animation-delay:0.2s">
            <div class="stat-card-icon bg-success-soft"><i class="bi bi-check-circle"></i></div>
            <div class="stat-card-value">{{ $stats['completed'] }}</div>
            <div class="stat-card-label">Selesai</div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="stat-card stat-danger fade-in-up" style="animation-delay:0.3s">
            <div class="stat-card-icon bg-danger-soft"><i class="bi bi-exclamation-triangle"></i></div>
            <div class="stat-card-value">{{ $stats['overdue'] }}</div>
            <div class="stat-card-label">Terlambat</div>
        </div>
    </div>
</div>

@if($stats['pending'] > 0)
<div class="alert alert-warning d-flex align-items-center mb-4">
    <i class="bi bi-hourglass-split me-2 fs-5"></i>
    <div>
        <strong>{{ $stats['pending'] }} laporan</strong> menunggu verifikasi Anda.
        <a href="{{ route('admin.tickets.index', ['status' => 'menunggu_verifikasi']) }}" class="alert-link">Lihat sekarang →</a>
    </div>
</div>
@endif

<!-- Charts -->
<div class="row g-3 mb-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-pie-chart me-2"></i>Laporan per Kategori</span>
            </div>
            <div class="card-body">
                <canvas id="categoryChart" height="260"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-bar-chart me-2"></i>Laporan per Bulan</span>
            </div>
            <div class="card-body">
                <canvas id="monthlyChart" height="260"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Recent Reports & Stats by Room -->
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-clock-history me-2"></i>Laporan Terbaru</span>
                <a href="{{ route('admin.tickets.index') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Tiket</th>
                                <th>Pelapor</th>
                                <th>Kategori</th>
                                <th>Ruangan</th>
                                <th>Prioritas</th>
                                <th>Status</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentReports as $report)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.tickets.show', $report) }}" class="fw-semibold text-primary">
                                        {{ $report->ticket_number }}
                                    </a>
                                </td>
                                <td>{{ $report->user->name }}</td>
                                <td><i class="{{ $report->category->icon ?? 'bi-tag' }} me-1"></i>{{ $report->category->name }}</td>
                                <td>{{ $report->room->name }}</td>
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
                                <td class="text-muted" style="font-size:0.78rem">{{ $report->reported_at->diffForHumans() }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">Belum ada laporan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-building me-2"></i>Statistik per Ruangan
            </div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    @foreach($byRoom as $item)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
                        <span style="font-size:0.85rem">{{ $item->room->name ?? 'N/A' }}</span>
                        <span class="badge bg-primary rounded-pill">{{ $item->total }}</span>
                    </div>
                    @endforeach
                    @if($byRoom->isEmpty())
                    <div class="list-group-item text-center text-muted py-3">Belum ada data.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
// Category Chart (Doughnut)
const catLabels = {!! json_encode($byCategory->pluck('label')) !!};
const catData = {!! json_encode($byCategory->pluck('total')) !!};

new Chart(document.getElementById('categoryChart'), {
    type: 'doughnut',
    data: {
        labels: catLabels,
        datasets: [{
            data: catData,
            backgroundColor: [
                '#0D6EFD', '#0DCAF0', '#198754', '#FFC107',
                '#DC3545', '#FD7E14', '#6610f2', '#d63384',
                '#20c997', '#6c757d', '#0dcaf0', '#adb5bd'
            ],
            borderWidth: 2,
            borderColor: '#fff',
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom', labels: { padding: 15, usePointStyle: true } } }
    }
});

// Monthly Chart (Bar)
const monthLabels = {!! json_encode($byMonth->pluck('month')) !!};
const monthData = {!! json_encode($byMonth->pluck('total')) !!};

new Chart(document.getElementById('monthlyChart'), {
    type: 'bar',
    data: {
        labels: monthLabels,
        datasets: [{
            label: 'Jumlah Laporan',
            data: monthData,
            backgroundColor: 'rgba(13, 110, 253, 0.8)',
            borderRadius: 8,
            barThickness: 32,
        }]
    },
    options: {
        responsive: true,
        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } },
        plugins: { legend: { display: false } }
    }
});
</script>
@endpush
