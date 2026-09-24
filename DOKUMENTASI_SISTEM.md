# Dokumentasi Diagram Sistem (SIMFAS)

Dokumen ini berisi kumpulan kode diagram (Mermaid) yang bisa Anda salin untuk men-generate gambar arsitektur sistem secara otomatis.

---

## 1. Use Case Diagram
Menggambarkan interaksi hak akses antara sistem dengan aktor (Pelapor, Teknisi, Admin, Supervisor).

```mermaid
flowchart LR
    %% Actors
    Pelapor((Pelapor / Perawat))
    Teknisi((Teknisi))
    Admin((Administrator))
    Supervisor((Supervisor / Manajemen))

    %% Use Cases
    subgraph Sistem SIMFAS
        UC1(Login Sistem)
        
        UC2(Buat Tiket Laporan)
        UC2_1(Scan QR Code)
        
        UC3(Pantau Status Tiket)
        UC4(Lihat Tugas Perbaikan)
        UC5(Update Progres & Foto)
        UC6(Kelola Data Master)
        UC7(Assign Teknisi)
        UC8(Cetak Rekap PDF)
        
        UC9(Ubah Status Shift)
    end

    %% Actor to Use Case Associations
    Pelapor --> UC1
    Pelapor --> UC2
    Pelapor --> UC3

    Teknisi --- UC1
    Teknisi --- UC4
    Teknisi --- UC5
    Teknisi --- UC9

    Admin --> UC1
    Admin --> UC6
    Admin --> UC7
    Admin --> UC8
    Admin --> UC3

    Supervisor --> UC1
    Supervisor --> UC3
    Supervisor --> UC8

    %% Include and Extend Relationships (UML Standard)
    UC2 -.->|<<extend>>| UC2_1
    UC2 -.->|<<include>>| UC1
    UC4 -.->|<<include>>| UC1
    UC6 -.->|<<include>>| UC1
    UC8 -.->|<<include>>| UC1
```

---

## 2. Entity Relationship Diagram (ERD)
Menggambarkan kerangka tabel database dan relasi antar Foreign Key.

```mermaid
erDiagram
    users {
        bigint id PK
        string name
        string email
        bigint role_id FK
        boolean is_active
        boolean is_on_shift
    }
    roles {
        bigint id PK
        string name
    }
    rooms {
        bigint id PK
        string name
        string building
    }
    categories {
        bigint id PK
        string name
    }
    facilities {
        bigint id PK
        string code
        string name
        bigint room_id FK
        bigint category_id FK
    }
    reports {
        bigint id PK
        string ticket_number
        bigint user_id FK "Pelapor"
        bigint facility_id FK
        string priority
        string status
    }
    report_images {
        bigint id PK
        bigint report_id FK
        string image_path
    }
    assignments {
        bigint id PK
        bigint report_id FK
        bigint technician_id FK "Teknisi"
        bigint assigned_by FK "Admin"
    }
    progress_histories {
        bigint id PK
        bigint assignment_id FK
        text note
    }
    status_histories {
        bigint id PK
        bigint report_id FK
        string old_status
        string new_status
    }
    notifications {
        bigint id PK
        bigint user_id FK
        string title
        boolean is_read
    }

    roles ||--o{ users : "memiliki"
    rooms ||--o{ facilities : "menampung"
    categories ||--o{ facilities : "mengelompokkan"
    users ||--o{ reports : "membuat"
    facilities ||--o{ reports : "dilaporkan di"
    reports ||--o{ report_images : "lampiran foto"
    reports ||--o| assignments : "ditugaskan ke"
    users ||--o{ assignments : "diberi tugas"
    assignments ||--o{ progress_histories : "catatan progres"
    reports ||--o{ status_histories : "riwayat status"
    users ||--o{ notifications : "menerima"
```

---

## 3. Alur Kerja Sistem (Activity Diagram / Flowchart)
Menggambarkan tahapan logis pelaporan dari awal ditemukan sampai diperbaiki.

```mermaid
flowchart TD
    Start([Mulai: Kerusakan Ditemukan]) --> A[Perawat Memindai QR Code Fasilitas]
    A --> B{Apakah Sudah Login?}
    B -- Belum --> C[Halaman Login]
    C --> D[Redirect ke Form Laporan]
    B -- Sudah --> D
    
    D --> E[Isi Deskripsi, Prioritas & Foto Bukti]
    E --> F[Submit Laporan]
    F --> G[Sistem Generate Nomor Tiket]
    G --> H[(Database)]
    G --> I[Kirim Notifikasi Lonceng ke Admin]
    
    I --> J[Admin Cek Dashboard & Daftar Tiket]
    J --> K[Admin Pilih Teknisi yang Sedang Shift]
    K --> L[Admin Melakukan Penugasan / Assignment]
    L --> M[Kirim Notifikasi ke Teknisi]
    
    M --> N[Teknisi Menuju Lokasi Kerusakan]
    N --> O[Teknisi Mengerjakan Perbaikan]
    O --> P[Teknisi Ubah Status Menjadi SELESAI]
    P --> Q[Teknisi Upload Foto Hasil & Catatan]
    
    Q --> R[Kirim Notifikasi ke Admin & Pelapor]
    R --> S([Selesai: Laporan Ditutup])
```
