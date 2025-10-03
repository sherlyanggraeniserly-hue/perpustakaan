<?php
include 'koneksi.php'; // Pastikan file koneksi database sudah benar

// Pastikan ID dikirim dengan aman
$id = isset($_GET['id']) ? mysqli_real_escape_string($koneksi, $_GET['id']) : die("ID tidak ditemukan!");

if (isset($_POST['submit'])) {
    $judul = mysqli_real_escape_string($koneksi, $_POST['judul']);
    $penulis = mysqli_real_escape_string($koneksi, $_POST['penulis']);
    $penerbit = mysqli_real_escape_string($koneksi, $_POST['penerbit']);
    $tahun_terbit = mysqli_real_escape_string($koneksi, $_POST['tahun_terbit']);
    $deskripsi = mysqli_real_escape_string($koneksi, $_POST['deskripsi']);
    $stok = mysqli_real_escape_string($koneksi, $_POST['stok']);
    $kategori = isset($_POST['id_kategori']) ? $_POST['id_kategori'] : []; // Pastikan kategori adalah array

    // Proses Upload Gambar (Jika Ada)
    $foto_buku = "";
    if (!empty($_FILES['foto_buku']['name'])) {
        $foto_buku = time() . "_" . basename($_FILES['foto_buku']['name']);
        $target = "uploads/" . $foto_buku;

        // Cek tipe file
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        $file_extension = strtolower(pathinfo($foto_buku, PATHINFO_EXTENSION));

        if (!in_array($file_extension, $allowed_types)) {
            die("Format gambar tidak diperbolehkan. Hanya JPG, JPEG, PNG, dan GIF.");
        }

        // Upload file
        if (!move_uploaded_file($_FILES['foto_buku']['tmp_name'], $target)) {
            die("Gagal mengupload gambar.");
        }

        // Hapus gambar lama (jika ada)
        $query_old_img = mysqli_query($koneksi, "SELECT foto_buku FROM buku WHERE id_buku='$id'");
        if ($query_old_img) {
            $old_img = mysqli_fetch_assoc($query_old_img);
            if (!empty($old_img['foto_buku']) && file_exists("uploads/" . $old_img['foto_buku'])) {
                unlink("uploads/" . $old_img['foto_buku']);
            }
        }

        // Update dengan foto baru
        $query = "UPDATE buku SET 
            judul='$judul', 
            penulis='$penulis', 
            penerbit='$penerbit', 
            tahun_terbit='$tahun_terbit', 
            deskripsi='$deskripsi', 
            foto_buku='$foto_buku', 
            stok='$stok' 
            WHERE id_buku='$id'";
    } else {
        // Update tanpa foto baru
        $query = "UPDATE buku SET 
            judul='$judul', 
            penulis='$penulis', 
            penerbit='$penerbit', 
            tahun_terbit='$tahun_terbit', 
            deskripsi='$deskripsi', 
            stok='$stok' 
            WHERE id_buku='$id'";
    }

    // Jalankan query update
    if (!mysqli_query($koneksi, $query)) {
        die("Gagal mengupdate data buku: " . mysqli_error($koneksi));
    }

    // Hapus kategori lama
    if (!mysqli_query($koneksi, "DELETE FROM buku_kategori WHERE id_buku='$id'")) {
        die("Gagal menghapus kategori lama: " . mysqli_error($koneksi));
    }

    // Simpan kategori baru (jika ada)
    if (!empty($kategori) && is_array($kategori)) {
        $stmt = mysqli_prepare($koneksi, "INSERT INTO buku_kategori (id_buku, id_kategori) VALUES (?, ?)");
        if ($stmt) {
            foreach ($kategori as $kat) {
                mysqli_stmt_bind_param($stmt, "ii", $id, $kat);
                mysqli_stmt_execute($stmt);
            }
            mysqli_stmt_close($stmt);
        } else {
            die("Gagal menyimpan kategori: " . mysqli_error($koneksi));
        }
    }

    echo '<script>alert("Edit data berhasil."); window.location.href="?page=buku";</script>';
}

// Ambil data buku
$query = mysqli_query($koneksi, "SELECT * FROM buku WHERE id_buku='$id'");
if (!$query || mysqli_num_rows($query) == 0) {
    die("Data buku tidak ditemukan.");
}
$data = mysqli_fetch_assoc($query);

// Ambil kategori yang sudah dipilih
$kategori_terpilih = [];
$query_kategori = mysqli_query($koneksi, "SELECT id_kategori FROM buku_kategori WHERE id_buku='$id'");
while ($row = mysqli_fetch_array($query_kategori)) {
    $kategori_terpilih[] = $row['id_kategori'];
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Buku</title>
    <link rel="stylesheet" href="css/style_buku.css">
    
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="dashboard-header">
            <h2>Edit Buku</h2>
            <p>Edit koleksi buku di perpustakaan</p>
        </div>
        <div class="card">
            <div class="card-body">
            <form method="post" >
                    <div class="row mb-3">
                        <div class="col-md-2">Kategori</div>
                        <div class="col-md-8">
                            <select name="id_kategori[]" class="form-control" multiple>
                                <?php
                                $kat = mysqli_query($koneksi, "SELECT * FROM kategori");
                                while ($kategori = mysqli_fetch_array($kat)) {
                                    $selected = in_array($kategori['id_kategori'], $kategori_terpilih) ? 'selected' : '';
                                    echo "<option value='{$kategori['id_kategori']}' $selected>{$kategori['kategori']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>


                    <div class="row mb-3">
                        <div class="col-md-2">Judul</div>
                        <div class="col-md-8"><input type="text" value="<?php echo $data['judul']; ?>" class="form-control" name="judul"></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">Penulis</div>
                        <div class="col-md-8"><input type="text" value="<?php echo $data['penulis']; ?>" class="form-control" name="penulis"></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">Penerbit</div>
                        <div class="col-md-8"><input type="text" value="<?php echo $data['penerbit']; ?>" class="form-control" name="penerbit"></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">Tahun Terbit</div>
                        <div class="col-md-8"><input type="text" value="<?php echo $data['tahun_terbit']; ?>" class="form-control" name="tahun_terbit"></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">Deskripsi</div>
                        <div class="col-md-8">
                            <textarea name="deskripsi" rows="5" class="form-control"><?php echo $data['deskripsi']; ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">        
                        <div class="d-flex align-items-center">
                            <div class="col-md-2 required">Foto Buku</div>
                            <div class="col-md-8">
                                <div class="d-flex align-items-center">
                                    <input type="file" name="foto_buku" accept="image/*" id="fotoBukuInput" class="form-control me-3" onchange="previewFile()">
                                    <img id="previewImage" src="uploads/<?php echo htmlspecialchars($data['foto_buku']); ?>" class="img-thumbnail" style="max-width: 100px; max-height: 150px; border-radius: 8px; object-fit: cover;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">Stok</div>
                        <div class="col-md-8"><input type="number" value="<?php echo $data['stok']; ?>" class="form-control" name="stok"></div>
                    </div>

                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-primary" name="submit">Simpan</button>
                            <a href="?page=buku" class="btn btn-danger">Kembali</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>







    <script>
    function previewFile() {
        var preview = document.getElementById('previewImage');
        var file = document.getElementById('fotoBukuInput').files[0];
        var reader = new FileReader();

        reader.onloadend = function () {
            preview.src = reader.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        }
    }
</script>

</body>
</html>
