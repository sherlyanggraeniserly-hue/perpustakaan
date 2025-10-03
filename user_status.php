<?php
include 'koneksi.php'; // Pastikan file koneksi ada

if (!isset($_SESSION['user']) || $_SESSION['user']['level'] != 'petugas') {
    echo "<script>alert('Akses ditolak!'); window.location.href='index.php';</script>";
    exit;
}

if (isset($_GET['id']) && isset($_GET['status'])) {
    $id_user = intval($_GET['id']);
    $status = ($_GET['status'] == 'aktif') ? 'aktif' : 'pasif';

    $query = "UPDATE user SET status = '$status' WHERE id_user = $id_user";
    if (mysqli_query($koneksi, $query)) {
        echo "<script>alert('Status user berhasil diperbarui!'); window.location.href='?page=user_kelola';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui status user.'); window.location.href='?page=user_kelola';</script>";
    }
} else {
    echo "<script>alert('Data tidak valid!'); window.location.href='?page=user_kelola';</script>";
}
