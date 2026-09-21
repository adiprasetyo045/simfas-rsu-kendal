<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    @auth
    <meta name="notif-count-url" content="{{ route('notifications.unread-count') }}">
    @endauth
    <title>@yield('title', 'Dashboard') - SIMFAS RSU Muhammadiyah Kendal</title>
 
     <!-- Google Fonts -->
     <link rel="preconnect" href="https://fonts.googleapis.com">
     <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
     <!-- Bootstrap 5 CSS -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
     <!-- Bootstrap Icons -->
     <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
     <!-- Custom CSS -->
     <link href="{{ asset('css/app.css') }}?v={{ time() }}" rel="stylesheet">
     @stack('styles')
 </head>
 <body>
     <!-- Sidebar Overlay (Mobile) -->
     <div class="sidebar-overlay" id="sidebarOverlay"></div>
 
     <!-- Sidebar -->
     <nav class="sidebar" id="sidebar">
         <div class="sidebar-brand">
             <div class="sidebar-brand-icon">
                 <img src="{{ asset('images/dashboard.png') }}" alt="Logo">
             </div>
             <div class="sidebar-brand-text">
                 SIMFAS
                 <small>RSU Muhammadiyah Kendal</small>
             </div>
         </div>

        <ul class="sidebar-menu">
            @auth
                @if(auth()->user()->isAdmin() || auth()->user()->isSupervisor())
                    <li class="sidebar-menu-label">Dashboard</li>
                    <li class="sidebar-menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="bi bi-grid-1x2-fill"></i> Dashboard
                        </a>
                    </li>

                    <li class="sidebar-menu-label">Manajemen Tiket</li>
                    <li class="sidebar-menu-item {{ request()->routeIs('admin.tickets.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.tickets.index') }}">
                            <i class="bi bi-ticket-detailed-fill"></i> Daftar Tiket
                        </a>
                    </li>

                    @if(auth()->user()->isAdmin())
                    <li class="sidebar-menu-label">Master Data</li>
                    <li class="sidebar-menu-item {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.users.index') }}">
                            <i class="bi bi-people-fill"></i> Pengguna
                        </a>
                    </li>
                    <li class="sidebar-menu-item {{ request()->routeIs('admin.rooms.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.rooms.index') }}">
                            <i class="bi bi-door-open-fill"></i> Ruangan
                        </a>
                    </li>
                    <li class="sidebar-menu-item {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.categories.index') }}">
                            <i class="bi bi-tag-fill"></i> Kategori
                        </a>
                    </li>
                    <li class="sidebar-menu-item {{ request()->routeIs('admin.facilities.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.facilities.index') }}">
                            <i class="bi bi-tools"></i> Fasilitas
                        </a>
                    </li>

                    <li class="sidebar-menu-label">Laporan</li>
                    <li class="sidebar-menu-item {{ request()->routeIs('admin.export.*') ? 'active' : '' }}">
                        <a href="{{ route('admin.export.index') }}">
                            <i class="bi bi-file-earmark-bar-graph-fill"></i> Export Laporan
                        </a>
                    </li>
                    @endif

                @elseif(auth()->user()->isPelapor())
                    <li class="sidebar-menu-label">Dashboard</li>
                    <li class="sidebar-menu-item {{ request()->routeIs('reporter.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('reporter.dashboard') }}">
                            <i class="bi bi-grid-1x2-fill"></i> Dashboard
                        </a>
                    </li>

                    <li class="sidebar-menu-label">Laporan</li>
                    <li class="sidebar-menu-item {{ request()->routeIs('reporter.reports.create') ? 'active' : '' }}">
                        <a href="{{ route('reporter.reports.create') }}">
                            <i class="bi bi-plus-circle-fill"></i> Buat Laporan
                        </a>
                    </li>
                    <li class="sidebar-menu-item {{ request()->routeIs('reporter.reports.index') ? 'active' : '' }}">
                        <a href="{{ route('reporter.reports.index') }}">
                            <i class="bi bi-list-ul"></i> Laporan Saya
                        </a>
                    </li>
                    <li class="sidebar-menu-item {{ request()->routeIs('reporter.history.*') ? 'active' : '' }}">
                        <a href="{{ route('reporter.history.index') }}">
                            <i class="bi bi-clock-history"></i> Riwayat
                        </a>
                    </li>

                @elseif(auth()->user()->isTeknisi())
                    <li class="sidebar-menu-label">Dashboard</li>
                    <li class="sidebar-menu-item {{ request()->routeIs('technician.dashboard') ? 'active' : '' }}">
                        <a href="{{ route('technician.dashboard') }}">
                            <i class="bi bi-grid-1x2-fill"></i> Dashboard
                        </a>
                    </li>

                    <li class="sidebar-menu-label">Pekerjaan</li>
                    <li class="sidebar-menu-item {{ request()->routeIs('technician.tasks.*') ? 'active' : '' }}">
                        <a href="{{ route('technician.tasks.index') }}">
                            <i class="bi bi-wrench-adjustable"></i> Tugas Saya
                        </a>
                    </li>
                @endif

                <li class="sidebar-menu-label">Lainnya</li>
                <li class="sidebar-menu-item {{ request()->routeIs('notifications.*') ? 'active' : '' }}">
                    <a href="{{ route('notifications.index') }}">
                        <i class="bi bi-bell-fill"></i> Notifikasi
                        @if(auth()->user()->unread_notifications_count > 0)
                        <span class="badge bg-danger ms-auto">{{ auth()->user()->unread_notifications_count }}</span>
                        @endif
                    </a>
                </li>
            @endauth
        </ul>

        @auth
        <div class="sidebar-user">
            <div class="sidebar-user-avatar">{{ auth()->user()->initials }}</div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                <div class="sidebar-user-role">{{ auth()->user()->role_name }}</div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-sm btn-link text-white-50 p-0" title="Logout">
                    <i class="bi bi-box-arrow-right"></i>
                </button>
            </form>
        </div>
        @endauth
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Topbar -->
        <div class="topbar">
            <div class="topbar-left">
                <button class="btn-sidebar-toggle" id="sidebarToggle">
                    <i class="bi bi-list"></i>
                </button>
                <div>
                    <h1 class="page-title">@yield('page-title', 'Dashboard')</h1>
                    @hasSection('breadcrumb')
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            @yield('breadcrumb')
                        </ol>
                    </nav>
                    @endif
                </div>
            </div>
            <div class="topbar-right">
                @auth
                <a href="{{ route('notifications.index') }}" class="notification-bell">
                    <i class="bi bi-bell"></i>
                    <span class="notification-badge" id="notifBadge"
                          style="{{ auth()->user()->unread_notifications_count > 0 ? '' : 'display:none' }}">
                        {{ auth()->user()->unread_notifications_count }}
                    </span>
                </a>
                @endauth
            </div>
        </div>

        <!-- Content Area -->
        <div class="content-area">
            <!-- Flash Messages -->
            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show auto-dismiss" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show auto-dismiss" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle-fill me-2"></i>
                <strong>Terdapat kesalahan:</strong>
                <ul class="mb-0 mt-1">
                    @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @yield('content')
        </div>
    </div>

    <!-- Bootstrap 5 JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom JS -->
    <script src="{{ asset('js/app.js') }}"></script>
    
    <!-- Initialize Tooltips & Prevent Double Submit -->
    <script>
        // Initialize Tooltips
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]')
        const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl))

        // Prevent Double Submit
        document.addEventListener('submit', function(e) {
            const form = e.target;
            // Hanya aplikasikan pada form dengan method POST/PUT/DELETE
            if (form.method && form.method.toUpperCase() !== 'GET') {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    // Cek form validasi bawaan HTML5 sebelum disable
                    if (!form.checkValidity || form.checkValidity()) {
                        submitBtn.disabled = true;
                        const originalText = submitBtn.innerHTML;
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Memproses...';
                    }
                }
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>
