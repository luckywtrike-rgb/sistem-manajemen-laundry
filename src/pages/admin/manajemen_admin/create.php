<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}
if ($_SESSION['role_admin'] !== 'superadmin') {
    header("Location: ../index.php");
    exit();
}
require '../../../config/koneksi.php';

if (isset($_POST['submit'])) {
    $nama     = $_POST['nama'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role     = $_POST['role'];

    // Cek username sudah ada atau belum
    $cek = $conn->prepare("SELECT id_admin FROM admin WHERE username = ?");
    $cek->bind_param("s", $username);
    $cek->execute();
    $cek->store_result();

    if ($cek->num_rows > 0) {
        $error = "Username sudah digunakan!";
    } else {
        $stmt = $conn->prepare("INSERT INTO admin (nama, username, password, role) VALUES (?, ?, SHA2(?, 256), ?)");
        $stmt->bind_param("ssss", $nama, $username, $password, $role);

        if ($stmt->execute()) {
            header("Location: index.php");
            exit();
        } else {
            $error = "Gagal menyimpan data!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Tambah Admin — Laundry</title>
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
                    <strong>Tambah Admin Baru</strong>
                </div>
                <div class="card-body">

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control"
                                   placeholder="ex: Budi Santoso" required>
                        </div>
                        <div class="mb-3">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control"
                                   placeholder="ex: kasir2" required>
                        </div>
                        <div class="mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control"
                                   placeholder="Masukkan password" required>
                        </div>
                        <div class="mb-3">
                            <label>Role</label>
                            <select name="role" class="form-control" required>
                                <option value="">-- Pilih Role --</option>
                                <option value="superadmin">Super Admin</option>
                                <option value="kasir">Kasir</option>
                                <option value="operator">Operator</option>
                            </select>
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