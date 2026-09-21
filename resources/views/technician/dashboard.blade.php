@extends('layouts.app')
@section('title', 'Dashboard Teknisi')
@section('page-title', 'Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Status Shift: 
        @if(auth()->user()->is_on_shift)
            <span class="badge bg-success">Sedang Bersiaga (On-Shift)</span>
        @else
            <span class="badge bg-secondary">Sedang Istirahat (Off-Shift)</span>
        @endif
    </h5>
    <form action="{{ route('technician.toggle-shift') }}" method="POST" class="d-inline">
        @csrf
        @if(auth()->user()->is_on_shift)
            <button type="submit" class="btn btn-outline-secondary"><i class="bi bi-power me-1"></i>Akhiri Shift</button>
        @else
            <button type="submit" class="btn btn-success"><i class="bi bi-play-circle me-1"></i>Mulai Shift</button>
        @endif
    </form>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="stat-card stat-primary fade-in-up">
            <div class="stat-card-icon bg-primary-soft"><i class="bi bi-wrench-adjustable"></i></div>
            <div class="stat-card-value">{{ $stats['total'] }}</div>
            <div class="stat-card-label">Total Tugas</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card stat-warning fade-in-up" style="animation-delay:0.1s">
            <div class="stat-card-icon bg-warning-soft"><i class="bi bi-gear-wide-connected"></i></div>
            <div class="stat-card-value">{{ $stats['active'] }}</div>
            <div class="stat-card-label">Sedang Dikerjakan</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card stat-success fade-in-up" style="animation-delay:0.2s">
            <div class="stat-card-icon bg-success-soft"><i class="bi bi-check-circle"></i></div>
            <div class="stat-card-value">{{ $stats['completed'] }}</div>
            <div class="stat-card-label">Selesai</div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card stat-danger fade-in-up" style="animation-delay:0.3s">
            <div class="stat-card-icon bg-danger-soft"><i class="bi bi-exclamation-triangle"></i></div>
            <div class="stat-card-value">{{ $stats['overdue'] }}</div>
            <div class="stat-card-label">Terlambat</div>
        </div>
    </div>
</div>

<h5 class="mb-3"><i class="bi bi-list-task me-2"></i>Tugas Terbaru</h5>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Tiket</th><th>Kategori</th><th>Ruangan</th><th>Prioritas</th><th>Status</th><th>Estimasi</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($recentTasks as $task)
                    <tr>
                        <td><a href="{{ route('technician.tasks.show', $task) }}" class="fw-semibold text-primary">{{ $task->report->ticket_number }}</a></td>
                        <td><i class="{{ $task->report->category->icon ?? 'bi-tag' }} me-1"></i>{{ $task->report->category->name }}</td>
                        <td>{{ $task->report->room->name }}</td>
                        <td><span class="badge bg-{{ $task->report->priority->color() }}">{{ $task->report->priority->label() }}</span></td>
                        <td><span class="badge bg-{{ $task->status === 'selesai' ? 'success' : ($task->status === 'dikerjakan' ? 'primary' : 'warning') }}">{{ ucfirst($task->status) }}</span></td>
                        <td class="{{ $task->is_overdue ? 'text-danger fw-bold' : '' }}">{{ $task->estimated_completion?->format('d/m/Y') ?? '-' }}</td>
                        <td><a href="{{ route('technician.tasks.show', $task) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
                    </tr>
                    @empty
                    <tr><td colspan="7" class="text-center py-4 text-muted">Belum ada tugas.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
