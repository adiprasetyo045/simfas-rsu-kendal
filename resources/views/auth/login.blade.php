@extends('layouts.auth')

@section('content')
<div class="login-wrapper">
    {{-- ─── Left Panel: Photo Background + Branding ──────── --}}
    <div class="login-left">
        <div class="login-left-bg">
            <img src="{{ asset('images/login.jpeg') }}" alt="Hospital">
            <div class="login-left-overlay"></div>
        </div>
        <div class="login-left-content">
            <div class="login-brand-badge">SIMFAS</div>
            <h1 class="login-left-title">
                Sistem Manajemen<br>Fasilitas (SIMFAS)<br>RSU Muhammadiyah Kendal
            </h1>
            <p class="login-left-subtitle">
                Sistem Pelaporan & Pemeliharaan Fasilitas
            </p>
        </div>
    </div>

    {{-- ─── Right Panel: Login Form ──────────────────── --}}
    <div class="login-right">
        <div class="login-right-inner">
            {{-- Logo --}}
            <div class="login-logo">
                <img src="{{ asset('images/logo.png') }}" alt="Logo Rumah Sakit" class="login-logo-img">
            </div>

            <h2 class="login-title">Masuk</h2>

            @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show py-2" role="alert">
                <small><i class="bi bi-check-circle-fill me-1"></i> {{ session('success') }}</small>
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
            </div>
            @endif

            @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show py-2" role="alert">
                <small><i class="bi bi-exclamation-triangle-fill me-1"></i> {{ session('error') }}</small>
                <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert"></button>
            </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <input type="email" class="form-control form-control-lg @error('email') is-invalid @enderror"
                           id="email" name="email" value="{{ old('email') }}"
                           placeholder="Masukkan email Anda" required autofocus>
                    @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                               id="password" name="password"
                               placeholder="Masukkan password" required>
                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                            <i class="bi bi-eye"></i>
                        </button>
                    </div>
                    @error('password')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4 form-check">
                    <input type="checkbox" class="form-check-input" id="remember" name="remember">
                    <label class="form-check-label" for="remember" style="font-size: 0.85rem;">Ingat saya</label>
                </div>

                <button type="submit" class="btn btn-login-submit btn-lg w-100">
                    Masuk
                </button>
            </form>
        </div>

        <div class="login-copyright">
            &copy; {{ date('Y') }} <strong>SIMFAS</strong> - RSU Muhammadiyah Kendal
        </div>
    </div>
</div>

<script>
document.getElementById('togglePassword').addEventListener('click', function() {
    const input = document.getElementById('password');
    const icon = this.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
});
</script>
@endsection
