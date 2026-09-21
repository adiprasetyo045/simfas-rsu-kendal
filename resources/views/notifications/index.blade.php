@extends('layouts.app')
@section('title', 'Notifikasi')
@section('page-title', 'Notifikasi')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <p class="mb-0 text-muted">Daftar notifikasi Anda.</p>
    @if($notifications->where('is_read', false)->count() > 0)
    <form action="{{ route('notifications.mark-all-read') }}" method="POST">
        @csrf
        <button type="submit" class="btn btn-sm btn-outline-primary"><i class="bi bi-check-all me-1"></i> Tandai Semua Dibaca</button>
    </form>
    @endif
</div>

<div class="card">
    <div class="card-body p-0">
        @forelse($notifications as $notif)
        <form action="{{ route('notifications.read', $notif) }}" method="POST">
            @csrf
            <button type="submit" class="notification-item w-100 text-start border-0 bg-transparent {{ $notif->is_read ? '' : 'unread' }}" style="display:block">
                <div class="d-flex align-items-start gap-3">
                    <div class="mt-1">
                        @switch($notif->type)
                            @case('new_report') <i class="bi bi-clipboard-plus text-primary fs-5"></i> @break
                            @case('assigned') <i class="bi bi-person-badge text-info fs-5"></i> @break
                            @case('completed') <i class="bi bi-check-circle-fill text-success fs-5"></i> @break
                            @case('report_rejected') <i class="bi bi-x-circle-fill text-danger fs-5"></i> @break
                            @default <i class="bi bi-bell text-warning fs-5"></i>
                        @endswitch
                    </div>
                    <div class="flex-1">
                        <div class="notif-title">{{ $notif->title }}</div>
                        <div class="notif-message">{{ $notif->message }}</div>
                        <div class="notif-time"><i class="bi bi-clock me-1"></i>{{ $notif->created_at->diffForHumans() }}</div>
                    </div>
                    @if(!$notif->is_read) <span class="badge bg-primary rounded-pill">Baru</span> @endif
                </div>
            </button>
        </form>
        @empty
        <div class="text-center py-5">
            <i class="bi bi-bell-slash" style="font-size:3rem;color:#ddd"></i>
            <h5 class="mt-2 text-muted">Tidak ada notifikasi</h5>
        </div>
        @endforelse
    </div>
    @if($notifications->hasPages())<div class="card-footer">{{ $notifications->links() }}</div>@endif
</div>
@endsection
