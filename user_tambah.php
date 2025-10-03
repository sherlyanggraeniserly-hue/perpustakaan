<?php
include 'koneksi.php'; // Pastikan file koneksi ke database sudah benar

if (isset($_POST['submit'])) {
    $nama = $_POST['nama'];
    $username = $_POST['username'];
    $password = md5($_POST['password']); // Gunakan hash yang lebih aman seperti password_hash()
    $email = $_POST['email'];
    $no_telepon = $_POST['no_telepon'];
    $level = $_POST['level'];
    $register_date = date("Y-m-d H:i:s"); // Format timestamp untuk register_date

    // Perbaikan: Sesuaikan dengan struktur database
    $query = mysqli_query($koneksi, "INSERT INTO perpus_user (username, password, nama, email, no_telepon, level, register_date) 
                                     VALUES ('$username', '$password', '$nama', '$email', '$no_telepon', '$level', '$register_date')");

    if ($query) {
        echo '<script>alert("Tambah user berhasil.");</script>';
    } else {
        echo '<script>alert("Tambah user gagal: ' . mysqli_error($koneksi) . '");</script>';
    }
}
?>

<body class="bg-light">
    <div class="container mt-4">
        <div class="dashboard-header">
            <h2><i class="fas fa-book me-2"></i>tambah user</h2>
            <p class="mb-0">tambah pengguna perpustakaan digital</p>
            <link rel="stylesheet" href="css/style_kategori.css">
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                <form method="post">
                <div class="row mb-3">
                    <div class="col-md-2">Nama</div>
                    <div class="col-md-8"><input type="text" class="form-control" name="nama" required></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-2">Username</div>
                    <div class="col-md-8"><input type="text" class="form-control" name="username" required></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-2">Password</div>
                    <div class="col-md-8"><input type="password" class="form-control" name="password" required></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-2">Email</div>
                    <div class="col-md-8"><input type="email" class="form-control" name="email" required></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-2">No Telepon</div>
                    <div class="col-md-8"><input type="text" class="form-control" name="no_telepon" required></div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-2">Level</div>
                    <div class="col-md-8">
                        <select name="level" class="form-control" required>
                            <option value="admin">Admin</option>
                            <option value="petugas">Petugas</option>
                            <option value="peminjam">Peminjam</option>
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
