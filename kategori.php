<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen Kategori Buku</title>
    <link rel="stylesheet" href="css/awal_buku.css">
    <style>
        
    </style>
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="dashboard-header" align = "right">
            <h2><i class="fas fa-list me-2"></i>Manajemen Kategori Buku</h2>
            <p class="mb-0">Kelola kategori buku perpustakaan</p>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="d-flex flex-wrap justify-content-between align-items-center">
                    <a href="?page=kategori_tambah" class="btn btn-primary mb-3">
                        <i class="fas fa-plus me-2"></i>Tambah Kategori
                    </a>
                    <div class="search-box mb-3">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="searchInput" class="form-control search-input" placeholder="Cari kategori...">
                    </div>
                </div>

                <div class="table-container">
                    <div class="table-responsive">
                        <table class="table table-hover" id="categoryTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Kategori</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $per_page = isset($_GET['items']) ? (int)$_GET['items'] : 5;
                                $page = isset($_GET['hal']) ? (int)$_GET['hal'] : 1;
                                $start = ($page - 1) * $per_page;

                                $query = mysqli_query($koneksi, "SELECT * FROM kategori LIMIT $start, $per_page");
                                $no = $start + 1;
                                while($data = mysqli_fetch_array($query)){
                                ?>
                                <tr>
                                    <td><?php echo $no++; ?></td>
                                    <td><?php echo $data['kategori']; ?></td>
                                    <td>
                                        <a href="?page=kategori_ubah&id=<?php echo $data['id_kategori']; ?>" class="btn btn-info btn-sm" title="Ubah">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($_SESSION['user']['level'] == 'admin') { ?>
                                        <a onclick="return confirm('Apakah anda yakin menghapus data ini?');" href="?page=kategori_hapus&&id=<?php echo $data['id_kategori']; ?>" class="btn btn-danger btn-sm" title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="d-flex justify-content-between align-items-center flex-wrap">
                    <div class="items-per-page">
                        <label>Tampilkan:</label>
                        <select id="itemsPerPage" class="form-select" style="width: auto;" onchange="changeItemsPerPage(this.value)">
                            <option value="5" <?php echo $per_page == 5 ? 'selected' : ''; ?>>5</option>
                            <option value="10" <?php echo $per_page == 10 ? 'selected' : ''; ?>>10</option>
                            <option value="25" <?php echo $per_page == 25 ? 'selected' : ''; ?>>25</option>
                            <option value="50" <?php echo $per_page == 50 ? 'selected' : ''; ?>>50</option>
                        </select>
                        <span>item per halaman</span>
                    </div>

                    <?php
                    $total_records_query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM kategori");
                    $total_records = mysqli_fetch_assoc($total_records_query)['total'];
                    $total_pages = ceil($total_records / $per_page);
                    ?>

                    <nav aria-label="Page navigation" class="mt-4">
                        <ul class="pagination">
                            <?php for($i = 1; $i <= $total_pages; $i++) { ?>
                                <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=kategori&hal=<?php echo $i; ?>&items=<?php echo $per_page; ?>"><?php echo $i; ?></a>
                                </li>
                            <?php } ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function(){
            $("#searchInput").on("keyup", function() {
                var value = $(this).val().toLowerCase();
                $("#categoryTable tbody tr").filter(function() {
                    $(this).toggle($(this).find('td:nth-child(2)').text().toLowerCase().indexOf(value) > -1);
                });
            });
        });

        function changeItemsPerPage(value) {
            window.location.href = '?page=kategori&items=' + value;
        }
    </script>
</body>
</html>