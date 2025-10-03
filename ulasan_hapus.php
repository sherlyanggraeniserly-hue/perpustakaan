<?php
include 'koneksi.php';

if (isset($_GET['id']) && isset($_GET['redirect'])) {
    $id = isset($_GET['id']) ? (int) $_GET['id'] : 0; // Pastikan ID valid
    $redirect = $_GET['redirect']; // Ambil halaman tujuan

    // Cek apakah ID valid
    if ($id <= 0) {
        echo "<script>alert('ID tidak valid!'); window.history.back();</script>";
        exit;
    }

    // Cek apakah ulasan ada dalam database
    $check = mysqli_query($koneksi, "SELECT * FROM ulasan WHERE id_ulasan = '$id'");
    if (!$check) {
        die("Query Error: " . mysqli_error($koneksi)); // Debugging
    }

    if (mysqli_num_rows($check) > 0) {
        // Jika ulasan ditemukan, lakukan penghapusan
        $query = mysqli_query($koneksi, "DELETE FROM ulasan WHERE id_ulasan = '$id'");

        if ($query) {
            $redirect_page = ($redirect == 'admin') ? '?page=ulasan' : '?page=peminjamanp';
            echo "<script>
                alert('Ulasan berhasil dihapus!');
                window.location.href='$redirect_page';
            </script>";
        } else {
            echo "<script>
                alert('Gagal menghapus ulasan: " . mysqli_error($koneksi) . "');
                window.history.back();
            </script>";
        }
    } else {
        echo "<script>
            alert('Ulasan tidak ditemukan!');
            window.history.back();
        </script>";
    }
} else {
    echo "<script>
        alert('ID tidak ditemukan!');
        window.history.back();
    </script>";
}
?>
