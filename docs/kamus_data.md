\# Kamus Data — Sistem Manajemen Laundry



\## 1. Tabel `pelanggan`

| Kolom          | Tipe Data            | Keterangan                  |

|----------------|----------------------|-----------------------------|

| id\_pelanggan   | INT, AUTO\_INCREMENT  | Primary Key                 |

| nama           | VARCHAR(100)         | Nama lengkap pelanggan      |

| no\_hp          | VARCHAR(15), UNIQUE  | Nomor HP pelanggan          |

| alamat         | TEXT                 | Alamat lengkap pelanggan    |

| email          | VARCHAR(100), UNIQUE | Email pelanggan             |

| tanggal\_daftar | DATE                 | Tanggal pertama mendaftar   |

| status\_member  | ENUM                 | Status: aktif / tidak\_aktif |



\## 2. Tabel `layanan`

| Kolom              | Tipe Data           | Keterangan                                                  |

|--------------------|---------------------|-------------------------------------------------------------|

| id\_layanan         | INT, AUTO\_INCREMENT | Primary Key                                                 |

| nama\_layanan       | VARCHAR(100)        | Nama layanan                                                |

| jenis\_layanan      | ENUM                | cuci\_kering / cuci\_basah / setrika / dry\_cleaning / ekspres |

| harga\_per\_kg       | DECIMAL(10,2)       | Harga per kg, NULL jika tidak berlaku                       |

| harga\_per\_item     | DECIMAL(10,2)       | Harga per item, NULL jika tidak berlaku                     |

| estimasi\_waktu\_jam | INT                 | Estimasi selesai dalam jam                                  |

| is\_aktif           | BOOLEAN             | Status layanan aktif atau tidak                             |



\## 3. Tabel `admin`

| Kolom    | Tipe Data           | Keterangan                     |

|----------|---------------------|--------------------------------|

| id\_admin | INT, AUTO\_INCREMENT | Primary Key                    |

| nama     | VARCHAR(100)        | Nama lengkap admin             |

| username | VARCHAR(50), UNIQUE | Username untuk login           |

| password | VARCHAR(255)        | Password dalam format SHA2-256 |

| role     | ENUM                | superadmin / kasir / operator  |

| is\_aktif | BOOLEAN             | Status akun aktif atau tidak   |



\## 4. Tabel `transaksi`

| Kolom            | Tipe Data           | Keterangan                                                                      |

|------------------|---------------------|---------------------------------------------------------------------------------|

| id\_transaksi     | INT, AUTO\_INCREMENT | Primary Key                                                                     |

| id\_pelanggan     | INT                 | Foreign Key ke tabel pelanggan                                                  |

| id\_admin         | INT                 | Foreign Key ke tabel admin (kasir pencatat)                                     |

| tanggal\_masuk    | DATETIME            | Waktu order masuk                                                               |

| tanggal\_selesai  | DATETIME            | Waktu order selesai, NULL jika belum                                            |

| jenis\_pengiriman | ENUM                | antar\_jemput / ambil\_sendiri                                                    |

| alamat\_pickup    | TEXT                | Alamat penjemputan, NULL jika ambil sendiri                                     |

| total\_harga      | DECIMAL(10,2)       | Total harga keseluruhan order                                                   |

| status\_transaksi | ENUM                | antre / pencucian / pengeringan / setrika / siap\_diambil / selesai / dibatalkan |

| catatan          | TEXT                | Catatan tambahan, NULL jika tidak ada                                           |



\## 5. Tabel `detail\_transaksi`

| Kolom        | Tipe Data                         | Keterangan                                |

|--------------|-----------------------------------|-------------------------------------------|

| id\_detail    | INT, AUTO\_INCREMENT               | Primary Key                               |

| id\_transaksi | INT                               | Foreign Key ke tabel transaksi            |

| id\_layanan   | INT                               | Foreign Key ke tabel layanan              |

| nama\_item    | VARCHAR(100)                      | Nama item pakaian (ex: Kemeja, Celana)    |

| jumlah       | INT                               | Jumlah item                               |

| berat\_kg     | DECIMAL(5,2)                      | Berat dalam kg, NULL jika harga per item  |

| subtotal     | DECIMAL(10,2)                     | Total harga item ini                      |



\## 6. Tabel `pembayaran`

| Kolom              | Tipe Data                    | Keterangan                             |

|--------------------|------------------------------|----------------------------------------|

| id\_pembayaran      | INT, AUTO\_INCREMENT          | Primary Key                            |

| id\_transaksi       | INT, UNIQUE                  | Foreign Key ke tabel transaksi         |

| metode\_pembayaran  | ENUM                         | tunai / transfer\_bank / dompet\_digital |

| tanggal\_pembayaran | DATETIME                     | Waktu pembayaran dilakukan             |

| jumlah\_bayar       | DECIMAL(10,2)                | Jumlah uang yang dibayarkan            |

| kembalian          | DECIMAL(10,2)                | Uang kembalian                         |

| status\_pembayaran  | ENUM                         | belum\_bayar / lunas / sebagian         |



\## 7. Tabel `inventori` 

| Kolom        | Tipe Data               | Keterangan                                           |

|--------------|-------------------------|------------------------------------------------------|

| id\_inventori | INT, AUTO\_INCREMENT     | Primary Key                                          |

| nama\_barang  | VARCHAR(100)            | Nama bahan/barang                                    |

| jumlah\_stok  | DECIMAL(10,2)           | Jumlah stok saat ini                                 |

| satuan       | VARCHAR(20)             | Satuan: kg / liter / pcs                             |

| stok\_minimum | DECIMAL(10,2)           | Batas minimum stok sebelum peringatan                |

| status\_stok  | ENUM                    | tersedia / menipis / habis (auto-update via trigger) |

