# Timeline Pengembangan Sistem (SIMFAS)

Dokumen ini berisi jadwal dan rancangan waktu (*Timeline*) pengembangan Sistem Informasi Manajemen Fasilitas (SIMFAS) dari tahap awal hingga selesai *(Deployment)*. 

Anda dapat melihat visualisasi *Gantt Chart* di bawah ini dengan meng-copy blok kode Mermaid ke [Mermaid Live Editor](https://mermaid.live/).

## 1. Gantt Chart Pengembangan Sistem
*(Gantt chart adalah grafik batang standar industri untuk menunjukkan jadwal proyek).*

```mermaid
gantt
    title Timeline Pengembangan SIMFAS (3 Agustus - 3 Oktober)
    dateFormat  YYYY-MM-DD
    section 1. Perancangan
    Analisis Kebutuhan & Wawancara      :done,    des1, 2026-08-03, 5d
    Desain Database (ERD) & Alur        :done,    des2, 2026-08-08, 3d
    Desain UI/UX (Mockup)               :done,    des3, 2026-08-11, 2d

    section 2. Pengembangan Core
    Setup Laravel & Auth Multi-Role     :done,    dev1, 2026-08-13, 5d
    CRUD Data Master (Ruang, Kategori)  :done,    dev2, 2026-08-18, 6d

    section 3. Fitur Utama
    Form Pelaporan & Upload Foto        :done,    dev3, 2026-08-24, 5d
    Fitur Penugasan & Dashboard Admin   :done,    dev4, 2026-08-29, 5d
    Update Progress oleh Teknisi        :done,    dev5, 2026-09-03, 5d

    section 4. Fitur Lanjutan & Hosting
    Deployment ke Server (Hosting)      :done,    rel1, 2026-09-07, 5d
    Manajemen Shift Teknisi             :done,    dev6, 2026-09-12, 4d
    Notifikasi Real-Time (Suara & Lonceng):done,  dev7, 2026-09-16, 4d
    Laporan PDF & Excel (DomPDF)        :done,    dev8, 2026-09-20, 3d

    section 5. Testing & Rilis
    Pengujian Sistem (UAT & Bug Fixing) :active,  test1, 2026-09-23, 7d
    Pembuatan Panduan & Serah Terima    :         rel2, 2026-09-30, 4d
```

---

## 2. Rincian Fase Pengembangan (SDLC - *Agile / Waterfall*)

Durasi total pengembangan sistem ini adalah **Tepat 2 Bulan (3 Agustus - 3 Oktober)**. Waktu ini dibagi secara proporsional dari tahap wawancara hingga rilis akhir.

### Fase 1: Analisis & Perancangan (3 - 12 Agustus)
*   **Analisis Kebutuhan:** Mengumpulkan masalah di lapangan (contoh: pelaporan via WhatsApp yang tidak tertata).
*   **Perancangan Database:** Membuat ERD yang mencakup tabel *Users, Reports, Rooms, Facilities, Assignments*.
*   **Desain UI/UX:** Merancang antarmuka berbasis *Bootstrap 5* agar ramah digunakan di layar HP pelapor (*Mobile First*).

### Fase 2: Pengembangan Sistem Inti (13 - 23 Agustus)
*   **Instalasi Framework:** Setup Laravel 10 dan pengaturan *Environment* awal.
*   **Autentikasi (Login):** Membuat sistem pembatasan akses (*Middleware*) untuk 4 tingkatan (*Role*): Administrator, Supervisor, Pelapor, dan Teknisi.
*   **Data Master:** Membangun halaman Admin untuk menambah, mengubah, dan menghapus data ruangan serta kategori kerusakan.

### Fase 3: Pengembangan Modul Utama (24 Agustus - 7 September)
*   **Modul Pelapor:** Membuat form pengaduan yang mendukung pilihan kamera langsung dan galeri HP, dengan pembatasan ukuran file.
*   **Modul Penugasan:** Membangun *Dashboard Admin* untuk memverifikasi laporan masuk dan mendistribusikannya ke teknisi beserta perkiraan waktu.
*   **Modul Progress:** Membangun antarmuka teknisi untuk melaporkan persentase pekerjaan (mulai dari *Diproses* hingga *Selesai*) disertai foto bukti selesai.

### Fase 4: Modul Lanjutan & Hosting (Minggu Ke-6, 7 - 22 September)
*   **Deployment (Hosting):** Mengorbitkan *database* dan kodingan lokal ke server *hosting* nyata (InfinityFree / cPanel) lebih awal agar klien dapat memantau progres.
*   **Sistem Kehadiran (Shift):** Mencegah Admin memberikan tugas kepada Teknisi yang sedang libur / lepas dinas.
*   **Notifikasi Cerdas:** Menerapkan *AJAX Polling* untuk menampilkan lonceng merah dan suara *"Ting!"* otomatis tanpa perlu me- *refresh* layar.
*   **Pelaporan (Reporting):** Membangun sistem unduhan dokumen laporan akhir bulan berbentuk PDF menggunakan library *DomPDF*.

### Fase 5: Pengujian & Penyebaran Akhir (23 September - 3 Oktober)
*   **Testing (UAT):** Mencoba berbagai skenario di lapangan langsung melalui *link hosting* (memotret file besar, mencoba login dengan banyak tab, mengecek *bug*).
*   **Penyelesaian Dokumen:** Menyusun manual penggunaan (SOP) dan menyelesaikan dokumentasi sistem (Use Case, Flowchart) untuk diserahkan ke Manajemen RS.
