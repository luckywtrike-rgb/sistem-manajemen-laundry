<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}
require '../../../config/koneksi.php';

$layanan = mysqli_query($conn, "SELECT * FROM layanan ORDER BY id_layanan ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Layanan — Laundry</title>
    <link rel="stylesheet" href="../../../assets/bootstrap/css/bootstrap.min.css">
    <style>
        body { background-color: #f0f2f5; }
        .navbar { background-color: #198754 !important; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark mb-4">
    <div class="container">
        <span class="navbar-brand">🧺 Laundry Admin</span>
        <div>
            <a href="../index.php" class="btn btn-outline-light btn-sm me-2">Dashboard</a>
            <a href="../logout.php" class="btn btn-outline-light btn-sm">Logout</a>
        </div>
    </div>
</nav>

<div class="container">
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <strong>Daftar Layanan</strong>
            <a href="create.php" class="btn btn-success btn-sm">+ Tambah Layanan</a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama Layanan</th>
                        <th>Jenis</th>
                        <th>Harga/Kg</th>
                        <th>Harga/Item</th>
                        <th>Estimasi</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($layanan)): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['nama_layanan'] ?></td>
                        <td><?= ucfirst(str_replace('_', ' ', $row['jenis_layanan'])) ?></td>
                        <td><?= $row['harga_per_kg'] ? 'Rp ' . number_format($row['harga_per_kg'], 0, ',', '.') : '-' ?></td>
                        <td><?= $row['harga_per_item'] ? 'Rp ' . number_format($row['harga_per_item'], 0, ',', '.') : '-' ?></td>
                        <td><?= $row['estimasi_waktu_jam'] ?> jam</td>
                        <td>
                            <?php if ($row['is_aktif']): ?>
                                <span class="badge bg-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="update.php?id=<?= $row['id_layanan'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete.php?id=<?= $row['id_layanan'] ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Yakin hapus layanan ini?')">Hapus</a>
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