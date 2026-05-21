-- ============================================================
-- DATABASE: Sistem Manajemen Laundry
-- Tim: Kelompok [ISI NAMA KELOMPOK]
-- Jurusan Ilmu Komputer - UNNES 2025/2026
-- Deskripsi: Database terintegrasi untuk operasional laundry
-- ============================================================

CREATE DATABASE IF NOT EXISTS laundry_db
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE laundry_db;

-- ------------------------------------------------------------
-- TABEL 1: pelanggan
-- ------------------------------------------------------------
CREATE TABLE pelanggan (
    id_pelanggan    INT             NOT NULL AUTO_INCREMENT,
    nama            VARCHAR(100)    NOT NULL,
    no_hp           VARCHAR(15)     NOT NULL UNIQUE,
    alamat          TEXT,
    email           VARCHAR(100)    UNIQUE,
    tanggal_daftar  DATE            NOT NULL DEFAULT (CURRENT_DATE),
    status_member   ENUM('aktif', 'tidak_aktif') NOT NULL DEFAULT 'aktif',
    PRIMARY KEY (id_pelanggan)
) ENGINE=InnoDB COMMENT='Data pelanggan terdaftar';

-- ------------------------------------------------------------
-- TABEL 2: layanan
-- ------------------------------------------------------------
CREATE TABLE layanan (
    id_layanan          INT             NOT NULL AUTO_INCREMENT,
    nama_layanan        VARCHAR(100)    NOT NULL,
    jenis_layanan       ENUM('cuci_kering', 'cuci_basah', 'setrika', 'dry_cleaning', 'ekspres') NOT NULL,
    harga_per_kg        DECIMAL(10,2)   DEFAULT NULL,
    harga_per_item      DECIMAL(10,2)   DEFAULT NULL,
    estimasi_waktu_jam  INT             NOT NULL COMMENT 'Estimasi dalam jam',
    is_aktif            BOOLEAN         NOT NULL DEFAULT TRUE,
    PRIMARY KEY (id_layanan)
) ENGINE=InnoDB COMMENT='Daftar layanan dan harga';

-- ------------------------------------------------------------
-- TABEL 3: admin
-- ------------------------------------------------------------
CREATE TABLE admin (
    id_admin    INT             NOT NULL AUTO_INCREMENT,
    nama        VARCHAR(100)    NOT NULL,
    username    VARCHAR(50)     NOT NULL UNIQUE,
    password    VARCHAR(255)    NOT NULL COMMENT 'Simpan dalam format hash',
    role        ENUM('superadmin', 'kasir', 'operator') NOT NULL DEFAULT 'kasir',
    is_aktif    BOOLEAN         NOT NULL DEFAULT TRUE,
    PRIMARY KEY (id_admin)
) ENGINE=InnoDB COMMENT='Akun pengelola sistem';

-- ------------------------------------------------------------
-- TABEL 4: transaksi
-- ------------------------------------------------------------
CREATE TABLE transaksi (
    id_transaksi        INT             NOT NULL AUTO_INCREMENT,
    id_pelanggan        INT             NOT NULL,
    id_admin            INT             NOT NULL COMMENT 'Kasir yang mencatat',
    tanggal_masuk       DATETIME        NOT NULL DEFAULT CURRENT_TIMESTAMP,
    tanggal_selesai     DATETIME        DEFAULT NULL,
    jenis_pengiriman    ENUM('antar_jemput', 'ambil_sendiri') NOT NULL DEFAULT 'ambil_sendiri',
    alamat_pickup       TEXT            DEFAULT NULL,
    total_harga         DECIMAL(10,2)   NOT NULL DEFAULT 0,
    status_transaksi    ENUM('antre', 'pencucian', 'pengeringan', 'setrika', 'siap_diambil', 'selesai', 'dibatalkan') NOT NULL DEFAULT 'antre',
    catatan             TEXT            DEFAULT NULL,
    PRIMARY KEY (id_transaksi),
    CONSTRAINT fk_transaksi_pelanggan
        FOREIGN KEY (id_pelanggan) REFERENCES pelanggan(id_pelanggan) ON UPDATE CASCADE,
    CONSTRAINT fk_transaksi_admin
        FOREIGN KEY (id_admin) REFERENCES admin(id_admin) ON UPDATE CASCADE
) ENGINE=InnoDB COMMENT='Header order laundry';

