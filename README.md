\# 🧺 Sistem Manajemen Laundry

> Projek Akhir Modul 5 — Sistem Basis Data  

> Jurusan Ilmu Komputer | Universitas Negeri Semarang 2025/2026



\## 👥 Anggota Kelompok

| No | Nama | NIM        | Tugas                                       |

|--- |------|------------|---------------------------------------------|

| 1  | Falen| 2504140001 | Database Design, Backend Core, GitHub Setup |

| 2  |Nasywa| 2504140014 | CRUD Transaksi \& Pelanggan                  |

| 3  | Dhiya| 2504140031 | Inventori \& Laporan Keuangan                |

| 4  | Abdul| 2504140051 | Halaman Pelanggan \& Tracking                |



\## 📋 Deskripsi

Sistem informasi berbasis web untuk membantu operasional jasa laundry secara digital.

Fitur utama meliputi manajemen transaksi, tracking status cucian secara real-time,

manajemen inventori bahan, dan laporan keuangan.



\## 🛠️ Teknologi

\- \*\*Backend\*\*: PHP 8.x

\- \*\*Database\*\*: MySQL (via XAMPP)

\- \*\*Frontend\*\*: Bootstrap 4.6

\- \*\*Server Lokal\*\*: XAMPP



\## 📁 Struktur Folder

sistem-manajemen-laundry/

├── database/

│   └── laundry\_db.sql       # DDL + Seed Data + Trigger

├── docs/

│   └── kamus\_data.md        # Dokumentasi tabel dan kolom

├── src/

│   ├── config/

│   │   └── koneksi.php      # Koneksi database

│   ├── assets/              # CSS, JS, gambar

│   └── pages/

│       ├── admin/           # Halaman pengelola

│       └── pelanggan/       # Halaman pelanggan

└── README.md



\## 🚀 Cara Menjalankan

1\. Clone repository ini

2\. Pastikan XAMPP sudah terinstall dan aktifkan Apache + MySQL

3\. Import file `database/laundry\_db.sql` ke phpMyAdmin

4\. Salin folder proyek ke `C:/xampp/htdocs/laundry/`

5\. Akses `http://localhost/laundry/`



\## 🗄️ Struktur Database

| Tabel                  | Deskripsi                     |

|------------------------|-------------------------------|

| pelanggan              | Data pelanggan terdaftar      |

| layanan                | Daftar layanan dan Harga      |

| transaksi              | Header order laundry          |

| detail\_transaksi       | Rincian item per transaksi    |

| pembayaran             | Data pembayaran per transaksi |

| inventori              | Stok bahan operasional        |

| admin                  | Akun pengelola sistem         |

