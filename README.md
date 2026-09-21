# Sistem Informasi Manajemen Fasilitas (SIMFAS)
**RSU Muhammadiyah Kendal**

SIMFAS (Sistem Informasi Manajemen Fasilitas) adalah aplikasi berbasis web yang dirancang khusus untuk mendigitalisasi proses pelaporan, pemantauan, dan penanganan kerusakan fasilitas (medis maupun non-medis) di lingkungan Rumah Sakit.

Aplikasi ini dibangun untuk menggantikan sistem pelaporan manual, sehingga setiap kerusakan dapat dilacak secara *real-time* dan ditangani oleh teknisi berdasarkan skala prioritas.

---

##  Fitur Utama
*   **Multi-Role Access:** Dilengkapi 4 hak akses (*Admin, Teknisi, Pelapor, Supervisor*).
*   **Ticketing System:** Pembuatan laporan kerusakan (tiket) yang disertai dengan lokasi ruangan, kategori, alat, prioritas, dan unggahan foto kerusakan.
*   **Real-time Tracking:** Pelapor dapat memantau status laporannya (Menunggu, Diproses, Dalam Perbaikan, Selesai).
*   **Dispatcher (Admin):** Admin dapat memverifikasi laporan masuk dan menugaskan teknisi tertentu.
*   **Dashboard & Analytics:** Visualisasi data laporan dalam bentuk grafik interaktif.
*   **Export PDF & CSV:** Pengeksporan laporan berdasarkan rentang tanggal untuk keperluan rapat bulanan.

##  Teknologi yang Digunakan
*   **Backend:** Laravel Framework (PHP 8.x)
*   **Frontend:** Bootstrap 5, Blade Templating Engine, CSS3
*   **Database:** MySQL / MariaDB
*   **Library Tambahan:** Barryvdh/Laravel-DomPDF (untuk cetak PDF)

---

##  Cara Menjalankan di Komputer Lokal (Localhost)

Jika Anda ingin menjalankan aplikasi ini di komputer sendiri (menggunakan XAMPP atau Laragon), ikuti langkah-langkah berikut:

1. **Clone atau Download** *repository* ini ke komputer Anda.
2. Buka terminal (Command Prompt/Git Bash) di dalam folder *project*.
3. Salin file `.env.example` menjadi `.env`:
   ```bash
   cp .env.example .env
   ```
4. Sesuaikan konfigurasi *database* di dalam file `.env`:
   ```env
   DB_DATABASE=nama_database_anda
   DB_USERNAME=root
   DB_PASSWORD=
   ```
5. Install dependensi PHP (Composer):
   ```bash
   composer install
   ```
6. Generate *Application Key*:
   ```bash
   php artisan key:generate
   ```
7. Jalankan Migrasi Database beserta data bawaan (Seeder):
   ```bash
   php artisan migrate --seed
   ```
8. Nyalakan *server* lokal:
   ```bash
   php artisan serve
   ```
9. Buka browser dan kunjungi `http://localhost:8000`

---

##  Akun Login Default
Setelah proses migrasi dan *seed* selesai, Anda bisa masuk menggunakan akun berikut:

**Administrator:**
*   Email: `admin@simfas.com`
*   Password: `admin123`

**Teknisi:**
*   Email: `teknisi1@simfas.com`
*   Password: `teknisi123`

**Pelapor:**
*   Email: `pelapor1@simfas.com`
*   Password: `pelapor123`

**Supervisor:**
*   Email: `supervisor@simfas.com`
*   Password: `supervisor123`
