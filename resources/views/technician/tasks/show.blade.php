@extends('layouts.app')
@section('title', 'Detail Tugas')
@section('page-title', 'Detail Tugas')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('technician.tasks.index') }}">Tugas</a></li>
<li class="breadcrumb-item active">{{ $assignment->report->ticket_number }}</li>
@endsection

@section('content')
<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header d-flex justify-content-between">
                <span><i class="bi bi-ticket-detailed me-2"></i>{{ $assignment->report->ticket_number }}</span>
                <span class="badge bg-{{ $assignment->report->priority->color() }}"><i class="{{ $assignment->report->priority->icon() }}"></i> {{ $assignment->report->priority->label() }}</span>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-4"><small class="text-muted">Ruangan</small><br><strong>{{ $assignment->report->room->name }}</strong></div>
                    <div class="col-sm-4"><small class="text-muted">Kategori</small><br><strong>{{ $assignment->report->category->name }}</strong></div>
                    <div class="col-sm-4"><small class="text-muted">Status</small><br>
                        <span class="badge-status bg-{{ $assignment->report->status->color() }} bg-opacity-10 text-{{ $assignment->report->status->color() }}">
                            <i class="{{ $assignment->report->status->icon() }}"></i> {{ $assignment->report->status->label() }}
                        </span>
                    </div>
                </div>
                <div class="mb-3"><small class="text-muted d-block">Deskripsi</small><p>{{ $assignment->report->description }}</p></div>
                @if($assignment->report->images->isNotEmpty())
                <small class="text-muted d-block mb-2">Foto Kerusakan</small>
                <div class="image-gallery">
                    @foreach($assignment->report->images as $img)
                    <div class="image-gallery-item"><img src="{{ asset('storage/' . $img->image_path) }}" alt="Foto" onclick="window.open(this.src,'_blank')"></div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>

        <!-- Progress History -->
        @if($assignment->progressHistories->isNotEmpty())
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-list-check me-2"></i>Riwayat Progress</div>
            <div class="card-body">
                <div class="progress mb-3"><div class="progress-bar bg-primary" style="width:{{ $assignment->latest_progress }}%">{{ $assignment->latest_progress }}%</div></div>
                @foreach($assignment->progressHistories as $p)
                <div class="border rounded p-2 mb-2 bg-light">
                    <div class="d-flex justify-content-between"><strong style="font-size:0.85rem">{{ ucfirst(str_replace('_',' ',$p->status)) }} ({{ $p->progress_percentage }}%)</strong><small class="text-muted">{{ $p->created_at->format('d/m/Y H:i') }}</small></div>
                    <p class="mb-0 mt-1" style="font-size:0.83rem">{{ $p->description }}</p>
                    @if($p->images->isNotEmpty())
                    <div class="image-gallery mt-2">
                        @foreach($p->images as $img)
                        <div class="image-gallery-item" style="width:80px;height:80px"><img src="{{ asset('storage/' . $img->image_path) }}" alt="{{ $img->type }}"></div>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="col-lg-4">
        <!-- Assignment Info -->
        <div class="card mb-3">
            <div class="card-header"><i class="bi bi-info-circle me-2"></i>Info Penugasan</div>
            <div class="card-body">
                <small class="text-muted">Ditugaskan oleh</small><br><strong>{{ $assignment->assignedBy->name }}</strong>
                <hr class="my-2">
                <small class="text-muted">Estimasi Mulai</small><br>{{ $assignment->estimated_start?->format('d/m/Y H:i') ?? '-' }}
                <hr class="my-2">
                <small class="text-muted">Estimasi Selesai</small><br>
                <span class="{{ $assignment->is_overdue ? 'text-danger fw-bold' : '' }}">{{ $assignment->estimated_completion?->format('d/m/Y H:i') ?? '-' }}</span>
                @if($assignment->admin_notes)<hr class="my-2"><small class="text-muted">Catatan Admin</small><br>{{ $assignment->admin_notes }}@endif
            </div>
        </div>

        <!-- Update Progress Form -->
        @if($assignment->status !== 'selesai')
        <div class="card">
            <div class="card-header"><i class="bi bi-pencil-square me-2"></i>Update Progress</div>
            <div class="card-body">
                <form action="{{ route('technician.tasks.progress', $assignment) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Status Pekerjaan</label>
                        <select name="status" class="form-select" required>
                            <option value="dalam_perbaikan">Dalam Perbaikan</option>
                            <option value="menunggu_sparepart">Menunggu Sparepart</option>
                            <option value="selesai">Selesai</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Progress (%)</label>
                        <input type="number" name="progress_percentage" class="form-control" min="0" max="100" value="{{ $assignment->latest_progress }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Catatan Pekerjaan</label>
                        <textarea name="description" class="form-control" rows="3" required placeholder="Jelaskan pekerjaan yang dilakukan..."></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipe Foto</label>
                        <select name="image_type" class="form-select">
                            <option value="progress">Progress</option>
                            <option value="before">Sebelum</option>
                            <option value="after">Sesudah</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label" for="images">Upload Foto <small class="text-muted">(Opsional, Maks 10MB/foto)</small></label>
                        
                        <!-- Input asli disembunyikan -->
                        <input type="file" name="images[]" id="images" class="d-none image-input" accept="image/*" multiple data-preview="imagePreview">
                        
                        <!-- Tombol Kustom -->
                        <div class="d-flex gap-2 mb-2">
                            <button type="button" class="btn btn-outline-primary w-100" onclick="openCamera()">
                                <i class="bi bi-camera me-1"></i> Kamera
                            </button>
                            <button type="button" class="btn btn-outline-secondary w-100" onclick="openGallery()">
                                <i class="bi bi-images me-1"></i> Galeri
                            </button>
                        </div>
                        <div id="imagePreview" class="image-gallery mt-2"></div>
                    </div>
                    
                    <script>
                        function openCamera() {
                            const input = document.getElementById('images');
                            input.setAttribute('capture', 'environment'); // Memaksa buka kamera belakang
                            input.click();
                        }
                        
                        function openGallery() {
                            const input = document.getElementById('images');
                            input.removeAttribute('capture'); // Membuka pilihan galeri/file
                            input.click();
                        }
                    </script>
                    <button type="submit" class="btn btn-primary w-100"><i class="bi bi-save me-1"></i> Simpan Progress</button>
                </form>
            </div>
        </div>
        @else
        <div class="card">
            <div class="card-body text-center py-4">
                <i class="bi bi-check-circle-fill text-success" style="font-size:3rem"></i>
                <h5 class="mt-2 text-success">Pekerjaan Selesai</h5>
                @if($assignment->is_on_time) <span class="badge bg-success">Tepat Waktu</span> @elseif($assignment->is_on_time === false) <span class="badge bg-danger">Terlambat</span> @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
