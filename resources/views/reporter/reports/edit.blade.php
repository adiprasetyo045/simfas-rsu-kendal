@extends('layouts.app')
@section('title', 'Edit Laporan')
@section('page-title', 'Edit Laporan')

@section('content')
<div class="row justify-content-center"><div class="col-lg-8"><div class="card">
    <div class="card-header"><i class="bi bi-pencil-square me-2"></i>Edit Laporan {{ $report->ticket_number }}</div>
    <div class="card-body">
        <form action="{{ route('reporter.reports.update', $report) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Ruangan *</label><select name="room_id" id="room_id" class="form-select" required>@foreach($rooms as $r)<option value="{{ $r->id }}" {{ $report->room_id==$r->id?'selected':'' }}>{{ $r->name }} ({{ $r->floor }})</option>@endforeach</select></div>
                <div class="col-md-6"><label class="form-label">Kategori *</label><select name="category_id" class="form-select" required>@foreach($categories as $c)<option value="{{ $c->id }}" {{ $report->category_id==$c->id?'selected':'' }}>{{ $c->name }}</option>@endforeach</select></div>
                <div class="col-md-6"><label class="form-label">Fasilitas</label><select name="facility_id" id="facility_id" class="form-select"><option value="">-- Pilih --</option>@foreach($facilities as $f)<option value="{{ $f->id }}" {{ $report->facility_id==$f->id?'selected':'' }}>{{ $f->name }} ({{ $f->code }})</option>@endforeach</select></div>
                <div class="col-md-6"><label class="form-label">Prioritas *</label><select name="priority" class="form-select" required>@foreach($priorities as $p)<option value="{{ $p->value }}" {{ $report->priority->value==$p->value?'selected':'' }}>{{ $p->label() }}</option>@endforeach</select></div>
                <div class="col-12"><label class="form-label">Detail Lokasi</label><input type="text" name="location_detail" class="form-control" value="{{ $report->location_detail }}"></div>
                <div class="col-12"><label class="form-label">Deskripsi Kerusakan *</label><textarea name="description" class="form-control" rows="4" required>{{ $report->description }}</textarea></div>
                <div class="col-12">
                    @if($report->images->isNotEmpty())<p class="mb-2"><small class="text-muted">Foto yang sudah diupload:</small></p><div class="image-gallery mb-3">@foreach($report->images as $img)<div class="image-gallery-item"><img src="{{ asset('storage/' . $img->image_path) }}" alt="Foto"></div>@endforeach</div>@endif
                    <label class="form-label">Tambah Foto Baru <small class="text-muted">(Maks. 5 foto, 10MB/foto)</small></label><input type="file" name="images[]" class="form-control" accept="image/*" multiple>
                </div>
            </div>
            <hr>
            <div class="d-flex justify-content-between">
                <a href="{{ route('reporter.reports.show', $report) }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a>
                <button class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div></div></div>
@endsection
