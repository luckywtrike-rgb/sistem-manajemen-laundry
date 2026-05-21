<?php
session_start();

// Jika sudah login, langsung redirect ke dashboard
if (isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — Sistem Manajemen Laundry</title>
    <link rel="stylesheet" href="../../assets/bootstrap/css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f2f5;
        }
        .login-card {
            margin-top: 100px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .login-header {
            background-color: #198754;
            color: white;
            border-radius: 12px 12px 0 0;
            padding: 24px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-4">
            <div class="card login-card">
                <div class="login-header">
                    <h4>🧺 Laundry Admin</h4>
                    <small>Sistem Manajemen Laundry</small>
                </div>
                <div class="card-body p-4">

                    <?php if (isset($_GET['error'])): ?>
                        <div class="alert alert-danger">
                            Username atau password salah!
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="proses_login.php">
                        <div class="form-group mb-3">
                            <label>Username</label>
                            <input type="text" name="username" class="form-control"
                                   placeholder="Masukkan username" required autofocus>
                        </div>
                        <div class="form-group mb-3">
                            <label>Password</label>
                            <input type="password" name="password" class="form-control"
                                   placeholder="Masukkan password" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100">
                            Login
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>