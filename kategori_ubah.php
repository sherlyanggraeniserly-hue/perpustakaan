<?php
include 'koneksi.php'; // Pastikan file koneksi database disertakan

$id = isset($_GET['id']) ? $_GET['id'] : null;
$kategori = "";

// Jika ada ID, maka ini halaman Edit
if ($id) {
    $query = mysqli_query($koneksi, "SELECT * FROM kategori WHERE id_kategori=$id");
    $data = mysqli_fetch_array($query);
    $kategori = $data['kategori'];
}

if(isset($_POST['submit'])){
    $kategori = $_POST['kategori'];

    // Cek apakah kategori sudah ada di database (kecuali kategori yang sedang diedit)
    if ($id) {
        $cek = mysqli_query($koneksi, "SELECT * FROM kategori WHERE kategori='$kategori' AND id_kategori != $id");
    } else {
        $cek = mysqli_query($koneksi, "SELECT * FROM kategori WHERE kategori='$kategori'");
    }

    if(mysqli_num_rows($cek) > 0) {
        echo '<script>
                alert("Kategori sudah ada! Silakan masukkan nama lain.");
                window.location.href="?page=kategori_ubah";
              </script>';
    } else {
        if ($id) {
            // Proses Update
            $query = mysqli_query($koneksi, "UPDATE kategori SET kategori='$kategori' WHERE id_kategori=$id");
        } else {
            // Proses Insert
            $query = mysqli_query($koneksi, "INSERT INTO kategori(kategori) VALUES ('$kategori')");
        }

        if($query){
            echo '<script>
                    alert("Data berhasil disimpan.");
                    window.location.href="?page=kategori";
                  </script>';
        } else {
            echo '<script>alert("Data gagal disimpan.");</script>';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $id ? 'Edit' : 'Tambah' ?> Kategori Buku</title>
    <link rel="stylesheet" href="css/style_kategori.css">
</head>
<body>
    <div class="container mt-4">
        <div class="dashboard-header">
            <h2><i class="fas fa-book-open"></i> <?= $id ? 'Edit' : 'Tambah' ?> Kategori Buku</h2>
            <p class="mb-0">Silakan lengkapi informasi kategori.</p>
        </div>

        <div class="card">
            <div class="card-body">
                <form method="post">
                    <div class="row mb-3">
                        <div class="col-md-2">Nama Kategori <span class="required"></span></div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="kategori" value="<?= htmlspecialchars($kategori) ?>" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-primary" name="submit" value="submit">
                                <i class="fas fa-save"></i> Simpan
                            </button>
                            <a href="?page=kategori" class="btn btn-danger"><i class="fas fa-arrow-left"></i> Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
