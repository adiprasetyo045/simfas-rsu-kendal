@extends('layouts.app')
@section('title', 'Tugas Saya')
@section('page-title', 'Daftar Tugas')

@section('content')
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Tiket</th><th>Kategori</th><th>Ruangan</th><th>Prioritas</th><th>Status</th><th>Estimasi Selesai</th><th>Progress</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    @forelse($assignments as $task)
                    <tr>
                        <td><a href="{{ route('technician.tasks.show', $task) }}" class="fw-semibold text-primary">{{ $task->report->ticket_number }}</a></td>
                        <td><i class="{{ $task->report->category->icon ?? 'bi-tag' }} me-1"></i>{{ $task->report->category->name }}</td>
                        <td>{{ $task->report->room->name }}</td>
                        <td><span class="badge bg-{{ $task->report->priority->color() }}">{{ $task->report->priority->label() }}</span></td>
                        <td><span class="badge bg-{{ $task->status === 'selesai' ? 'success' : ($task->status === 'dikerjakan' ? 'primary' : 'warning') }}">{{ ucfirst($task->status) }}</span></td>
                        <td class="{{ $task->is_overdue ? 'text-danger fw-bold' : '' }}">{{ $task->estimated_completion?->format('d/m/Y H:i') ?? '-' }} @if($task->is_overdue) <i class="bi bi-exclamation-triangle-fill text-danger"></i> @endif</td>
                        <td style="min-width:120px"><div class="progress"><div class="progress-bar" style="width:{{ $task->latest_progress }}%">{{ $task->latest_progress }}%</div></div></td>
                        <td><a href="{{ route('technician.tasks.show', $task) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
                    </tr>
                    @empty
                    <tr><td colspan="8" class="text-center py-4 text-muted">Belum ada tugas yang ditugaskan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($assignments->hasPages())<div class="card-footer">{{ $assignments->links() }}</div>@endif
</div>
@endsection
