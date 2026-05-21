<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: ../login.php");
    exit();
}
require '../../../config/koneksi.php';

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM layanan WHERE id_layanan = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: index.php");
exit();
?>