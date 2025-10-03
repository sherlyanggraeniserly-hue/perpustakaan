<?php
include 'koneksi.php';

if (!isset($_SESSION['user'])) {
    echo "<script>alert('Anda harus login terlebih dahulu!'); window.location.href='login.php';</script>";
    exit;
}

$id_user = $_SESSION['user']['id_user'];
$level = $_SESSION['user']['level'];

if (isset($_POST['dikembalikan'])) {
    $id_peminjaman = $_POST['id_peminjaman'];
    $tanggal_kembali = date('Y-m-d');

    // Update status peminjaman
    $update_query = "UPDATE peminjaman SET 
                     status_peminjaman = 'dikembalikan', 
                     tanggal_pengembalian = '$tanggal_kembali' 
                     WHERE id_peminjaman = '$id_peminjaman'";
    
    if (mysqli_query($koneksi, $update_query)) {
        // Update stok buku (stok utama bertambah, stok dipinjam berkurang)
        $buku_query = "UPDATE buku 
        SET stok = stok + 1, stok_dipinjam = stok_dipinjam - 1
        WHERE id_buku = (SELECT id_buku FROM peminjaman WHERE id_peminjaman = '$id_peminjaman')";

        mysqli_query($koneksi, $buku_query);
        
        echo "<script>alert('Buku berhasil dikembalikan!'); window.location.href='?page=peminjaman';</script>";
    } else {
        echo "<script>alert('Gagal mengembalikan buku!');</script>";
    }
}


$status_filter = isset($_GET['status']) ? $_GET['status'] : '';

$query = "SELECT peminjaman.*, user.username, buku.judul, 
          CASE 
              WHEN peminjaman.status_peminjaman = 'dipinjam' 
                   THEN DATEDIFF(DATE_ADD(peminjaman.tanggal_peminjaman, INTERVAL 7 DAY), CURDATE())
              ELSE DATEDIFF(peminjaman.tanggal_pengembalian, peminjaman.tanggal_peminjaman) 
          END AS durasi,
          CASE
              WHEN peminjaman.status_peminjaman = 'dikembalikan' AND peminjaman.tanggal_pengembalian > DATE_ADD(peminjaman.tanggal_peminjaman, INTERVAL 7 DAY)
              THEN CONCAT('Terlambat ', DATEDIFF(peminjaman.tanggal_pengembalian, DATE_ADD(peminjaman.tanggal_peminjaman, INTERVAL 7 DAY)), ' hari')
              ELSE ''
          END AS keterlambatan
          FROM peminjaman 
          LEFT JOIN user ON user.id_user = peminjaman.id_user 
          LEFT JOIN buku ON buku.id_buku = peminjaman.id_buku";

$where_clauses = [];
if ($level == 'peminjam') {
    $where_clauses[] = "peminjaman.id_user = '$id_user'";
}
if (!empty($status_filter)) {
    if ($status_filter == 'terlambat') {
        $where_clauses[] = "peminjaman.status_peminjaman = 'dipinjam' AND CURDATE() > DATE_ADD(peminjaman.tanggal_peminjaman, INTERVAL 7 DAY)";
    } else {
        $where_clauses[] = "peminjaman.status_peminjaman = '$status_filter'";
    }
}
if (!empty($where_clauses)) {
    $query .= " WHERE " . implode(" AND ", $where_clauses);
}
$query .= " ORDER BY peminjaman.tanggal_peminjaman DESC";
$result = mysqli_query($koneksi, $query);
?>



