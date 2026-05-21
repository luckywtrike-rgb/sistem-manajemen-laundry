<?php
session_start();

// Proteksi halaman, harus login dulu
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit();
}

require '../../config/koneksi.php';

// Ambil data ringkasan untuk dashboard
$total_pelanggan  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM pelanggan"))['total'];
$total_transaksi  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi WHERE DATE(tanggal_masuk) = CURDATE()"))['total'];
$transaksi_aktif  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM transaksi WHERE status_transaksi NOT IN ('selesai', 'dibatalkan')"))['total'];
$stok_menipis     = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM inventori WHERE status_stok IN ('menipis', 'habis')"))['total'];

// Ambil pemasukan hari ini
$pemasukan = mysqli_fetch_assoc(mysqli_query($conn, "
    SELECT COALESCE(SUM(jumlah_bayar), 0) as total 
    FROM pembayaran 
    WHERE DATE(tanggal_pembayaran) = CURDATE() 
    AND status_pembayaran = 'lunas'
"))['total'];

// Ambil transaksi terbaru
$transaksi_terbaru = mysqli_query($conn, "
    SELECT t.id_transaksi, p.nama, t.status_transaksi, t.tanggal_masuk, t.total_harga
    FROM transaksi t
    JOIN pelanggan p ON t.id_pelanggan = p.id_pelanggan
    ORDER BY t.tanggal_masuk DESC
    LIMIT 5
");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — Sistem Manajemen Laundry</title>
    <link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.min.css">
    <style>
        body { background-color: #f0f2f5; }
        .navbar { background-color: #198754 !important; }
        .card-stat {
            border-radius: 12px;
            border: none;
            box-shadow: 0 2px 10px rgba(0,0,0,0.08);
        }
        .badge-status-antre        { background-color: #6c757d; }
        .badge-status-pencucian    { background-color: #0dcaf0; }
        .badge-status-pengeringan  { background-color: #0d6efd; }
        .badge-status-setrika      { background-color: #fd7e14; }
        .badge-status-siap_diambil { background-color: #198754; }
        .badge-status-selesai      { background-color: #198754; }
        .badge-status-dibatalkan   { background-color: #dc3545; }
    </style>
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-dark mb-4">
    <div class="container">
        <span class="navbar-brand">🧺 Laundry Admin</span>
        <div>
            <span class="text-white me-3">Halo, <?= $_SESSION['nama_admin'] ?></span>
            <a href="logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container">

    <!-- KARTU STATISTIK -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card card-stat p-3">
                <small class="text-muted">Total Pelanggan</small>
                <h3 class="text-success"><?= $total_pelanggan ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat p-3">
                <small class="text-muted">Transaksi Hari Ini</small>
                <h3 class="text-primary"><?= $total_transaksi ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat p-3">
                <small class="text-muted">Order Aktif</small>
                <h3 class="text-warning"><?= $transaksi_aktif ?></h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card card-stat p-3">
                <small class="text-muted">Pemasukan Hari Ini</small>
                <h3 class="text-success">Rp <?= number_format($pemasukan, 0, ',', '.') ?></h3>
            </div>
        </div>
    </div>

    <!-- PERINGATAN STOK -->
    <?php if ($stok_menipis > 0): ?>
    <div class="alert alert-warning">
        ⚠️ Ada <strong><?= $stok_menipis ?> item</strong> stok yang menipis atau habis!
        <a href="inventori/index.php" class="alert-link">Cek Inventori</a>
    </div>
    <?php endif; ?>

    <!-- MENU NAVIGASI -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <a href="pelanggan/index.php" class="btn btn-outline-success w-100 p-3">
                👤 Pelanggan
            </a>
        </div>
        <div class="col-md-3">
            <a href="transaksi/index.php" class="btn btn-outline-primary w-100 p-3">
                📋 Transaksi
            </a>
        </div>
        <div class="col-md-3">
            <a href="inventori/index.php" class="btn btn-outline-warning w-100 p-3">
                📦 Inventori
            </a>
        </div>
        <div class="col-md-3">
            <a href="laporan/harian.php" class="btn btn-outline-info w-100 p-3">
                📊 Laporan
            </a>
        </div>
    </div>

    <!-- TABEL TRANSAKSI TERBARU -->
    <div class="card card-stat">
        <div class="card-header bg-white">
            <strong>Transaksi Terbaru</strong>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Pelanggan</th>
                        <th>Tanggal Masuk</th>
                        <th>Total</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = mysqli_fetch_assoc($transaksi_terbaru)): ?>
                    <tr>
                        <td>#<?= $row['id_transaksi'] ?></td>
                        <td><?= $row['nama'] ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($row['tanggal_masuk'])) ?></td>
                        <td>Rp <?= number_format($row['total_harga'], 0, ',', '.') ?></td>
                        <td>
                            <span class="badge badge-status-<?= $row['status_transaksi'] ?>">
                                <?= ucfirst(str_replace('_', ' ', $row['status_transaksi'])) ?>
                            </span>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>
</body>
</html>