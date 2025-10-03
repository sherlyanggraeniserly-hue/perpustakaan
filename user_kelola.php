<?php
include "koneksi.php";

$per_page = isset($_GET['items']) ? (int)$_GET['items'] : 5;
$page = isset($_GET['hal']) ? (int)$_GET['hal'] : 1;
$start = ($page - 1) * $per_page;

$level = $_SESSION['user']['level'];
$i = $start + 1;

if ($level == 'admin') {
    $query = mysqli_query($koneksi, "SELECT * FROM user LIMIT $start, $per_page");
} elseif ($level == 'petugas') {
    $query = mysqli_query($koneksi, "SELECT * FROM user WHERE level = 'peminjam' LIMIT $start, $per_page");
} else {
    $query = false;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manajemen User</title>
   <link rel="stylesheet" href="css/user.css">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="dashboard-header">
            <h2><i class="fas fa-list me-2"></i>Manajemen Kategori Buku</h2>
            <p class="mb-0">Kelola kategori buku perpustakaan</p>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                <?php if ($_SESSION['user']['level'] == 'admin') { ?>
                    <a href="?page=user_tambah" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>Tambah User
                    </a>
                    <?php } ?>
                    <div class="search-box">
                        <i class="fas fa-search search-icon"></i>
                        <input type="text" id="searchInput" class="form-control search-input" placeholder="Cari user...">
                    </div>
                </div>
                <div class="table-responsive mt-3">
                    <table class="table table-hover" id="ulasanTable">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Nama Lengkap</th>
                                <th>Email</th>
                                <th>No Telepon</th>
                                <th>Level</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($query) {
                                while ($data = mysqli_fetch_assoc($query)) { ?>
                                    <tr>
                                        <td><?php echo $i++; ?></td>
                                        <td><?php echo htmlspecialchars($data['username']); ?></td>
                                        <td><?php echo htmlspecialchars($data['nama']); ?></td>
                                        <td><?php echo htmlspecialchars($data['email']); ?></td>
                                        <td><?php echo htmlspecialchars($data['no_telepon']); ?></td>
                                        <td><?php echo htmlspecialchars(ucfirst($data['level'])); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo $data['status'] == 'aktif' ? 'success' : 'danger'; ?>">
                                                <?php echo ucfirst($data['status']); ?>
                                            </span>
                                        </td>
                                        <td>
                                            <a href="?page=user_ubah&id=<?php echo $data['id_user']; ?>" class="btn btn-info btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if ($_SESSION['user']['level'] == 'admin') { ?>
                                                <a onclick="return confirm('Apakah anda yakin menghapus data ini?');" href="?page=user_hapus&id=<?php echo $data['id_user']; ?>" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                                <?php } ?>
                                                <?php if ($_SESSION['user']['level'] == 'petugas') { ?>
                                                <a href="?page=user_status&id=<?php echo $data['id_user']; ?>&status=<?php echo $data['status'] == 'aktif' ? 'pasif' : 'aktif'; ?>" class="btn btn-warning btn-sm">
                                                    <i class="fas fa-exchange-alt"></i>
                                                </a>
                                             <?php } ?>
                                        </td>
                                    </tr>
                            <?php } } else { echo "<tr><td colspan='8' class='text-center'>Anda tidak memiliki akses</td></tr>"; } ?>
                        </tbody>
                    </table>
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
                    $total_records_query = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM user");
                    $total_records = mysqli_fetch_assoc($total_records_query)['total'];
                    $total_pages = ceil($total_records / $per_page);
                    ?>
                    <nav>
                        <ul class="pagination">
                            <?php for ($i = 1; $i <= $total_pages; $i++) { ?>
                                <li class="page-item <?php echo $page == $i ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=user_kelola&hal=<?php echo $i; ?>&items=<?php echo $per_page; ?>"> <?php echo $i; ?> </a>
                                </li>
                            <?php } ?>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('searchInput').addEventListener('keyup', function() {
            var value = this.value.toLowerCase();
            document.querySelectorAll('#ulasanTable tbody tr').forEach(row => {
                row.style.display = row.textContent.toLowerCase().includes(value) ? '' : 'none';
            });
        });
        function changeItemsPerPage(value) {
            window.location.href = '?page=user_kelola&items=' + value;
        }
    </script>
</body>
</html>
