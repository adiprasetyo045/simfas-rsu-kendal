@extends('layouts.app')
@section('title', 'Detail Tiket ' . $ticket->ticket_number)
@section('page-title', 'Detail Tiket')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.tickets.index') }}">Tiket</a></li>
<li class="breadcrumb-item active">{{ $ticket->ticket_number }}</li>
@endsection

@section('content')
<div class="row g-3">
    <!-- Report Detail -->
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-ticket-detailed me-2"></i>{{ $ticket->ticket_number }}</span>
                <span class="badge-status bg-{{ $ticket->status->color() }} bg-opacity-10 text-{{ $ticket->status->color() }}">
                    <i class="{{ $ticket->status->icon() }}"></i> {{ $ticket->status->label() }}
                </span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Pelapor</small>
                        <strong>{{ $ticket->user->name }}</strong>
                        <small class="text-muted d-block">{{ $ticket->user->unit }} - {{ $ticket->user->position }}</small>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Dilaporkan</small>
                        <strong>{{ $ticket->reported_at->format('d M Y, H:i') }}</strong>
                        <small class="text-muted d-block">{{ $ticket->reported_at->diffForHumans() }}</small>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4">
                        <small class="text-muted d-block">Ruangan</small>
                        <strong>{{ $ticket->room->name }}</strong>
                        <small class="text-muted d-block">{{ $ticket->room->floor }}, {{ $ticket->room->building }}</small>
                    </div>
                    <div class="col-sm-4">
                        <small class="text-muted d-block">Kategori</small>
                        <strong><i class="{{ $ticket->category->icon ?? 'bi-tag' }} me-1"></i>{{ $ticket->category->name }}</strong>
                    </div>
                    <div class="col-sm-4">
                        <small class="text-muted d-block">Prioritas</small>
                        <span class="badge bg-{{ $ticket->priority->color() }} fs-6">
                            <i class="{{ $ticket->priority->icon() }}"></i> {{ $ticket->priority->label() }}
                        </span>
                    </div>
                </div>
                @if($ticket->facility)
                <div class="mb-3">
                    <small class="text-muted d-block">Fasilitas</small>
                    <strong>{{ $ticket->facility->name }}</strong> <small class="text-muted">({{ $ticket->facility->code }})</small>
                </div>
                @endif
                @if($ticket->location_detail)
                <div class="mb-3">
                    <small class="text-muted d-block">Detail Lokasi</small>
                    <span>{{ $ticket->location_detail }}</span>
                </div>
                @endif
                <div class="mb-3">
                    <small class="text-muted d-block">Deskripsi Kerusakan</small>
                    <p class="mb-0">{{ $ticket->description }}</p>
                </div>

                @if($ticket->images->isNotEmpty())
                <div>
                    <small class="text-muted d-block mb-2">Foto Kerusakan</small>
                    <div class="image-gallery">
                        @foreach($ticket->images as $image)
                        <div class="image-gallery-item">
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="Foto Kerusakan" onclick="window.open(this.src, '_blank')">
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Assignment & Progress -->
        @if($ticket->assignment)
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-person-badge me-2"></i>Penugasan Teknisi</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-4">
                        <small class="text-muted d-block">Teknisi</small>
                        <strong>{{ $ticket->assignment->technician->name }}</strong>
                    </div>
                    <div class="col-sm-4">
                        <small class="text-muted d-block">Estimasi Mulai</small>
                        <span>{{ $ticket->assignment->estimated_start?->format('d/m/Y H:i') ?? '-' }}</span>
                    </div>
                    <div class="col-sm-4">
                        <small class="text-muted d-block">Estimasi Selesai</small>
                        <span class="{{ $ticket->assignment->is_overdue ? 'text-danger fw-bold' : '' }}">
                            {{ $ticket->assignment->estimated_completion?->format('d/m/Y H:i') ?? '-' }}
                            @if($ticket->assignment->is_overdue) <i class="bi bi-exclamation-triangle-fill text-danger"></i> @endif
                        </span>
                    </div>
                </div>
                @if($ticket->assignment->actual_completion)
                <div class="row mt-2">
                    <div class="col-sm-4">
                        <small class="text-muted d-block">Selesai Aktual</small>
                        <strong>{{ $ticket->assignment->actual_completion->format('d/m/Y H:i') }}</strong>
                    </div>
                    <div class="col-sm-4">
                        <small class="text-muted d-block">Status</small>
                        @if($ticket->assignment->is_on_time)
                        <span class="badge bg-success"><i class="bi bi-check"></i> Tepat Waktu</span>
                        @else
                        <span class="badge bg-danger"><i class="bi bi-x"></i> Terlambat</span>
                        @endif
                    </div>
                </div>
                @endif
                @if($ticket->assignment->admin_notes)
                <div class="mt-2">
                    <small class="text-muted d-block">Catatan Admin</small>
                    <span>{{ $ticket->assignment->admin_notes }}</span>
                </div>
                @endif

                <!-- Progress -->
                @if($ticket->assignment->progressHistories->isNotEmpty())
                <hr>
                <h6 class="mb-3"><i class="bi bi-list-check me-1"></i> Progress Perbaikan</h6>
                <div class="progress mb-3">
                    <div class="progress-bar bg-primary" style="width: {{ $ticket->assignment->latest_progress }}%">
                        {{ $ticket->assignment->latest_progress }}%
                    </div>
                </div>
                @foreach($ticket->assignment->progressHistories as $progress)
                <div class="border rounded p-2 mb-2 bg-light">
                    <div class="d-flex justify-content-between">
                        <strong style="font-size:0.85rem">{{ ucfirst(str_replace('_', ' ', $progress->status)) }} ({{ $progress->progress_percentage }}%)</strong>
                        <small class="text-muted">{{ $progress->created_at->format('d/m/Y H:i') }}</small>
                    </div>
                    <p class="mb-0 mt-1" style="font-size:0.83rem">{{ $progress->description }}</p>
                    @if($progress->images->isNotEmpty())
                    <div class="image-gallery mt-2">
                        @foreach($progress->images as $img)
                        <div class="image-gallery-item" style="width:80px;height:80px">
                            <img src="{{ asset('storage/' . $img->image_path) }}" alt="{{ $img->type }}">
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endforeach
                @endif
            </div>
        </div>
        @endif

        <!-- Status History Timeline -->
        <div class="card">
            <div class="card-header"><i class="bi bi-clock-history me-2"></i>Riwayat Status</div>
            <div class="card-body">
                <div class="timeline">
                    @foreach($ticket->statusHistories as $history)
                    <div class="timeline-item">
                        @php
                            $statusEnum = \App\Enums\ReportStatus::tryFrom($history->new_status);
                            $dotColor = $statusEnum ? $statusEnum->color() : 'secondary';
                        @endphp
                        <div class="timeline-dot bg-{{ $dotColor }}"></div>
                        <div class="timeline-content">
                            <div class="d-flex justify-content-between align-items-start">
                                <div>
                                    <strong style="font-size:0.85rem">{{ $statusEnum?->label() ?? $history->new_status }}</strong>
                                    <small class="text-muted d-block">oleh {{ $history->changedBy->name }}</small>
                                </div>
                                <span class="timeline-time">{{ $history->created_at->format('d/m/Y H:i') }}</span>
                            </div>
                            @if($history->notes)
                            <p class="mb-0 mt-1" style="font-size:0.82rem; color:#555">{{ $history->notes }}</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Action Panel -->
    <div class="col-lg-4">
        @if(!in_array($ticket->status->value, ['selesai', 'ditolak']))
        <div class="card">
            <div class="card-header"><i class="bi bi-pencil-square me-2"></i>Update Tiket</div>
            <div class="card-body">
                <form action="{{ route('admin.tickets.update', $ticket) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-3">
                        <label class="form-label">Ubah Status</label>
                        <select name="status" class="form-select" required>
                            <option value="">-- Pilih Status --</option>
                            @foreach($ticket->status->allowedTransitions() as $nextStatus)
                            <option value="{{ $nextStatus->value }}">{{ $nextStatus->label() }}</option>
                            @endforeach
                        </select>
                    </div>

                    @if($ticket->status->value === 'menunggu_verifikasi')
                    <div class="mb-3">
                        <label class="form-label">Tugaskan Teknisi</label>
                        <select name="technician_id" class="form-select">
                            <option value="">-- Pilih Teknisi --</option>
                            @foreach($technicians as $tech)
                            <option value="{{ $tech->id }}">{{ $tech->name }} ({{ $tech->position }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Estimasi Mulai</label>
                        <input type="datetime-local" name="estimated_start" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Estimasi Selesai</label>
                        <input type="datetime-local" name="estimated_completion" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan Admin</label>
                        <textarea name="admin_notes" class="form-control" rows="2" placeholder="Catatan untuk teknisi..."></textarea>
                    </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">Catatan</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Catatan perubahan status..."></textarea>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-lg me-1"></i> Update Status
                    </button>
                </form>
            </div>
        </div>
        @else
        <div class="card">
            <div class="card-body text-center py-4">
                @if($ticket->status->value === 'selesai')
                <i class="bi bi-check-circle-fill text-success" style="font-size:3rem"></i>
                <h5 class="mt-2 text-success">Perbaikan Selesai</h5>
                @else
                <i class="bi bi-x-circle-fill text-danger" style="font-size:3rem"></i>
                <h5 class="mt-2 text-danger">Laporan Ditolak</h5>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
