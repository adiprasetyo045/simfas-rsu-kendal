@extends('layouts.app')
@section('title', isset($facility) ? 'Edit Fasilitas' : 'Tambah Fasilitas')
@section('page-title', isset($facility) ? 'Edit Fasilitas' : 'Tambah Fasilitas')
@section('content')
<div class="row justify-content-center"><div class="col-lg-6"><div class="card"><div class="card-body">
    <form action="{{ isset($facility) ? route('admin.facilities.update', $facility) : route('admin.facilities.store') }}" method="POST">
        @csrf @if(isset($facility)) @method('PUT') @endif
        <div class="row g-3">
            <div class="col-md-8 mb-3"><label class="form-label">Nama Fasilitas *</label><input type="text" name="name" class="form-control" value="{{ old('name', $facility->name ?? '') }}" required></div>
            <div class="col-md-4 mb-3"><label class="form-label">Kode *</label><input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $facility->code ?? '') }}" required placeholder="FAC-XXX-001">@error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        </div>
        <div class="row g-3">
            <div class="col-md-6 mb-3"><label class="form-label">Ruangan *</label><select name="room_id" class="form-select" required><option value="">-- Pilih --</option>@foreach($rooms as $r)<option value="{{ $r->id }}" {{ old('room_id', $facility->room_id ?? '')==$r->id?'selected':'' }}>{{ $r->name }}</option>@endforeach</select></div>
            <div class="col-md-6 mb-3"><label class="form-label">Kategori *</label><select name="category_id" class="form-select" required><option value="">-- Pilih --</option>@foreach($categories as $c)<option value="{{ $c->id }}" {{ old('category_id', $facility->category_id ?? '')==$c->id?'selected':'' }}>{{ $c->name }}</option>@endforeach</select></div>
        </div>
        <div class="row g-3">
            <div class="col-md-6 mb-3"><label class="form-label">Kondisi</label><select name="condition" class="form-select"><option value="baik" {{ old('condition', $facility->condition ?? '')=='baik'?'selected':'' }}>Baik</option><option value="rusak_ringan" {{ old('condition', $facility->condition ?? '')=='rusak_ringan'?'selected':'' }}>Rusak Ringan</option><option value="rusak_berat" {{ old('condition', $facility->condition ?? '')=='rusak_berat'?'selected':'' }}>Rusak Berat</option></select></div>
            <div class="col-md-6 mb-3"><label class="form-label">Tanggal Instalasi</label><input type="date" name="installation_date" class="form-control" value="{{ old('installation_date', isset($facility) && $facility->installation_date ? $facility->installation_date->format('Y-m-d') : '') }}"></div>
        </div>
        <div class="mb-3"><label class="form-label">Deskripsi</label><textarea name="description" class="form-control" rows="2">{{ old('description', $facility->description ?? '') }}</textarea></div>
        @if(isset($facility))<div class="mb-3 form-check"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ $facility->is_active?'checked':'' }}><label class="form-check-label" for="is_active">Aktif</label></div>@endif
        <div class="d-flex justify-content-between"><a href="{{ route('admin.facilities.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a><button class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan</button></div>
    </form>
</div></div></div></div>
@endsection
