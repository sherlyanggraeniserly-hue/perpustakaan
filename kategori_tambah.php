<?php
include "koneksi.php"; // Pastikan file koneksi sudah benar

if (isset($_POST['submit'])) {
    $kategori = trim($_POST['kategori']);

    // Cek apakah kategori sudah ada di database
    $cek_kategori = mysqli_query($koneksi, "SELECT * FROM kategori WHERE kategori = '$kategori'");
    if (mysqli_num_rows($cek_kategori) > 0) {
        echo '<script>
                alert("Kategori sudah ada! Silakan masukkan nama kategori lain.");
                window.history.back();
              </script>';
        exit();
    }

    // Jika belum ada, tambahkan ke database
    $query = mysqli_query($koneksi, "INSERT INTO kategori (kategori) VALUES ('$kategori')");

    if ($query) {
        echo '<script>
                alert("Tambah kategori berhasil!");
                window.location.href="?page=kategori"; 
              </script>';
        exit();
    } else {
        echo '<script>alert("Tambah data gagal. Silakan coba lagi!");</script>';
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori Buku</title>
    <link rel="stylesheet" href="css/style_kategori.css">
</head>
<body>

<div class="container mt-4">
    <div class="dashboard-header">
        <h2><i class="fas fa-book-open"></i> Tambah Kategori Buku</h2>
        <p class="mb-0">Lengkapi informasi kategori untuk menambahkan ke daftar buku.</p>
    </div>
    <div class="card">
        <div class="card-body">
            <form method="post">
                <div class="row mb-3">
                    <div class="col-md-2 required">Nama Kategori</div>
                    <div class="col-md-8">
                        <input type="text" class="form-control" name="kategori" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                        <button type="submit" class="btn btn-primary" name="submit" value="submit">Simpan</button>
                        <a href="?page=kategori" class="btn btn-danger">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>