-- ------------------------------------------------------------
-- TABEL 5: detail_transaksi
-- ------------------------------------------------------------
CREATE TABLE detail_transaksi (
    id_detail       INT             NOT NULL AUTO_INCREMENT,
    id_transaksi    INT             NOT NULL,
    id_layanan      INT             NOT NULL,
    nama_item       VARCHAR(100)    NOT NULL COMMENT 'Contoh: Kemeja, Celana',
    jumlah          INT             NOT NULL DEFAULT 1,
    berat_kg        DECIMAL(5,2)    DEFAULT NULL COMMENT 'Isi jika harga per kg',
    subtotal        DECIMAL(10,2)   NOT NULL,
    PRIMARY KEY (id_detail),
    CONSTRAINT fk_detail_transaksi
        FOREIGN KEY (id_transaksi) REFERENCES transaksi(id_transaksi) ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_detail_layanan
        FOREIGN KEY (id_layanan) REFERENCES layanan(id_layanan) ON UPDATE CASCADE
) ENGINE=InnoDB COMMENT='Rincian item per transaksi';

-- ------------------------------------------------------------
-- TABEL 6: pembayaran
-- ------------------------------------------------------------
CREATE TABLE pembayaran (
    id_pembayaran       INT             NOT NULL AUTO_INCREMENT,
    id_transaksi        INT             NOT NULL UNIQUE,
    metode_pembayaran   ENUM('tunai', 'transfer_bank', 'dompet_digital') NOT NULL,
    tanggal_pembayaran  DATETIME        DEFAULT NULL,
    jumlah_bayar        DECIMAL(10,2)   NOT NULL,
    kembalian           DECIMAL(10,2)   NOT NULL DEFAULT 0,
    status_pembayaran   ENUM('belum_bayar', 'lunas', 'sebagian') NOT NULL DEFAULT 'belum_bayar',
    PRIMARY KEY (id_pembayaran),
    CONSTRAINT fk_pembayaran_transaksi
        FOREIGN KEY (id_transaksi) REFERENCES transaksi(id_transaksi) ON UPDATE CASCADE
) ENGINE=InnoDB COMMENT='Data pembayaran per transaksi';

-- ------------------------------------------------------------
-- TABEL 7: inventori
-- ------------------------------------------------------------
CREATE TABLE inventori (
    id_inventori    INT             NOT NULL AUTO_INCREMENT,
    nama_barang     VARCHAR(100)    NOT NULL,
    jumlah_stok     DECIMAL(10,2)   NOT NULL DEFAULT 0,
    satuan          VARCHAR(20)     NOT NULL COMMENT 'kg, liter, pcs, dll',
    stok_minimum    DECIMAL(10,2)   NOT NULL DEFAULT 5 COMMENT 'Batas peringatan stok habis',
    status_stok     ENUM('tersedia', 'menipis', 'habis') NOT NULL DEFAULT 'tersedia',
    PRIMARY KEY (id_inventori)
) ENGINE=InnoDB COMMENT='Stok bahan operasional laundry';

-- ============================================================
-- TRIGGER: Auto-update status_stok inventori
-- ============================================================
DELIMITER $$
CREATE TRIGGER trg_update_status_stok
BEFORE UPDATE ON inventori
FOR EACH ROW
BEGIN
    IF NEW.jumlah_stok <= 0 THEN
        SET NEW.status_stok = 'habis';
    ELSEIF NEW.jumlah_stok <= NEW.stok_minimum THEN
        SET NEW.status_stok = 'menipis';
    ELSE
        SET NEW.status_stok = 'tersedia';
    END IF;
END$$
DELIMITER ;

-- ============================================================
-- SEED DATA
-- ============================================================

INSERT INTO layanan (nama_layanan, jenis_layanan, harga_per_kg, harga_per_item, estimasi_waktu_jam) VALUES
('Cuci + Kering Regular',  'cuci_kering',  7000.00,  NULL,     48),
('Cuci + Kering Ekspres',  'ekspres',      12000.00, NULL,     6),
('Setrika Saja',           'setrika',      NULL,     3000.00,  24),
('Dry Cleaning',           'dry_cleaning', NULL,     25000.00, 72),
('Cuci Basah',             'cuci_basah',   5000.00,  NULL,     48);

INSERT INTO admin (nama, username, password, role) VALUES
('Super Admin',  'superadmin', SHA2('admin123', 256), 'superadmin'),
('Kasir Utama',  'kasir1',     SHA2('kasir123', 256), 'kasir'),
('Operator 1',   'operator1',  SHA2('oper123',  256), 'operator');

INSERT INTO inventori (nama_barang, jumlah_stok, satuan, stok_minimum) VALUES
('Deterjen Bubuk',   50.00,  'kg',    10.00),
('Pewangi Pakaian',  20.00,  'liter',  5.00),
('Plastik Packing',  500.00, 'pcs',  100.00),
('Softener',         15.00,  'liter',  3.00);

INSERT INTO pelanggan (nama, no_hp, alamat, email) VALUES
('Budi Santoso',  '081234567890', 'Jl. Merdeka No. 1, Semarang',   'budi@email.com'),
('Siti Rahayu',   '082345678901', 'Jl. Pahlawan No. 5, Semarang',  'siti@email.com'),
('Andi Wijaya',   '083456789012', 'Jl. Sudirman No. 10, Semarang', 'andi@email.com');