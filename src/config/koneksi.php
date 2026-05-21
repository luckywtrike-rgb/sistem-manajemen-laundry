<?php
// ============================================================
// FILE: koneksi.php
// Deskripsi: File koneksi antara aplikasi dan database MySQL
// ============================================================

$host     = 'localhost';
$username = 'root';
$password = '';
$db_name  = 'laundry_db';

$conn = new mysqli($host, $username, $password, $db_name);

if (!$conn) {
    die("Koneksi Gagal: " . mysqli_connect_error());
}

// Set charset agar karakter Indonesia tampil dengan benar
mysqli_set_charset($conn, 'utf8mb4');
?>