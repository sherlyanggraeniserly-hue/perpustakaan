<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ulasan Buku</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/awal_buku.css">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="dashboard-header">
            <h2><i class="fas fa-comments me-2"></i>Ulasan Buku</h2>
            <p class="mb-0">Kelola ulasan buku perpustakaan</p>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-flex gap-2 mb-3">
                    <select id="filterJudul" class="form-select">
                        <option value="">Pilih Judul Buku</option>
                        <?php
                        $judul_query = mysqli_query($koneksi, "SELECT DISTINCT judul FROM buku");
                        while ($judul = mysqli_fetch_assoc($judul_query)) {
                            echo "<option value='" . strtolower($judul['judul']) . "'>" . $judul['judul'] . "</option>";
                        }
                        ?>
                    </select>
                    <select id="filterRating" class="form-select">
                        <option value="">Pilih Rating</option>
                        <option value="1">⭐1</option>
                        <option value="2">⭐⭐2</option>
                        <option value="3">⭐⭐⭐3</option>
                        <option value="4">⭐⭐⭐⭐4</option>
                        <option value="5">⭐⭐⭐⭐⭐5</option>
                    </select>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover" id="ulasanTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>User</th>
                                <th>Buku</th>
                                <th>Ulasan</th>
                                <th>Rating</th>
                                <?php if ($_SESSION['user']['level'] == 'admin') { ?>
                                <th>Aksi</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = mysqli_query($koneksi, "SELECT * FROM ulasan 
                                LEFT JOIN user ON user.id_user = ulasan.id_user 
                                LEFT JOIN buku ON buku.id_buku = ulasan.id_buku");
                            $i = 1;
                            while ($data = mysqli_fetch_array($query)) {
                            ?>
                            <tr>
                                <td><?php echo $i++; ?></td>
                                <td><?php echo $data['username']; ?></td>
                                <td class="judul"><?php echo strtolower($data['judul']); ?></td>
                                <td><?php echo $data['ulasan']; ?></td>
                                <td class="rating"><?= str_repeat('⭐', $data['reting']) ?></td>
                                <?php if ($_SESSION['user']['level'] == 'admin') { ?>
                                <td>
                                    
                                        <a onclick="return confirm('Apakah anda yakin menghapus data ini?');" 
                                           href="ulasan_hapus.php?id=<?php echo $data['id_ulasan']; ?>&redirect=admin" 
                                           class="btn btn-danger btn-sm">
                                           <i class="fas fa-trash"></i>
                                        </a>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#filterJudul, #filterRating").on("change", function() {
                var judulValue = $("#filterJudul").val();
                var ratingValue = $("#filterRating").val();
                
                $("#ulasanTable tbody tr").each(function() {
                    var judulMatch = !judulValue || $(this).find(".judul").text().toLowerCase() === judulValue;
                    var ratingMatch = !ratingValue || $(this).find(".rating").text().length === parseInt(ratingValue);
                    $(this).toggle(judulMatch && ratingMatch);
                });
            });
        });
    </script>
</body>
</html>
