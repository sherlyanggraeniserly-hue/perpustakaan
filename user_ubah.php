<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Buku</title>
    <link rel="stylesheet" href="css/style_kategori.css">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="dashboard-header">
            <h2><i class="fas fa-book me-2"></i>edit user</h2>
            <p class="mb-0">edit pengguna perpustakaan digital</p>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                <form method="post">
                <?php
$id = $_GET['id'];  

// Ambil data user berdasarkan id
$query = mysqli_query($koneksi, "SELECT * FROM user WHERE id_user = $id");
$data = mysqli_fetch_array($query);

// Proses saat form disubmit
if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $no_telepon = $_POST['no_telepon'];
    $level = $_POST['level'];

    // Update password hanya jika diisi
    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $query = mysqli_query($koneksi, "UPDATE user SET 
            nama='$nama', 
            username='$username', 
            password='$password', 
            email='$email', 
            no_telepon='$no_telepon', 
            level='$level' 
            WHERE id_user=$id
        ");
    } else {
        $query = mysqli_query($koneksi, "UPDATE user SET 
            nama='$nama', 
            username='$username', 
            email='$email', 
            no_telepon='$no_telepon', 
            level='$level' 
            WHERE id_user=$id
        ");
    }

    if ($query) {
        echo '<script>alert("Data user berhasil diubah."); window.location="?page=user_kelola";</script>';
    } else {
        echo '<script>alert("Gagal mengubah data user.");</script>';
    }
}
?>

<div class="row mb-3">
    <div class="col-md-2">Nama Lengkap</div>
    <div class="col-md-8">
        <input type="text" value="<?= htmlspecialchars($data['nama']); ?>" class="form-control" name="nama" required>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-2">Username</div>
    <div class="col-md-8">
        <input type="text" value="<?= htmlspecialchars($data['username']); ?>" class="form-control" name="username" required>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-2">Password</div>
    <div class="col-md-8">
        <input type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak ingin mengubah">
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-2">Email</div>
    <div class="col-md-8">
        <input type="email" value="<?= htmlspecialchars($data['email']); ?>" class="form-control" name="email" required>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-2">No Telepon</div>
    <div class="col-md-8">
        <input type="text" value="<?= htmlspecialchars($data['no_telepon']); ?>" class="form-control" name="no_telepon" required>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-2">Level</div>
    <div class="col-md-8">
        <select name="level" class="form-control" required>
            <option value="admin" <?= $data['level'] == 'admin' ? 'selected' : ''; ?>>Admin</option>
            <option value="petugas" <?= $data['level'] == 'petugas' ? 'selected' : ''; ?>>Petugas</option>
            <option value="peminjam" <?= $data['level'] == 'peminjam' ? 'selected' : ''; ?>>Peminjam</option>
        </select>
    </div>
</div>

<div class="row">
    <div class="col-md-2"></div>
    <div class="col-md-8">
        <button type="submit" class="btn btn-primary" name="submit">Simpan</button>
        <a href="?page=user_kelola" class="btn btn-danger">Kembali</a>
    </div>
</div>

                </form>
            </div>
        </div>
    </div>
</div>
