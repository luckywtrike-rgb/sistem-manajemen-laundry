<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}
require '../../../config/koneksi.php';

$id = $_GET['id'];

// Ambil data layanan yang akan diedit
$stmt = $conn->prepare("SELECT * FROM layanan WHERE id_layanan = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    header("Location: index.php");
    exit();
}

// Proses update
if (isset($_POST['submit'])) {
    $nama_layanan       = $_POST['nama_layanan'];
    $jenis_layanan      = $_POST['jenis_layanan'];
    $harga_per_kg       = !empty($_POST['harga_per_kg']) ? $_POST['harga_per_kg'] : NULL;
    $harga_per_item     = !empty($_POST['harga_per_item']) ? $_POST['harga_per_item'] : NULL;
    $estimasi_waktu_jam = $_POST['estimasi_waktu_jam'];
    $is_aktif           = $_POST['is_aktif'];

    $stmt = $conn->prepare("UPDATE layanan SET nama_layanan=?, jenis_layanan=?, harga_per_kg=?, harga_per_item=?, estimasi_waktu_jam=?, is_aktif=? WHERE id_layanan=?");
    $stmt->bind_param("ssddiis", $nama_layanan, $jenis_layanan, $harga_per_kg, $harga_per_item, $estimasi_waktu_jam, $is_aktif, $id);

    if ($stmt->execute()) {
        header("Location: index.php");
        exit();
    } else {
        $error = "Gagal mengupdate data!";
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Edit Layanan — Laundry</title>
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
                    <strong>Edit Layanan</strong>
                </div>
                <div class="card-body">

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label>Nama Layanan</label>
                            <input type="text" name="nama_layanan" class="form-control"
                                   value="<?= $row['nama_layanan'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Jenis Layanan</label>
                            <select name="jenis_layanan" class="form-control" required>
                                <option value="cuci_kering"  <?= $row['jenis_layanan'] == 'cuci_kering'  ? 'selected' : '' ?>>Cuci Kering</option>
                                <option value="cuci_basah"   <?= $row['jenis_layanan'] == 'cuci_basah'   ? 'selected' : '' ?>>Cuci Basah</option>
                                <option value="setrika"      <?= $row['jenis_layanan'] == 'setrika'      ? 'selected' : '' ?>>Setrika</option>
                                <option value="dry_cleaning" <?= $row['jenis_layanan'] == 'dry_cleaning' ? 'selected' : '' ?>>Dry Cleaning</option>
                                <option value="ekspres"      <?= $row['jenis_layanan'] == 'ekspres'      ? 'selected' : '' ?>>Ekspres</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Harga per Kg (kosongkan jika tidak ada)</label>
                            <input type="number" name="harga_per_kg" class="form-control"
                                   value="<?= $row['harga_per_kg'] ?>">
                        </div>
                        <div class="mb-3">
                            <label>Harga per Item (kosongkan jika tidak ada)</label>
                            <input type="number" name="harga_per_item" class="form-control"
                                   value="<?= $row['harga_per_item'] ?>">
                        </div>
                        <div class="mb-3">
                            <label>Estimasi Waktu (jam)</label>
                            <input type="number" name="estimasi_waktu_jam" class="form-control"
                                   value="<?= $row['estimasi_waktu_jam'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select name="is_akt