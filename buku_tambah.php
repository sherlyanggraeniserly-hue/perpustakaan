<?php
                    if(isset($_POST['submit'])) {
                        $kategori_ids = $_POST['id_kategori']; // Now an array
                        $judul = $_POST['judul'];
                        $penulis = $_POST['penulis'];
                        $penerbit = $_POST['penerbit'];
                        $tahun_terbit = $_POST['tahun_terbit'];
                        $deskripsi = $_POST['deskripsi'];

                        // Handle file upload for foto_buku
                        $foto_buku = $_FILES['foto_buku']['name'];
                        $foto_tmp = $_FILES['foto_buku']['tmp_name'];
                        $foto_path = "uploads/" . $foto_buku;
                        move_uploaded_file($foto_tmp, $foto_path);

                        $stok = $_POST['stok'];
                        $stok_dipinjam = 0;

                        // Start transaction
                        mysqli_begin_transaction($koneksi);

                        try {
                            // Insert into buku table
                            $query = mysqli_query($koneksi, "INSERT INTO buku (judul, penulis, penerbit, tahun_terbit, deskripsi, foto_buku, stok, stok_dipinjam) 
                                VALUES ('$judul', '$penulis', '$penerbit', '$tahun_terbit', '$deskripsi', '$foto_buku', '$stok', '$stok_dipinjam')");

                            $buku_id = mysqli_insert_id($koneksi);

                            // Insert into buku_kategori table
                            foreach($kategori_ids as $kategori_id) {
                                mysqli_query($koneksi, "INSERT INTO buku_kategori (id_buku, id_kategori) VALUES ('$buku_id', '$kategori_id')");
                            }

                            // Commit transaction
                            mysqli_commit($koneksi);
                            echo '<script>alert("Tambah data berhasil."); window.location.href="?page=buku";</script>';
                        } catch (Exception $e) {
                            // Rollback on error
                            mysqli_rollback($koneksi);
                            echo '<script>alert("Tambah data gagal.");</script>';
                        }
                    }
                    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Buku</title>
    <link rel="stylesheet" href="css/style_buku.css">

</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="dashboard-header">
            <h2><i class="fas fa-book me-2"></i>Tambah Buku</h2>
            <p class="mb-0">Tambah koleksi buku baru ke perpustakaan</p>
        </div>

        <div class="card">
            <div class="card-body">
            <form method="post" enctype="multipart/form-data">
            <div class="row mb-3">
        <div class="col-md-2">Kategori</div>
        <div class="col-md-8">
            <select name="id_kategori[]" class="form-control" multiple>
                <?php
                $kat = mysqli_query($koneksi, "SELECT * FROM kategori");
                while ($kategori = mysqli_fetch_array($kat)) {
                    echo "<option value='{$kategori['id_kategori']}'>{$kategori['kategori']}</option>";
                }
                ?>
            </select>
        </div>
    </div>


                    <!-- Rest of the form remains exactly the same -->
                    <div class="row mb-3">
                        <div class="col-md-2 required">Judul</div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="judul" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2 required">Penulis</div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="penulis" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2 required">Penerbit</div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="penerbit" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2 required">Tahun Terbit</div>
                        <div class="col-md-8">
                            <input type="text" class="form-control" name="tahun_terbit" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2">Deskripsi</div>
                        <div class="col-md-8">
                            <textarea name="deskripsi" rows="5" class="form-control"></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">        
                        <div class="d-flex align-items-center">
                        <div class="col-md-2 required">Foto Buku</div>
                            <div class="col-md-8">
                                <div class="d-flex align-items-center">
                                    <input type="file" name="foto_buku" accept="image/*" id="fotoBukuInput" class="form-control me-3" onchange="previewFile()">
                                    <img id="previewImage" src="" class="img-thumbnail" style="max-width: 100px; max-height: 150px; border-radius: 8px; object-fit: cover; display: none;">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-2 required">Stok</div>
                        <div class="col-md-8">
                            <input type="number" class="form-control" name="stok" min="0" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-2"></div>
                        <div class="col-md-8">
                            <button type="submit" class="btn btn-primary" name="submit">Simpan</button>
                            <button type="reset" class="btn btn-secondary">Reset</button>
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
        var fileNameText = document.getElementById('fileNameText');
        var reader = new FileReader();

        if (file) {
            reader.onloadend = function () {
                preview.src = reader.result;
                preview.style.display = "block"; // Menampilkan gambar jika ada file
            }
            reader.readAsDataURL(file);
            fileNameText.textContent = "File: " + file.name;
        } else {
            preview.style.display = "none"; // Sembunyikan gambar jika tidak ada file
            fileNameText.textContent = "Belum ada file dipilih";
        }
    }
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const categorySelect = document.querySelector('select[name="id_kategori[]"]');
        const selectedCategoriesDiv = document.getElementById('selectedCategories');
        
        function updateSelectedCategories() {
            selectedCategoriesDiv.innerHTML = '';
            Array.from(categorySelect.selectedOptions).forEach(option => {
                const chip = document.createElement('div');
                chip.className = 'kategori-chip';
                chip.innerHTML = `
                    ${option.text}
                    <button type="button" onclick="removeCategory('${option.value}')">&times;</button>
                `;
                selectedCategoriesDiv.appendChild(chip);
            });
        }

        categorySelect.addEventListener('change', updateSelectedCategories);

        window.removeCategory = function(value) {
            const option = categorySelect.querySelector(`option[value="${value}"]`);
            option.selected = false;
            updateSelectedCategories();
        };
    });
    </script>


</body>
</html>