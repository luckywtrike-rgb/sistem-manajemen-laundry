<?php
session_start();
require '../../config/koneksi.php';

if (isset($_POST['username']) && isset($_POST['password'])) {
    
    $username = $_POST['username'];
    $password = SHA1($_POST['password']);
    
    // Gunakan prepared statement untuk keamanan
    $stmt = $conn->prepare("SELECT * FROM admin WHERE username = ? AND password = SHA2(?, 256) AND is_aktif = 1");
    $stmt->bind_param("ss", $username, $_POST['password']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();

        // Simpan data admin ke session
        $_SESSION['admin']          = true;
        $_SESSION['id_admin']       = $data['id_admin'];
        $_SESSION['nama_admin']     = $data['nama'];
        $_SESSION['role_admin']     = $data['role'];

        header("Location: index.php");
        exit();
    } else {
        header("Location: login.php?error=1");
        exit();
    }
} else {
    header("Location: login.php");
    exit();
}
?>