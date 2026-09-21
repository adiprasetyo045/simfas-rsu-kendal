@extends('layouts.app')
@section('title', 'Laporan Saya')
@section('page-title', 'Laporan Saya')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <p class="mb-0 text-muted">Daftar semua laporan kerusakan yang Anda buat.</p>
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
                        <th>Teknisi</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
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
                        <td>{{ $report->assignment?->technician?->name ?? '-' }}</td>
                        <td><small>{{ $report->reported_at->format('d/m/Y H:i') }}</small></td>
                        <td>
                            <a href="{{ route('reporter.reports.show', $report) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a>
                            @if($report->canBeEdited())
                            <a href="{{ route('reporter.reports.edit', $report) }}" class="btn btn-sm btn-outline-warning"><i class="bi bi-pencil"></i></a>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="empty-state">
                                <i class="bi bi-clipboard-plus"></i>
                                <h5>Belum Ada Laporan</h5>
                                <a href="{{ route('reporter.reports.create') }}" class="btn btn-primary mt-2"><i class="bi bi-plus-circle me-1"></i> Buat Laporan</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($reports->hasPages())
    <div class="card-footer">{{ $reports->links() }}</div>
    @endif
</div>
@endsection
