# Dokumentasi Perancangan Sistem (SIMFAS)

Dokumen ini berisi diagram-diagram perancangan sistem yang sangat cocok untuk dimasukkan ke dalam laporan tugas akhir, makalah, atau presentasi Anda.

Anda dapat melihat gambar diagram ini secara langsung dengan meng-copy kodenya dan mem-pastenya di web [Mermaid Live Editor](https://mermaid.live/) atau meng-upload file ini ke GitHub.

## 1. Diagram Use Case (Hak Akses Pengguna)
Diagram ini menjelaskan apa saja yang bisa dilakukan oleh masing-masing pengguna di dalam sistem.

```mermaid
flowchart LR
    %% Definisi Aktor
    P[Pelapor / Perawat]
    A[Administrator / Supervisor]
    T[Teknisi]

    %% Sistem Boundary
    subgraph Sistem Manajemen Fasilitas Rumah Sakit
        %% Use Case Pelapor
        UC1(Buat Laporan Kerusakan)
        UC2(Pantau Status Laporan)
        
        %% Use Case Admin
        UC3(Kelola Data Master Ruangan & Fasilitas)
        UC4(Verifikasi Laporan Masuk)
        UC5(Tugaskan Teknisi)
        UC6(Cetak Rekap Laporan PDF/Excel)
        
        %% Use Case Teknisi
        UC7(Ubah Status Shift / Jam Kerja)
        UC8(Terima & Lihat Tugas)
        UC9(Update Progress Pekerjaan & Foto)
    end

    %% Hubungan Aktor ke Use Case
    P --> UC1
    P --> UC2

    A --> UC3
    A --> UC4
    A --> UC5
    A --> UC6
    A --> UC2

    T --> UC7
    T --> UC8
    T --> UC9
```

---

## 2. Entity Relationship Diagram (ERD) / Relasi Database
Diagram ini menjelaskan struktur dan hubungan antar tabel di dalam database MySQL sistem kita.

```mermaid
erDiagram
    USERS ||--o{ REPORTS : "membuat"
    USERS ||--o{ ASSIGNMENTS : "mengerjakan"
    ROLES ||--o{ USERS : "memiliki"
    ROOMS ||--o{ FACILITIES : "berisi"
    ROOMS ||--o{ REPORTS : "lokasi kerusakan"
    CATEGORIES ||--o{ REPORTS : "kategori"
    FACILITIES ||--o{ REPORTS : "terkait dengan"
    REPORTS ||--o{ REPORT_IMAGES : "memiliki bukti"
    REPORTS ||--o{ STATUS_HISTORIES : "riwayat"
    REPORTS ||--o| ASSIGNMENTS : "ditugaskan ke"
    ASSIGNMENTS ||--o{ PROGRESS_HISTORIES : "catatan progress"

    USERS {
        bigint id PK
        bigint role_id FK
        string name
        string email
        string password
        boolean is_on_shift
    }
    
    REPORTS {
        bigint id PK
        bigint user_id FK
        bigint room_id FK
        bigint category_id FK
        bigint facility_id FK
        string ticket_number
        string status
        string priority
        text description
        datetime reported_at
    }

    ASSIGNMENTS {
        bigint id PK
        bigint report_id FK
        bigint technician_id FK
        datetime estimated_start
        datetime estimated_completion
        datetime actual_completion
        int latest_progress
    }
```

---

## 3. Flowchart Alur Sistem (Proses Bisnis)
Diagram ini menjelaskan perjalanan pelaporan kerusakan dari awal ditemukan sampai selesai diperbaiki.

```mermaid
flowchart TD
    Start([Pelapor Menemukan Kerusakan]) --> LoginPelapor[Pelapor Buka Web & Login]
    LoginPelapor --> IsiForm[Isi Form Kerusakan & Upload Foto]
    IsiForm --> StatusMenunggu[Status: Menunggu Verifikasi]
    
    StatusMenunggu --> NotifAdmin((Notifikasi Lonceng ke Admin))
    NotifAdmin --> LoginAdmin[Admin Buka Dashboard]
    LoginAdmin --> CekValid{Apakah Laporan Valid?}
    
    CekValid -- Tidak --> Ditolak[Admin Menolak Laporan]
    Ditolak --> StatusDitolak[Status: Ditolak]
    StatusDitolak --> End([Selesai])
    
    CekValid -- Ya --> Tugaskan[Admin Memilih Teknisi & Estimasi Waktu]
    Tugaskan --> StatusDitugaskan[Status: Ditugaskan]
    
    StatusDitugaskan --> NotifTeknisi((Notifikasi ke Teknisi))
    NotifTeknisi --> BukaTugas[Teknisi Mengecek Detail Tugas]
    BukaTugas --> MulaiKerja[Teknisi Menuju Lokasi Kerusakan]
    MulaiKerja --> UpdateProgress[Teknisi Update Progress Berkala]
    UpdateProgress --> StatusDiproses[Status: Diproses]
    
    StatusDiproses --> SelesaiKerja[Teknisi Meng-upload Bukti Selesai]
    SelesaiKerja --> StatusSelesai[Status: Selesai]
    
    StatusSelesai --> NotifAkhir((Notifikasi ke Admin & Pelapor))
    NotifAkhir --> Cetak[Admin Dapat Mencetak Laporan PDF di Akhir Bulan]
```
