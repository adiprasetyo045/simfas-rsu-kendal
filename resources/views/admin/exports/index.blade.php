@extends('layouts.app')
@section('title', 'Export Laporan')
@section('page-title', 'Export Laporan')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header"><i class="bi bi-file-earmark-bar-graph me-2"></i>Generate Laporan</div>
            <div class="card-body">
                <form action="{{ route('admin.export.generate') }}" method="POST">
                    @csrf
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Dari Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="date_from" class="form-control @error('date_from') is-invalid @enderror" value="{{ old('date_from', now()->startOfMonth()->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sampai Tanggal <span class="text-danger">*</span></label>
                            <input type="date" name="date_to" class="form-control @error('date_to') is-invalid @enderror" value="{{ old('date_to', now()->format('Y-m-d')) }}" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Kategori</label>
                            <select name="category_id" class="form-select"><option value="">Semua</option>@foreach($categories as $c)<option value="{{ $c->id }}">{{ $c->name }}</option>@endforeach</select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Ruangan</label>
                            <select name="room_id" class="form-select"><option value="">Semua</option>@foreach($rooms as $r)<option value="{{ $r->id }}">{{ $r->name }}</option>@endforeach</select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Teknisi</label>
                            <select name="technician_id" class="form-select"><option value="">Semua</option>@foreach($technicians as $t)<option value="{{ $t->id }}">{{ $t->name }}</option>@endforeach</select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select"><option value="">Semua</option>@foreach(\App\Enums\ReportStatus::cases() as $s)<option value="{{ $s->value }}">{{ $s->label() }}</option>@endforeach</select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Format <span class="text-danger">*</span></label>
                            <select name="format" class="form-select" required>
                                <option value="pdf">PDF</option>
                                <option value="excel">Excel (CSV)</option>
                            </select>
                        </div>
                    </div>
                    <hr>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-download me-1"></i> Download Laporan</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
