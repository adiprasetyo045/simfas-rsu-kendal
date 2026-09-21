@extends('layouts.app')
@section('title', 'Buat Laporan Kerusakan')
@section('page-title', 'Buat Laporan')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('reporter.reports.index') }}">Laporan</a></li>
<li class="breadcrumb-item active">Buat Baru</li>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-clipboard-plus me-2"></i>Form Pelaporan Kerusakan Fasilitas
            </div>
            <div class="card-body">
                <form action="{{ route('reporter.reports.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="room_id" class="form-label">Ruangan / Lokasi <span class="text-danger">*</span></label>
                            <select name="room_id" id="room_id" class="form-select @error('room_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Ruangan --</option>
                                @foreach($rooms as $room)
                                <option value="{{ $room->id }}" {{ old('room_id', isset($prefillFacility) ? $prefillFacility->room_id : null) == $room->id ? 'selected' : '' }}>
                                    {{ $room->name }} ({{ $room->floor }}, {{ $room->building }})
                                </option>
                                @endforeach
                            </select>
                            @error('room_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="category_id" class="form-label">Kategori Kerusakan <span class="text-danger">*</span></label>
                            <select name="category_id" id="category_id" class="form-select @error('category_id') is-invalid @enderror" required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="facility_id" class="form-label">Fasilitas <small class="text-muted">(Opsional)</small></label>
                            <select name="facility_id" id="facility_id" class="form-select">
                                @if(isset($prefillFacility))
                                    <option value="">-- Pilih Fasilitas --</option>
                                    @foreach(\App\Models\Facility::where('room_id', $prefillFacility->room_id)->get() as $fac)
                                        <option value="{{ $fac->id }}" {{ old('facility_id', $prefillFacility->id) == $fac->id ? 'selected' : '' }}>
                                            {{ $fac->name }} ({{ $fac->code }})
                                        </option>
                                    @endforeach
                                @else
                                    <option value="">-- Pilih Ruangan Terlebih Dahulu --</option>
                                @endif
                            </select>
                        </div>

                        <div class="col-md-6">
                            <label for="priority" class="form-label">Tingkat Prioritas <span class="text-danger">*</span></label>
                            <select name="priority" id="priority" class="form-select @error('priority') is-invalid @enderror" required>
                                @foreach($priorities as $p)
                                <option value="{{ $p->value }}" {{ old('priority', 'sedang') == $p->value ? 'selected' : '' }}>
                                    {{ $p->label() }}
                                </option>
                                @endforeach
                            </select>
                            @error('priority') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="col-12">
                            <label for="location_detail" class="form-label">Detail Lokasi <small class="text-muted">(Opsional)</small></label>
                            <input type="text" name="location_detail" id="location_detail" class="form-control"
                                   value="{{ old('location_detail') }}" placeholder="Contoh: di dekat pintu masuk, sudut kanan atas, dll.">
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label">Deskripsi Kerusakan <span class="text-danger">*</span></label>
                            <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror"
                                      rows="4" required placeholder="Jelaskan kerusakan secara detail...">{{ old('description') }}</textarea>
                            @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            <small class="text-muted">Minimal 10 karakter</small>
                        </div>

                        <div class="col-12">
                            <label for="images" class="form-label">Foto Kerusakan <small class="text-muted">(Maks. 5 foto, 10MB/foto)</small></label>
                            
                            <!-- Input asli disembunyikan -->
                            <input type="file" name="images[]" id="images" class="d-none image-input"
                                   accept="image/*" multiple data-preview="imagePreview">
                            
                            <!-- Tombol Kustom -->
                            <div class="d-flex gap-2 mb-2">
                                <button type="button" class="btn btn-outline-primary w-100" onclick="openCamera()">
                                    <i class="bi bi-camera me-1"></i> Kamera
                                </button>
                                <button type="button" class="btn btn-outline-secondary w-100" onclick="openGallery()">
                                    <i class="bi bi-images me-1"></i> Galeri
                                </button>
                            </div>

                            @error('images') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                            @error('images.*') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
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
                    </div>

                    <hr>
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('reporter.reports.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-send me-1"></i> Kirim Laporan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
