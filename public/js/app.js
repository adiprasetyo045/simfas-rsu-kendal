/**
 * HMS - Hospital Maintenance System
 * Global JavaScript
 */

document.addEventListener('DOMContentLoaded', function () {

    // ─── Sidebar Toggle (Mobile) ────────────────────────
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });
    }

    if (overlay) {
        overlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
    }

    // ─── Auto-dismiss alerts ────────────────────────────
    document.querySelectorAll('.alert-dismissible.auto-dismiss').forEach(alert => {
        setTimeout(() => {
            const bsAlert = bootstrap.Alert.getOrCreateInstance(alert);
            bsAlert.close();
        }, 5000);
    });

    // ─── Notification Polling ───────────────────────────
    const notifBadge = document.getElementById('notifBadge');
    const notifCountUrl = document.querySelector('meta[name="notif-count-url"]');
    let lastNotifCount = 0;

    if (notifBadge && notifCountUrl) {
        // Simpan jumlah notifikasi saat ini
        lastNotifCount = parseInt(notifBadge.textContent) || 0;

        setInterval(async () => {
            try {
                const response = await fetch(notifCountUrl.content, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                });
                const data = await response.json();
                
                if (data.count > 0) {
                    notifBadge.textContent = data.count > 99 ? '99+' : data.count;
                    notifBadge.style.display = 'flex';

                    // Jika jumlah notifikasi bertambah, mainkan suara
                    if (data.count > lastNotifCount) {
                        const audio = new Audio('/audio/notification.mp3');
                        audio.play().catch(e => console.log("Suara diblokir oleh browser"));
                        
                        // Logika Auto-Refresh Halaman yang Cerdas
                        const path = window.location.pathname;
                        
                        // 1. Jangan me-reload jika user sedang berada di halaman form (agar ketikan tidak hilang)
                        const isFormPage = path.includes('/create') || path.includes('/edit');
                        
                        // 2. Boleh me-reload jika user di Dashboard, Tabel Daftar, atau Halaman Detail (Riwayat Status)
                        const isAutoReloadPage = path.includes('/dashboard') || 
                                                 path.includes('/tickets') || 
                                                 path.includes('/reports') || 
                                                 path.includes('/tasks');

                        if (isAutoReloadPage && !isFormPage) {
                            setTimeout(() => {
                                window.location.reload();
                            }, 1500); // Tunggu 1.5 detik agar suara notifikasi sempat terdengar
                        }
                    }
                } else {
                    notifBadge.style.display = 'none';
                }

                // Perbarui jumlah notifikasi terakhir
                lastNotifCount = data.count;

            } catch (e) {
                // silently fail
            }
        }, 10000); // Poll setiap 10 detik (sebelumnya 30 detik)
    }

    // ─── Confirm Delete ─────────────────────────────────
    document.querySelectorAll('[data-confirm]').forEach(el => {
        el.addEventListener('click', function (e) {
            if (!confirm(this.dataset.confirm || 'Apakah Anda yakin?')) {
                e.preventDefault();
            }
        });
    });

    // ─── Image Preview on File Input ────────────────────
    document.querySelectorAll('.image-input').forEach(input => {
        input.addEventListener('change', function () {
            const preview = document.getElementById(this.dataset.preview);
            if (!preview) return;

            preview.innerHTML = '';
            Array.from(this.files).forEach(file => {
                if (!file.type.startsWith('image/')) return;
                const reader = new FileReader();
                reader.onload = (e) => {
                    const div = document.createElement('div');
                    div.className = 'image-gallery-item';
                    div.innerHTML = `<img src="${e.target.result}" alt="Preview">`;
                    preview.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        });
    });

    // ─── Dynamic Facility Loading ───────────────────────
    const roomSelect = document.getElementById('room_id');
    const facilitySelect = document.getElementById('facility_id');

    if (roomSelect && facilitySelect) {
        roomSelect.addEventListener('change', async function () {
            const roomId = this.value;
            facilitySelect.innerHTML = '<option value="">Memuat...</option>';
            facilitySelect.disabled = true;

            if (!roomId) {
                facilitySelect.innerHTML = '<option value="">-- Pilih Fasilitas --</option>';
                facilitySelect.disabled = false;
                return;
            }

            try {
                const response = await fetch(`/api/facilities-by-room/${roomId}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                    }
                });
                const facilities = await response.json();

                facilitySelect.innerHTML = '<option value="">-- Pilih Fasilitas (Opsional) --</option>';
                facilities.forEach(f => {
                    facilitySelect.innerHTML += `<option value="${f.id}">${f.name} (${f.code})</option>`;
                });
            } catch (e) {
                facilitySelect.innerHTML = '<option value="">Gagal memuat fasilitas</option>';
            }

            facilitySelect.disabled = false;
        });
    }

});
