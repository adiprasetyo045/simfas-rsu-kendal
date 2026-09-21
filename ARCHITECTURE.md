# Architecture & Implementation Plan
**Sistem Informasi Manajemen Fasilitas (SIMFAS) - RSU Muhammadiyah Kendal**

---

## 1. Technology Stack

Aplikasi SIMFAS dibangun menggunakan teknologi yang tangguh dan umum digunakan di industri, guna memastikan kemudahan perawatan (*maintainability*) ke depannya:

*   **Backend Framework:** Laravel (PHP 8.3+)
*   **Arsitektur Desain:** MVC (Model-View-Controller)
*   **Database:** MySQL (MariaDB)
*   **Frontend UI:** Laravel Blade, Bootstrap 5, Custom Vanilla CSS
*   **Asset Management:** Vite
*   **PDF Generator:** Barryvdh/Laravel-DomPDF
*   **Production Hosting:** InfinityFree (cPanel/VistaPanel)
*   **Server OS:** Linux (Shared Hosting)

---

## 2. Rencana Implementasi & Kriteria Selesai (DoD)

Proses pengembangan SIMFAS dipecah menjadi 6 Fase utama yang saling berkaitan. Masing-masing fase terdiri dari tugas-tugas kecil yang terukur.

### Fase 1: Environment Setup & Database Modeling
**Fokus:** Menyiapkan kerangka kerja awal dan merancang fondasi *database*.

- [x] **Setup Project Laravel**
  - Menginisiasi *project* Laravel baru via Composer.
  - Mengonfigurasi `.env` untuk koneksi *database* lokal (Laragon/XAMPP).
  - *Kriteria Selesai:* Halaman default Laravel berhasil diakses tanpa error.
- [x] **Database Migration & Seeding**
  - Membuat *migration* untuk tabel: `users`, `rooms`, `categories`, `facilities`, `reports`, dan `assignments`.
  - Mengatur relasi antar tabel (contoh: *One-to-Many* antara `rooms` dan `reports`).
  - Membuat *Seeder* untuk *dummy data* awal (akun Admin).
  - *Kriteria Selesai:* Perintah `php artisan migrate --seed` berhasil dijalankan dan data masuk ke tabel MySQL.

### Fase 2: Authentication & Authorization
**Fokus:** Mengamankan pintu masuk aplikasi dan membedakan hak akses.

- [x] **Sistem Login & Logout**
  - Membuat halaman antarmuka Login dengan *background* kustom (foto RS).
  - Mengimplementasikan validasi form (email & password).
  - *Kriteria Selesai:* Pengguna berhasil login ke dasbor dan logout dengan aman.
- [x] **Role-Based Access Control (RBAC)**
  - Menambahkan *Enum Role* (Admin, Supervisor, Teknisi, Pelapor).
  - Membuat *Middleware* (`RoleMiddleware`) untuk mencegat *user* yang salah kamar.
  - *Kriteria Selesai:* Pelapor tidak bisa mengakses halaman milik Admin (dilempar ke halaman 403 atau kembali ke *dashboard* pelapor).

### Fase 3: Master Data Management (Khusus Admin)
**Fokus:** Halaman *back-office* untuk mengelola data operasional rumah sakit.

- [x] **Modul Pengguna (Users)**
  - Membuat fitur CRUD (Tambah, Edit, Hapus) untuk perawat dan teknisi.
  - *Kriteria Selesai:* Admin dapat me-reset *password* dan mengubah hak akses *user* lain.
- [x] **Modul Ruangan (Rooms) & Kategori**
  - Membuat daftar ruangan rumah sakit (IRJ, IRNA, IGD, dll) dan kategori kerusakan.
  - *Kriteria Selesai:* Opsi ruangan dan kategori muncul di *dropdown* form pembuatan tiket.
- [x] **Modul Fasilitas (Facilities)**
  - Mengelola data inventaris fasilitas beserta kode barcode/unik.
  - *Kriteria Selesai:* Daftar fasilitas dapat dicari berdasarkan kata kunci.

### Fase 4: Core Ticketing Engine (Pelaporan)
**Fokus:** Jantung utama aplikasi tempat alur perbaikan alat berjalan.

- [x] **Input Laporan Kerusakan (Pelapor)**
  - Membuat *form* pelaporan dengan opsi Ruangan, Alat, Tingkat Prioritas, dan *upload* Foto.
  - *Kriteria Selesai:* Data tersimpan dan foto berhasil masuk ke dalam folder `storage`.
- [x] **Dispatching / Penugasan (Admin)**
  - Menampilkan tiket baru (status: Menunggu Verifikasi) di layar Admin.
  - Admin dapat memilih teknisi untuk perbaikan (*Assign*).
  - *Kriteria Selesai:* Status laporan berubah menjadi "Diproses" dan nama teknisi tersimpan di tabel `assignments`.
- [x] **Update Status Pengerjaan (Teknisi)**
  - Teknisi melihat daftar pekerjaannya (Tugas Saya).
  - Teknisi dapat mengganti status (Dalam Perbaikan, Menunggu Sparepart, Selesai) dan menambahkan *Log* perbaikan.
  - *Kriteria Selesai:* Riwayat perubahan status tercatat dengan jam & tanggal (*Timestamp*).

### Fase 5: Dashboard & Reporting
**Fokus:** Menyajikan rekapitulasi kinerja secara visual.

- [x] **Widget Dasbor**
  - Menampilkan total laporan, laporan selesai, diproses, dan *pie chart* berdasarkan kategori.
  - *Kriteria Selesai:* Angka di dasbor berubah secara *real-time* sesuai status laporan hari itu.
- [x] **Fitur Export PDF & Excel (CSV)**
  - Membuat fitur *filter* berdasarkan tanggal (Tanggal Awal - Akhir).
  - Mengonversi tampilan tabel menjadi dokumen PDF menggunakan `DomPDF`.
  - *Kriteria Selesai:* Dokumen PDF dapat di-download berisi tabel yang rapi beserta ringkasan jumlah perbaikan.

### Fase 6: Deployment & Production Tuning
**Fokus:** Mengorbitkan aplikasi agar bisa diakses oleh seluruh staf rumah sakit secara *online*.

- [x] **Konfigurasi Server & Pemindahan File**
  - Mengekspor database SQL lokal ke phpMyAdmin *hosting*.
  - Mengunggah dan menyesuaikan struktur folder Laravel ke *cPanel* (memindahkan folder `public` ke `htdocs`).
  - *Kriteria Selesai:* Web berhasil diakses lewat domain/URL publik tanpa *error 500*.
- [x] **Penyesuaian Environment (Hosting Gratis)**
  - Mengubah rute direktori penyimpanan (*Storage Link Bypass*) di `filesystems.php` agar *upload* foto berfungsi di *shared hosting*.
  - Membuat direktori palsu pembantu (`htdocs/hospital-maintenance-system/public`) untuk kelancaran *render* DomPDF.
  - *Kriteria Selesai:* Foto profil/kerusakan berhasil tampil di *live server* dan fitur unduh PDF berfungsi 100%.
