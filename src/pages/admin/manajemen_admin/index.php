<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}
// Hanya superadmin yang boleh akses
if ($_SESSION['role_admin'] !== 'superadmin') {
    header("Location: ../index.php");
    exit();
}
require '../../../config/koneksi.php';

$admins = mysqli_query($conn, "SELECT * FROM admin ORDER BY id_admin ASC");
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Manajemen Admin — Laundry</title>
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
            <strong>Daftar Admin</strong>
            <a href="create.php" class="btn btn-success btn-sm">+ Tambah Admin</a>
        </div>
        <div class="card-body p-0">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>No</th>
                        <th>Nama</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php $no = 1; while ($row = mysqli_fetch_assoc($admins)): ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $row['nama'] ?></td>
                        <td><?= $row['username'] ?></td>
                        <td>
                            <?php
                            $badge = [
                                'superadmin' => 'danger',
                                'kasir'      => 'primary',
                                'operator'   => 'secondary'
                            ];
                            ?>
                            <span class="badge bg-<?= $badge[$row['role']] ?>">
                                <?= ucfirst($row['role']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($row['is_aktif']): ?>
                                <span class="badge bg-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="update.php?id=<?= $row['id_admin'] ?>" class="btn btn-warning btn-sm">Edit</a>
                            <?php if ($row['id_admin'] !== $_SESSION['id_admin']): ?>
                                <a href="delete.php?id=<?= $row['id_admin'] ?>"
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Yakin hapus admin ini?')">Hapus</a>
                            <?php endif; ?>
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