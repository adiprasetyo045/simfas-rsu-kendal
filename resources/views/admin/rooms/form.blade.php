@extends('layouts.app')
@section('title', isset($room) ? 'Edit Ruangan' : 'Tambah Ruangan')
@section('page-title', isset($room) ? 'Edit Ruangan' : 'Tambah Ruangan')
@section('content')
<div class="row justify-content-center"><div class="col-lg-6"><div class="card"><div class="card-body">
    <form action="{{ isset($room) ? route('admin.rooms.update', $room) : route('admin.rooms.store') }}" method="POST">
        @csrf @if(isset($room)) @method('PUT') @endif
        <div class="mb-3"><label class="form-label">Nama Ruangan *</label><input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $room->name ?? '') }}" required>@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="row g-3">
            <div class="col-md-6 mb-3"><label class="form-label">Lantai</label><input type="text" name="floor" class="form-control" value="{{ old('floor', $room->floor ?? '') }}" placeholder="Contoh: Lantai 1"></div>
            <div class="col-md-6 mb-3"><label class="form-label">Gedung</label><input type="text" name="building" class="form-control" value="{{ old('building', $room->building ?? '') }}" placeholder="Contoh: Gedung Utama"></div>
        </div>
        <div class="mb-3"><label class="form-label">Unit</label><input type="text" name="unit" class="form-control" value="{{ old('unit', $room->unit ?? '') }}"></div>
        <div class="mb-3"><label class="form-label">Deskripsi</label><textarea name="description" class="form-control" rows="2">{{ old('description', $room->description ?? '') }}</textarea></div>
        @if(isset($room))<div class="mb-3 form-check"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ $room->is_active?'checked':'' }}><label class="form-check-label" for="is_active">Aktif</label></div>@endif
        <div class="d-flex justify-content-between"><a href="{{ route('admin.rooms.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a><button class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan</button></div>
    </form>
</div></div></div></div>
@endsection
