

<?php
include 'koneksi.php'; // Pastikan file koneksi.php ada dan benar

// Periksa apakah ada id_peminjaman yang dikirim via URL
if (isset($_GET['id_peminjaman'])) {
    $id_peminjaman = $_GET['id_peminjaman'];

    // Ambil data buku berdasarkan ID peminjaman
    $query = mysqli_query($koneksi, "SELECT buku.id_buku, buku.judul 
                                     FROM buku AS buku
                                     JOIN peminjaman AS peminjaman ON buku.id_buku = peminjaman.id_buku 
                                     WHERE peminjaman.id_peminjaman = '$id_peminjaman'");

    if (mysqli_num_rows($query) > 0) {
        $buku = mysqli_fetch_array($query);
    } else {
        echo "<script>alert('Buku tidak ditemukan untuk peminjaman ini'); window.location.href='?page=peminjamanp';</script>";
        exit;
    }
} else {
    echo "<script>alert('ID Peminjaman tidak ditemukan'); window.location.href='?page=peminjamanp';</script>";
    exit;
}

// Jika form disubmit
if (isset($_POST['submit'])) {
    $id_buku = $_POST['id_buku'];
    $id_user = $_SESSION['user']['id_user']; // Mengambil ID user dari session
    $ulasan = mysqli_real_escape_string($koneksi, $_POST['ulasan']);
    $reting = $_POST['reting'];

    // Simpan ulasan ke database
    $insert = mysqli_query($koneksi, "INSERT INTO ulasan (id_buku, id_user, ulasan, reting, id_peminjaman) 
                                      VALUES ('$id_buku', '$id_user', '$ulasan', '$reting', '$id_peminjaman')");

    if ($insert) {
        echo "<script>alert('Ulasan berhasil ditambahkan!'); window.location.href='?page=peminjamanp';</script>";
    } else {
        echo "<script>alert('Gagal menambahkan ulasan. Silakan coba lagi!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Ulasan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <title>Manajemen Buku</title>
    <link rel="stylesheet" href="css/style_ulasan.css">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="dashboard-header">
            <h2><i class="fas fa-book me-2"></i> Tambah Ulasan</h2>
            <p class="mb-0">Beri ulasan untuk buku yang telah Anda pinjam</p>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="post">
                    <div class="row mb-3">
                        <label class="col-md-2">Buku</label>
                        <div class="col-md-8">
                            <select name="id_buku" class="form-control" readonly>
                                <option value="<?= $buku['id_buku']; ?>"><?= $buku['judul']; ?></option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-2">Ulasan</label>
                        <div class="col-md-8">
                            <textarea name="ulasan" rows="5" class="form-control" required></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-md-2">Reting</label>
                        <div class="col-md-8">
                            <select name="reting" class="form-control" required>
                                <option value="1">⭐ 1</option>
                                <option value="2">⭐⭐ 2</option>
                                <option value="3">⭐⭐⭐ 3</option>
                                <option value="4">⭐⭐⭐⭐ 4</option>
                                <option value="5">⭐⭐⭐⭐⭐ 5</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-primary" name="submit">Simpan</button>
                            <a href="?page=peminjaman" class="btn btn-danger">Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
