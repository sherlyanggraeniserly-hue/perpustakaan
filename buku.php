<?php
include 'koneksi.php';


// Mendapatkan level pengguna
$level_user = isset($_SESSION['level']) ? $_SESSION['level'] : '';

// Mendapatkan jumlah item per halaman dan halaman saat ini
$per_page = isset($_GET['items']) ? (int)$_GET['items'] : 10;
$page = isset($_GET['hal']) ? (int)$_GET['hal'] : 1;
$start = ($page - 1) * $per_page;

// Query untuk mengambil data buku termasuk stok, stok dipinjam, dan gambar
$query = mysqli_query($koneksi, "
    SELECT buku.id_buku, buku.judul, buku.penulis, buku.penerbit, buku.tahun_terbit, buku.deskripsi, buku.stok, buku.stok_dipinjam, buku.foto_buku,
           GROUP_CONCAT(kategori.kategori SEPARATOR ', ') AS kategori_list
    FROM buku
    LEFT JOIN buku_kategori ON buku.id_buku = buku_kategori.id_buku
    LEFT JOIN kategori ON buku_kategori.id_kategori = kategori.id_kategori
    GROUP BY buku.id_buku
    LIMIT $start, $per_page
");

$total_records_query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM buku");
$total_records = mysqli_fetch_assoc($total_records_query)['total'];
$total_pages = ceil($total_records / $per_page);
?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Buku</title>
    <link rel="stylesheet" href="css/awal_buku.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <div class="dashboard-header">
            <h2><i class="fas fa-book me-2"></i>Manajemen Buku</h2>
            <p class="mb-0">Kelola koleksi buku perpustakaan</p>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="?page=buku_tambah" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Tambah Buku
                    </a>
                    <input type="text" id="searchInput" class="form-control w-25" placeholder="Cari buku...">
                </div>

                <div class="table-responsive">
                    <table class="table table-hover" id="bookTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Gambar</th>
                                <th>Kategori</th>
                                <th>Judul</th>
                                <th>Penulis</th>
                                <th>Penerbit</th>
                                <th>Tahun</th>
                                <th>Stok</th>
                                <th>Dipinjam</th>
                                <th>Deskripsi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = $start + 1;
                            while ($data = mysqli_fetch_array($query)) {
                                $stok_tersedia = $data['stok'] - $data['stok_dipinjam'];
                            ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td>
                                    <?php if (!empty($data['foto_buku'])) { ?>
                                        <img src="uploads/<?php echo $data['foto_buku']; ?>" class="book-img" alt="Gambar Buku">
                                    <?php } else { ?>
                                        <img src="uploads/default.jpg" class="book-img" alt="Gambar Default">
                                    <?php } ?>
                                </td>
                                <td>
                                    <?php
                                    $kategori_arr = explode(', ', $data['kategori_list']);
                                    foreach ($kategori_arr as $kategori) {
                                        echo "<span class='badge'>$kategori</span>";
                                    }
                                    ?>
                                </td>
                                <td><?php echo $data['judul']; ?></td>
                                <td><?php echo $data['penulis']; ?></td>
                                <td><?php echo $data['penerbit']; ?></td>
                                <td><?php echo $data['tahun_terbit']; ?></td>
                                <td><?= htmlspecialchars($data['stok']); ?></td>
                                <td><?php echo $data['stok_dipinjam']; ?></td>
                                <td class="description-content"><?php echo $data['deskripsi']; ?></td>
                                <td>
                                    <a href="?page=buku_ubah&id=<?php echo $data['id_buku']; ?>" class="btn btn-info btn-sm">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($_SESSION['user']['level'] == 'admin') { ?>
                                        <a onclick="return confirm('Apakah anda yakin menghapus data ini?');" href="?page=buku_hapus&id=<?php echo $data['id_buku']; ?>" class="btn btn-danger btn-sm">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                    <?php } ?>
                                </td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="items-per-page">
                        <label>Tampilkan:</label>
                        <select id="itemsPerPage" class="form-select" style="width: auto;" onchange="changeItemsPerPage(this.value)">
                            <option value="10" <?php echo $per_page == 10 ? 'selected' : ''; ?>>10</option>
                            <option value="20" <?php echo $per_page == 20 ? 'selected' : ''; ?>>20</option>
                            <option value="30" <?php echo $per_page == 30 ? 'selected' : ''; ?>>30</option>
                            <option value="40" <?php echo $per_page == 40 ? 'selected' : ''; ?>>40</option>
                        </select>
                        <span>item per halaman</span>
                    </div>

                    <nav>
                        <ul class="pagination">
                            <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                                <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=buku&hal=<?php echo $i; ?>&items=<?php echo $per_page; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <script>
    $(document).ready(function(){
        $("#searchInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#bookTable tbody tr").filter(function() {
                $(this).toggle($(this).children("td").toArray().some(td => $(td).text().toLowerCase().indexOf(value) > -1));
            });
        });
    });

    function changeItemsPerPage(value) {
        window.location.href = '?page=buku&items=' + value;
    }
    </script>
</body>
</html>
