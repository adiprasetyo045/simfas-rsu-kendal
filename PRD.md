# Product Requirements Document (PRD)

**Project Name:** Sistem Informasi Manajemen Fasilitas (SIMFAS)
**Client/Institution:** RSU Muhammadiyah Kendal
**Date:** 3 Agustus 2026

---

## 1. Problem Statement
Di lingkungan rumah sakit, kelancaran operasional fasilitas (medis maupun non-medis) sangat krusial. Saat ini, pelaporan kerusakan fasilitas (seperti AC mati, lampu putus, atau alat medis rusak) masih dilakukan secara manual menggunakan kertas formulir atau pesan di grup WhatsApp. Hal ini menyebabkan:
- Sulitnya melacak status perbaikan secara *real-time* (apakah sudah dikerjakan atau belum).
- Tidak ada skala prioritas pengerjaan yang jelas bagi tim teknisi.
- Riwayat perbaikan suatu alat tidak tercatat dengan baik di database, menyulitkan evaluasi kualitas fasilitas.
- Laporan sering kali tenggelam atau terlewat di grup *chat*.

## 2. Goals
- **Mendigitalisasi Proses Pelaporan:** Membangun platform terpusat berbasis web untuk melaporkan segala jenis kerusakan fasilitas.
- **Transparansi Status:** Memberikan kemudahan bagi pelapor (perawat/staf) untuk melacak (*tracking*) status laporannya dari awal hingga tuntas.
- **Efisiensi Kinerja Teknisi:** Memudahkan teknisi menerima surat tugas dan melaporkan hasil perbaikan secara langsung dari lapangan.
- **Rekapitulasi Data:** Memudahkan manajemen (Admin/Supervisor) dalam memonitor kinerja IPSRS dan menarik laporan evaluasi bulanan (PDF/Excel) dengan akurat.

## 3. Target User
1. **Admin (Admin IPSRS / IT)**: Mengelola sistem inti, data master, memverifikasi validitas laporan, dan menugaskan tiket ke teknisi yang tersedia.
2. **Teknisi (Staf Pemeliharaan)**: Pekerja lapangan yang menerima tugas dan mengeksekusi perbaikan kerusakan fasilitas.
3. **Pelapor (Perawat, Dokter, Staf Unit)**: Pengguna ruangan/fasilitas yang menemukan kerusakan dan bertugas melaporkannya ke sistem.
4. **Supervisor (Manajemen / Kepala Instalasi)**: Pengguna dengan akses pantau (*view-only*) untuk memonitor dasbor secara keseluruhan dan mencetak laporan akhir.

## 4. User Stories
- **Sebagai Pelapor**, saya ingin membuat laporan kerusakan dengan bukti foto, agar teknisi tahu persis tingkat kerusakan alat tersebut.
- **Sebagai Pelapor**, saya ingin melihat status *real-time* laporan saya, agar saya tidak perlu mondar-mandir bertanya ke ruang IPSRS.
- **Sebagai Admin**, saya ingin memverifikasi laporan masuk dan membagi tugas ke teknisi yang sedang kosong, agar pengerjaan cepat ditangani.
- **Sebagai Admin**, saya ingin mencetak rekap laporan bulanan dengan satu klik, agar saya bisa melaporkannya ke direktur rumah sakit tanpa membuat laporan manual.
- **Sebagai Teknisi**, saya ingin melihat daftar tugas perbaikan saya, agar saya tahu jadwal dan lokasi ruangan yang harus saya perbaiki hari ini.
- **Sebagai Teknisi**, saya ingin meng-*update* status (Sedang Diperbaiki/Menunggu Sparepart/Selesai) dari HP saya, agar progres pekerjaan saya transparan.

## 5. Functional Requirements
- **Manajemen Akun (Authentication):** Sistem memiliki fitur *Login*, *Logout*, dan pembagian hak akses (*Role-based access control*).
- **Master Data (Admin-only):** Admin dapat melakukan operasi CRUD (Create, Read, Update, Delete) pada data Pengguna, Ruangan, Kategori Kerusakan, dan Fasilitas.
- **Pembuatan Tiket (Ticketing):** Pelapor dapat mengisi *form* laporan kerusakan (memilih ruangan, nama fasilitas, prioritas, deskripsi) dan mengunggah foto.
- **Dispatching & Penugasan:** Admin dapat me-*review* daftar tiket baru, menolak tiket (bila tidak valid), dan menugaskan tiket ke Teknisi beserta catatan estimasi waktu.
- **Update Status:** Teknisi dapat mengubah *state* tiket (Diproses ➔ Dalam Perbaikan ➔ Menunggu Sparepart ➔ Selesai) dan memberikan catatan perbaikan.
- **Dashboard & Statistik:** Sistem menampilkan visualisasi ringkasan laporan (total laporan, status selesai, status diproses) berupa angka dan grafik *pie/bar*.
- **Sistem Notifikasi:** Tersedia fitur lonceng notifikasi (di dalam *header* aplikasi) untuk memberi tahu Admin saat ada tiket baru, atau memberi tahu Pelapor saat tiketnya diperbarui.
- **Export Laporan:** Sistem mampu melakukan penyaringan (*filter*) data berdasarkan tanggal (Tanggal Awal - Akhir) dan mengekspornya menjadi format dokumen PDF dan CSV (Excel).

## 6. Non-Functional Requirements
- **Responsive Design:** *User Interface* (UI) harus responsif; nyaman digunakan di layar PC (untuk Admin) dan mudah diakses via layar sentuh HP (untuk Teknisi/Pelapor di lapangan).
- **Security:** Kata sandi harus dienkripsi menggunakan *Bcrypt Hash*. Pengguna tidak bisa mengakses URL menu yang di luar hak akses rolenya (dihadang *Middleware*).
- **Performance:** Waktu memuat (*load time*) sistem diusahakan tidak lebih dari 3 detik pada koneksi standar rumah sakit.
- **Availability:** Aplikasi di-*hosting* pada layanan awan (*cloud/shared hosting*) yang dapat diakses 24/7 melalui jaringan intranet maupun internet.

## 7. Scope
**In-Scope (Termasuk dalam lingkup pengembangan saat ini):**
- Modul *Authentication* & *Role Management*.
- Modul Master Data.
- Modul Tiket Laporan (dari tahap lapor hingga selesai).
- Dasbor analitik sederhana dan Ekspor Laporan (*Reporting*).
- Aplikasi berbasis Web (*Web-based Application*).

**Out-of-Scope (Tidak termasuk dalam lingkup pengembangan saat ini / Pengembangan Lanjutan):**
- Fitur *Live Chat* di dalam aplikasi antara Pelapor dan Teknisi.
- Aplikasi dalam bentuk *Mobile Native* (Android APK / iOS) yang dipublikasikan di App Store.
- Integrasi/Jembatan dengan Sistem Informasi Manajemen Rumah Sakit (SIMRS) untuk sinkronisasi data perawat/dokter.
