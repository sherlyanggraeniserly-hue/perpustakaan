<?php
include "koneksi.php";

// Cek apakah id_user ada di session atau URL
if (isset($_GET['id_user'])) {
    $id_user = $_GET['id_user'];
} elseif (isset($_SESSION['id_user'])) {
    $id_user = $_SESSION['id_user'];
} else {
    echo "ID User tidak ditemukan!";
    exit;
}

// Ambil data user dari database
$query = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user = '$id_user'");
$user = mysqli_fetch_assoc($query);

if (!$user) {
    echo "User tidak ditemukan!";
    exit;
}


// Proses update profil
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $email = $_POST['email'];
    $no_telepon = $_POST['no_telepon'];

    // Cek apakah ada file yang diunggah
    if (!empty($_FILES['foto_profil']['name'])) {
        $foto = $_FILES['foto_profil']['name'];
        $temp = $_FILES['foto_profil']['tmp_name'];
        $folder = "foto/" . $foto;

        move_uploaded_file($temp, $folder);

        // Update dengan foto profil
        $update = mysqli_query($koneksi, "UPDATE user SET nama='$nama', email='$email', no_telepon='$no_telepon', foto_profil='$foto' WHERE id_user='$id_user'");
    } else {
        // Update tanpa mengubah foto
        $update = mysqli_query($koneksi, "UPDATE user SET nama='$nama', email='$email', no_telepon='$no_telepon' WHERE id_user='$id_user'");
    }

    if ($update) {
        echo "<script>alert('Profil berhasil diperbarui!'); window.location='?page=home';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui profil!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pengaturan Profil</title>
    <link rel="stylesheet" href="css/awal_buku.css">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="dashboard-header">
            <h2><i class="fas fa-comments me-2"></i> pengaturan ptofil</h2>
            <p class="mb-0">pengaturan profil user peminjam perpustakaan</p>
        </div>
    <form method="post" enctype="multipart/form-data">
        <label>Nama:</label>
        <input type="text" name="nama" value="<?= isset($user['nama']) ? htmlspecialchars($user['nama'], ENT_QUOTES, 'UTF-8') : ''; ?>" required><br>

        <label>Email:</label>
        <input type="email" name="email" value="<?= isset($user['email']) ? htmlspecialchars($user['email'], ENT_QUOTES, 'UTF-8') : ''; ?>" required><br>

        <label>No. Telepon:</label>
        <input type="text" name="no_telepon" value="<?= isset($user['no_telepon']) ? htmlspecialchars($user['no_telepon'], ENT_QUOTES, 'UTF-8') : ''; ?>" required><br>

        <label>Foto Profil:</label>
        <input type="file" name="foto_profil"><br>
        <?php if (!empty($user['foto_profil'])): ?>
            <img src="foto/<?= htmlspecialchars($user['foto_profil'], ENT_QUOTES, 'UTF-8'); ?>" width="100"><br>
        <?php endif; ?>

        <button type="submit">Simpan Perubahan</button>
    </form>
</body>
</html>
