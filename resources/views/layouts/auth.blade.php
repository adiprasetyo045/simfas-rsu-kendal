<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMFAS RSU Muhammadiyah Kendal</title>
    <meta name="description" content="Sistem Pelaporan Kerusakan Fasilitas Rumah Sakit">
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}?v={{ time() }}" rel="stylesheet">
</head>
<body>
    @yield('content')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- UI/UX Enhancement: Prevent Double Submit & Show Loading -->
    <script>
        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (form.method && form.method.toUpperCase() !== 'GET') {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    if (!form.checkValidity || form.checkValidity()) {
                        submitBtn.disabled = true;
                        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span> Memproses...';
                    }
                }
            }
        });
    </script>
</body>
</html>
