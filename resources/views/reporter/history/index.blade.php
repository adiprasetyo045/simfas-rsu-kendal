@extends('layouts.app')
@section('title', 'Riwayat Laporan')
@section('page-title', 'Riwayat Laporan')

@section('content')
<p class="text-muted mb-3">Seluruh riwayat laporan kerusakan yang pernah Anda buat.</p>
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light"><tr><th>No. Tiket</th><th>Kategori</th><th>Ruangan</th><th>Prioritas</th><th>Status</th><th>Teknisi</th><th>Tanggal</th><th>Estimasi</th><th></th></tr></thead>
                <tbody>
                    @forelse($reports as $r)
                    <tr>
                        <td><a href="{{ route('reporter.history.show', $r) }}" class="fw-semibold text-primary">{{ $r->ticket_number }}</a></td>
                        <td>{{ $r->category->name }}</td><td>{{ $r->room->name }}</td>
                        <td><span class="badge bg-{{ $r->priority->color() }}">{{ $r->priority->label() }}</span></td>
                        <td><span class="badge-status bg-{{ $r->status->color() }} bg-opacity-10 text-{{ $r->status->color() }}"><i class="{{ $r->status->icon() }}"></i> {{ $r->status->label() }}</span></td>
                        <td>{{ $r->assignment?->technician?->name ?? '-' }}</td>
                        <td><small>{{ $r->reported_at->format('d/m/Y') }}</small></td>
                        <td><small>{{ $r->assignment?->estimated_completion?->format('d/m/Y') ?? '-' }}</small></td>
                        <td><a href="{{ route('reporter.history.show', $r) }}" class="btn btn-sm btn-outline-primary"><i class="bi bi-eye"></i></a></td>
                    </tr>
                    @empty<tr><td colspan="9" class="text-center py-4 text-muted">Belum ada riwayat laporan.</td></tr>@endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($reports->hasPages())<div class="card-footer">{{ $reports->links() }}</div>@endif
</div>
@endsection
