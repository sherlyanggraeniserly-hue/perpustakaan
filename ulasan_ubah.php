<?php
include 'koneksi.php'; // Pastikan koneksi.php tersedia

// Pastikan id_ulasan dikirim melalui URL
if (!isset($_GET['id_ulasan'])) {
    echo "<script>alert('ID tidak ditemukan'); window.location.href='dashboard.php';</script>";
    exit;
}

$id_ulasan = $_GET['id_ulasan']; // Ambil ID ulasan dari URL
$level_user = isset($_SESSION['user']['level']) ? $_SESSION['user']['level'] : ''; // Cek level user

// Ambil data ulasan berdasarkan ID
$query = mysqli_query($koneksi, "SELECT ulasan.*, buku.judul 
                                 FROM ulasan 
                                 JOIN buku ON ulasan.id_buku = buku.id_buku 
                                 WHERE ulasan.id_ulasan = '$id_ulasan'") or die("Query Error: " . mysqli_error($koneksi));

if (mysqli_num_rows($query) > 0) {
    $data = mysqli_fetch_assoc($query);
} else {
    echo "<script>alert('Ulasan tidak ditemukan'); window.location.href='dashboard.php';</script>";
    exit;
}

// Jika form disubmit
if (isset($_POST['submit'])) {
    $ulasan = mysqli_real_escape_string($koneksi, $_POST['ulasan']);
    $reting = $_POST['reting'];

    // Update ulasan ke database
    $update = mysqli_query($koneksi, "UPDATE ulasan SET ulasan = '$ulasan', reting = '$reting' WHERE id_ulasan = '$id_ulasan'");

    if ($update) {
        echo "<script>alert('Ulasan berhasil diperbarui!'); window.location.href='" . ($level_user == 'admin' ? '?page=ulasan' : '?page=peminjamanp') . "';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui ulasan. Silakan coba lagi!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ulasan</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style_ulasan.css">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="dashboard-header">
            <h2><i class="fas fa-edit me-2"></i> Edit Ulasan</h2>
            <p class="mb-0">Perbarui ulasan Anda untuk buku ini</p>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="post">
                    <div class="mb-3">
                        <label class="form-label">Buku</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($data['judul']); ?>" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Ulasan</label>
                        <textarea name="ulasan" rows="5" class="form-control" required><?= htmlspecialchars($data['ulasan']); ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Rating</label>
                        <select name="reting" class="form-control" required>
                            <option value="1" <?= $data['reting'] == 1 ? 'selected' : ''; ?>>⭐ 1</option>
                            <option value="2" <?= $data['reting'] == 2 ? 'selected' : ''; ?>>⭐⭐ 2</option>
                            <option value="3" <?= $data['reting'] == 3 ? 'selected' : ''; ?>>⭐⭐⭐ 3</option>
                            <option value="4" <?= $data['reting'] == 4 ? 'selected' : ''; ?>>⭐⭐⭐⭐ 4</option>
                            <option value="5" <?= $data['reting'] == 5 ? 'selected' : ''; ?>>⭐⭐⭐⭐⭐ 5</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary" name="submit">Simpan Perubahan</button>
                    <a href="<?= ($level_user == 'admin') ? '?page=ulasan' : '?page=peminjamanp'; ?>" class="btn btn-danger">Kembali</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
