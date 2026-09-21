@extends('layouts.app')
@section('title', isset($category) ? 'Edit Kategori' : 'Tambah Kategori')
@section('page-title', isset($category) ? 'Edit Kategori' : 'Tambah Kategori')
@section('content')
<div class="row justify-content-center"><div class="col-lg-6"><div class="card"><div class="card-body">
    <form action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}" method="POST">
        @csrf @if(isset($category)) @method('PUT') @endif
        <div class="mb-3"><label class="form-label">Nama Kategori *</label><input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name ?? '') }}" required>@error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror</div>
        <div class="mb-3"><label class="form-label">Deskripsi</label><textarea name="description" class="form-control" rows="3">{{ old('description', $category->description ?? '') }}</textarea></div>
        <div class="mb-3"><label class="form-label">Icon Bootstrap <small class="text-muted">(contoh: bi-droplet-fill)</small></label><input type="text" name="icon" class="form-control" value="{{ old('icon', $category->icon ?? '') }}" placeholder="bi-tag-fill"></div>
        @if(isset($category))<div class="mb-3 form-check"><input type="hidden" name="is_active" value="0"><input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ $category->is_active?'checked':'' }}><label class="form-check-label" for="is_active">Aktif</label></div>@endif
        <div class="d-flex justify-content-between"><a href="{{ route('admin.categories.index') }}" class="btn btn-outline-secondary"><i class="bi bi-arrow-left me-1"></i> Kembali</a><button class="btn btn-primary"><i class="bi bi-save me-1"></i> Simpan</button></div>
    </form>
</div></div></div></div>
@endsection