<!DOCTYPE html>
<html>
<head>
    <title>Peminjaman Buku</title>
    <link rel="stylesheet" href="css/style_peminjama.css">
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="dashboard-header">
            <h2><i class="fas fa-list me-2"></i> Manajemen Peminjaman dan Pengembalian Buku</h2>
            <p class="mb-0">Kelola peminjaman dan pengembalian buku perpustakaan</p>
            
        </div> 
        <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <input type="text" id="searchInput" class="form-control w-25" placeholder="Cari berdasarkan semua informasi...">
                </div>
         <!-- Tabs untuk Filter -->
         <ul class="nav nav-tabs mb-3" id="statusTabs">
            <li class="nav-item">
                <a class="nav-link <?= ($status_filter == '' ? 'active' : '') ?>" data-status="all" href="?page=peminjaman">Semua</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($status_filter == 'dipinjam' ? 'active' : '') ?>" data-status="dipinjam" href="?page=peminjaman&status=dipinjam">Dipinjam</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($status_filter == 'dikembalikan' ? 'active' : '') ?>" data-status="dikembalikan" href="?page=peminjaman&status=dikembalikan">Dikembalikan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= ($status_filter == 'terlambat' ? 'active' : '') ?>" data-status="Terlambat" href="?page=peminjaman&status=terlambat">terlambat</a>
            </li>
        </ul> 
</div>  

        <table class="table table-bordered" id="peminjamanTable">
        <tbody>
        <tr>
            <th>No</th>
            <th>User</th>
            <th>Buku</th>
            <th>Tanggal Peminjaman</th>
            <th>Tanggal Pengembalian</th>
            <th>Durasi</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
        <?php 
                if (mysqli_num_rows($result) == 0) {
                    echo "<tr><td colspan='8'>Data tidak ditemukan</td></tr>";
                } else {
                    $i = 1;
                    while ($data = mysqli_fetch_assoc($result)) { ?>
                    <tr>
                <td><?= $i++; ?></td>
                <td><?= htmlspecialchars($data['username']); ?></td>
                <td><?= htmlspecialchars($data['judul']); ?></td>
                <td><?= htmlspecialchars($data['tanggal_peminjaman']); ?></td>
                <td><?= $data['tanggal_pengembalian'] ? htmlspecialchars($data['tanggal_pengembalian']) : '-'; ?></td>
                <td>
    <?php 
    if ($data['status_peminjaman'] == 'dipinjam') {
        if ($data['durasi'] > 0) {
            echo $data['durasi'] . ' hari lagi';
        } else {
            echo 'Terlambat ' . abs($data['durasi']) . ' hari';
        }
    } else {
        if (!empty($data['keterlambatan'])) {
            echo $data['keterlambatan'];
        } else {
            echo $data['durasi'] . ' hari';
        }
    }
    ?>
</td>

<td><?= htmlspecialchars($data['status_peminjaman']); ?></td>
   
                        <td>
    <?php if ($data['status_peminjaman'] == 'dikembalikan') { ?>
        <span class="badge bg-success">Selesai</span>
    <?php } ?>

    <?php if (isset($_SESSION['user']) && $_SESSION['user']['level'] != 'peminjam' && $data['status_peminjaman'] == 'dipinjam') { ?>
        <form method="post" class="d-inline">
            <input type="hidden" name="id_peminjaman" value="<?= $data['id_peminjaman']; ?>">
            <button type="submit" name="dikembalikan" class="btn btn-success btn-sm">kembalikan</button>
        </form>
    <?php } ?>
    <?php if (isset($_SESSION['user']) && $_SESSION['user']['level'] == 'admin') { ?>
        <a onclick="return confirm('Apakah anda yakin menghapus data ini?');" href="?page=peminjaman_hapus&id=<?= $data['id_peminjaman']; ?>" class="btn btn-danger btn-sm">
            <i class="fas fa-trash-alt"></i>
        </a>
    <?php } ?>
</td>

                        </td>
                    </tr>
                    <?php } 
                } ?>
            </tbody>
        </table>
        
        
    
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
$(document).ready(function(){
    $("#searchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#peminjamanTable tbody tr").filter(function() {
            $(this).toggle($(this).find('td').text().toLowerCase().indexOf(value) > -1);
        });
    });
});

    </script>
</body>