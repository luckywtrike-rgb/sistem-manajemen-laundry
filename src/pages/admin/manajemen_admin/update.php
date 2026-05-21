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

$id = $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM admin WHERE id_admin = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['submit'])) {
    $nama     = $_POST['nama'];
    $username = $_POST['username'];
    $role     = $_POST['role'];
    $is_aktif = $_POST['is_aktif'];

    // Jika password diisi, update password juga
    if (!empty($_POST['password'])) {
        $stmt = $conn->prepare("UPDATE admin SET nama=?, username=?, password=SHA2(?,256), role=?, is_aktif=? WHERE id_admin=?");
        $stmt->bind_param("ssssii", $nama, $username, $_POST['password'], $role, $is_aktif, $id);
    } else {
        $stmt = $conn->prepare("UPDATE admin SET nama=?, username=?, role=?, is_aktif=? WHERE id_admin=?");
        $stmt->bind_param("sssii", $nama, $username, $role, $is_aktif, $id);
    }

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
    <title>Edit Admin — Laundry</title>
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
                    <strong>Edit Admin</strong>
                </div>
                <div class="card-body">

                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label>Nama Lengkap</label>
                            <input type="text" name="nama" class="form-control"
                                   value="<?= $row['nama'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control"
                                   value="<?= $row['username'] ?>" required>
                        </div>
                        <div class="mb-3">
                            <label>Password Baru <small class="text-muted">(kosongkan jika tidak ingin mengubah)</small></label>
                            <input type="password" name="password" class="form-control"
                                   placeholder="Isi jika ingin mengubah password">
                        </div>
                        <div class="mb-3">
                            <label>Role</label>
                            <select name="role" class="form-control" required>
                                <option value="superadmin" <?= $row['role'] == 'superadmin' ? 'selected' : '' ?>>Super Admin</option>
                                <option value="kasir"      <?= $row['role'] == 'kasir'      ? 'selected' : '' ?>>Kasir</option>
                                <option value="operator"   <?= $row['role'] == 'operator'   ? 'selected' : '' ?>>Operator</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label>Status</label>
                            <select name="is_aktif" class="form-control">
                                <option value="1" <?= $row['is_aktif'] == 1 ? 'selected' : '' ?>>Aktif</option>
                                <option value="0" <?= $row['is_aktif'] == 0 ? 'selected' : '' ?>>Nonaktif</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="index.php" class="btn btn-secondary">Batal</a>
                            <button type="submit" name="submit" class="btn btn-warning">Update</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>