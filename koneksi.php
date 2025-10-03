<?php
// Cek apakah session sudah aktif sebelum memulai sesi baru
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Koneksi ke database (tanpa mengubah struktur yang sudah ada)
$koneksi = mysqli_connect('localhost', 'root', '', 'perpus');

// Cek koneksi database
if (!$koneksi) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
?>
