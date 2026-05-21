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

// Tidak boleh hapus diri sendiri
if ($id == $_SESSION['id_admin']) {
    header("Location: index.php");
    exit();
}

$stmt = $conn->prepare("DELETE FROM admin WHERE id_admin = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: index.php");
exit();
?>