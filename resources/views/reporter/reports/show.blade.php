@extends('layouts.app')
@section('title', 'Detail Laporan ' . $report->ticket_number)
@section('page-title', 'Detail Laporan')

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <!-- Report Info -->
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between">
                <span><i class="bi bi-ticket-detailed me-2"></i>{{ $report->ticket_number }}</span>
                <span class="badge-status bg-{{ $report->status->color() }} bg-opacity-10 text-{{ $report->status->color() }}">
                    <i class="{{ $report->status->icon() }}"></i> {{ $report->status->label() }}
                </span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-6"><small class="text-muted d-block">Ruangan</small><strong>{{ $report->room->name }}</strong><small class="text-muted d-block">{{ $report->room->floor }}, {{ $report->room->building }}</small></div>
                    <div class="col-sm-3"><small class="text-muted d-block">Kategori</small><strong><i class="{{ $report->category->icon ?? 'bi-tag' }} me-1"></i>{{ $report->category->name }}</strong></div>
                    <div class="col-sm-3"><small class="text-muted d-block">Prioritas</small><span class="badge bg-{{ $report->priority->color() }}"><i class="{{ $report->priority->icon() }}"></i> {{ $report->priority->label() }}</span></div>
                </div>
                @if($report->facility)<div class="mb-2"><small class="text-muted">Fasilitas:</small> {{ $report->facility->name }} ({{ $report->facility->code }})</div>@endif
                @if($report->location_detail)<div class="mb-2"><small class="text-muted">Lokasi Detail:</small> {{ $report->location_detail }}</div>@endif
                <div class="mb-3"><small class="text-muted d-block">Deskripsi</small><p>{{ $report->description }}</p></div>
                @if($report->images->isNotEmpty())
                <small class="text-muted d-block mb-2">Foto Kerusakan</small>
                <div class="image-gallery">
                    @foreach($report->images as $image)
                    <div class="image-gallery-item"><img src="{{ asset('storage/' . $image->image_path) }}" alt="Foto" onclick="window.open(this.src,'_blank')"></div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- Assignment Info -->
        @if($report->assignment)
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-wrench-adjustable me-2"></i>Informasi Penugasan</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4"><small class="text-muted d-block">Teknisi</small><strong>{{ $report->assignment->technician->name }}</strong></div>
                    <div class="col-sm-4"><small class="text-muted d-block">Estimasi Selesai</small>{{ $report->assignment->estimated_completion?->format('d/m/Y H:i') ?? '-' }}</div>
                    <div class="col-sm-4">
                        <small class="text-muted d-block">Progress</small>
                        <div class="progress mt-1"><div class="progress-bar" style="width:{{ $report->assignment->latest_progress }}%">{{ $report->assignment->latest_progress }}%</div></div>
                    </div>
                </div>
                @if($report->assignment->actual_completion)
                <div class="mt-2">
                    <small class="text-muted">Selesai:</small> {{ $report->assignment->actual_completion->format('d/m/Y H:i') }}
                    @if($report->assignment->is_on_time) <span class="badge bg-success">Tepat Waktu</span> @else <span class="badge bg-danger">Terlambat</span> @endif
                </div>
                @endif
            </div>
        </div>
        @endif

        <!-- Timeline -->
        <div class="card">
            <div class="card-header"><i class="bi bi-clock-history me-2"></i>Riwayat Status</div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($report->statusHistories as $h)
                    @php $se = \App\Enums\ReportStatus::tryFrom($h->new_status); @endphp
                    <div class="timeline-item">
                        <div class="timeline-dot bg-{{ $se?->color() ?? 'secondary' }}"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between">
                                <strong style="font-size:0.85rem">{{ $se?->label() ?? $h->new_status }}</strong>
                                <span class="timeline-time">{{ $h->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            @if($h->notes) <p class="mb-0 mt-1" style="font-size:0.82rem;color:#555">{{ $h->notes }}</p> @endif
                            <small class="text-muted">oleh {{ $h->changedBy->name }}</small>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <small class="text-muted d-block">Dilaporkan pada</small>
                <strong>{{ $report->reported_at->format('d M Y, H:i') }}</strong>
                <small class="text-muted d-block mt-1">{{ $report->reported_at->diffForHumans() }}</small>
                <hr>
                <small class="text-muted d-block">Pelapor</small>
                <strong>{{ $report->user->name }}</strong>
                <small class="text-muted d-block">{{ $report->user->unit }} - {{ $report->user->position }}</small>
            </div>
        </div>

        @if($report->canBeEdited())
        <a href="{{ route('reporter.reports.edit', $report) }}" class="btn btn-outline-warning w-100 mt-3">
            <i class="bi bi-pencil me-1"></i> Edit Laporan
        </a>
        @endif

        <a href="{{ route('reporter.reports.index') }}" class="btn btn-outline-secondary w-100 mt-2">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>
</div>
@endsection
