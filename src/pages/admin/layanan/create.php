<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}
require '../../../config/koneksi.php';

if (isset($_POST['submit'])) {
    $nama_layanan       = $_POST['nama_layanan'];
    $jenis_layanan      = $_POST['jenis_layanan'];
    $harga_per_kg       = !empty($_POST['harga_per_kg']) ? $_POST['harga_per_kg'] : NULL;
    $harga_per_item     = !empty($_POST['harga_per_item']) ? $_POST['harga_per_item'] : NULL;
    $estimasi_waktu_jam = $_POST['estimasi_waktu_jam'];

    $stmt = $conn->prepare("INSERT INTO layanan (nama_layanan, jenis_layanan, harga_per_kg, harga_per_item, estimasi_waktu_jam) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("ssddi", $nama_layanan, $jenis_layanan, $harga_per_kg, $harga_per_item, $estimasi_waktu_jam);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Gagal menyimpan data!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Layanan — Laundry</title>
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
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <strong>Tambah Layanan Baru</strong>
                </div>
                <div class="card-body">

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label>Nama Layanan</label>
                            <input type="text" name="nama_layanan" class="form-control"
                                   placeholder="ex: Cuci + Kering Regular" required>
                        </div>
                        <div class="mb-3">
                            <label>Jenis Layanan</label>
                            <select name="jenis_layanan" class="form-control" required>
                                <option value="">-- Pilih Jenis --</option>
                                <option value="cuci_kering">Cuci Kering</option>
                                <option value="cuci_basah">Cuci Basah</option>
                                <option value="setrika">Setrika</option>
                                <option value="dry_cleaning">Dry Cleaning</option>
                                <option value="ekspres">Ekspres</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Harga per Kg (kosongkan jika tidak ada)</label>
                            <input type="number" name="harga_per_kg" class="form-control"
                                   placeholder="ex: 7000">
                        </div>
                        <div class="mb-3">
                            <label>Harga per Item (kosongkan jika tidak ada)</label>
                            <input type="number" name="harga_per_item" class="form-control"
                                   placeholder="ex: 3000">
                        </div>
                        <div class="mb-3">
                            <label>Estimasi Waktu (jam)</label>
                            <input type="number" name="estimasi_waktu_jam" class="form-control"
                                   placeholder="ex: 48" required>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="index.php" class="btn btn-secondary">Batal</a>
                            <button type="submit" name="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>