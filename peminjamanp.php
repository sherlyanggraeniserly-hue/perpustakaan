
<?php
include 'koneksi.php';

$user_id = $_SESSION['user']['id_user'];

$sql = "SELECT p.id_peminjaman, u.username, b.id_buku, b.judul, b.foto_buku, 
        b.penulis, b.penerbit, b.tahun_terbit, b.foto_buku,
        p.tanggal_peminjaman, p.tanggal_pengembalian, 
        p.status_peminjaman, 
        GROUP_CONCAT(DISTINCT k.kategori ORDER BY k.kategori SEPARATOR ', ') AS kategori
        FROM peminjaman p
        JOIN user u ON p.id_user = u.id_user
        JOIN buku b ON p.id_buku = b.id_buku
        LEFT JOIN buku_kategori bk ON b.id_buku = bk.id_buku
        LEFT JOIN kategori k ON bk.id_kategori = k.id_kategori
        WHERE p.id_user = '$user_id'
        GROUP BY p.id_peminjaman
        ORDER BY p.tanggal_peminjaman DESC";  


$result = $koneksi->query($sql);

// Query untuk mengambil ulasan
$sql_ulasan = "SELECT ul.id_ulasan, ul.ulasan, ul.reting, p.id_peminjaman 
               FROM ulasan ul
               JOIN peminjaman p ON ul.id_peminjaman = p.id_peminjaman
               WHERE p.id_user = '$user_id'
               ORDER BY ul.id_ulasan DESC";

$result_ulasan = $koneksi->query($sql_ulasan);

// Simpan ulasan dalam array
$ulasan_map = [];
while ($row = $result_ulasan->fetch_assoc()) {
    $ulasan_map[$row['id_peminjaman']][] = $row;
}

function hitungDurasi($tanggal_peminjaman, $tanggal_pengembalian) {
    $tgl_peminjaman = new DateTime($tanggal_peminjaman);
    $tgl_pengembalian = new DateTime($tanggal_pengembalian);
    $tgl_sekarang = new DateTime();

    // Jika tanggal peminjaman belum tiba
    if ($tgl_peminjaman > $tgl_sekarang) {
        $selisih = $tgl_sekarang->diff($tgl_peminjaman)->days;

        // Jika hari ini tanggal 25 dan peminjaman tanggal 26, maka "Akan diambil besok"
        if ($tgl_peminjaman->format('Y-m-d') === $tgl_sekarang->modify('+1 day')->format('Y-m-d')) {
            return "Akan diambil besok";
        }

        return "Akan diambil dalam " . $selisih . " hari";
    }

    // Hitung selisih antara hari ini dan tanggal pengembalian
    $selisih = $tgl_sekarang->diff($tgl_pengembalian)->days;

    if ($tgl_sekarang > $tgl_pengembalian) {
        return "Terlambat " . $selisih . " hari!";
    }
    return $selisih . " hari lagi";
}

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Peminjaman Buku</title>
    <!-- Font Awesome untuk icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="css/style_riwayat.css">
</head>
<body>
    <div class="container">
        <div class="header">
            <h2><i class="fas fa-book-reader"></i> Riwayat Peminjaman Buku</h2>
        </div>
        
    <!-- Sections untuk pengelompokan -->          
        <div class="tabs" align = "center">
            <div class="tab active" onclick="showSection('dipinjam')">
                <i class="fas fa-clock"></i> Sedang Dipinjam
            </div>
            <div class="tab" onclick="showSection('dikembalikan')">
                <i class="fas fa-check-circle"></i> Dikembalikan
            </div>
            <div class="tab" onclick="showSection('diulas')">
                <i class="fas fa-star"></i> Sudah Diulas
            </div>
        </div>


  <!-- Sections untuk dipinjam mengikuti format yang sama -->
        <div id="dipinjam" class="section active">
        <?php 
            $ada_peminjaman = false;
            foreach ($result as $row): 
                if ($row['status_peminjaman'] == 'dipinjam'):
                    $ada_peminjaman = true;
            ?>
                <div class="book-entry">
                    <div class="top-info">
                        <div class="info-item">
                            <i class="fas fa-user"></i>
                            <?= htmlspecialchars($row['username']) ?>
                        </div>
                        <span class="status-badge status-dipinjam">
    <i class="fas fa-clock"></i>
    <?= hitungDurasi($row['tanggal_peminjaman'], $row['tanggal_pengembalian']) ?>
</span>

                    </div>
                    <div class="book-content">
                <div class="book-image">
                    <?php if (!empty($row['foto_buku'])) { ?>
                        <img src="uploads/<?php echo htmlspecialchars($row['foto_buku']); ?>" class="book-img" alt="Gambar Buku">
                    <?php } else { ?>
                        <img src="uploads/default.jpg" class="book-img" alt="Gambar Default">
                    <?php } ?>
                </div>
                        <div class="book-details">
                            <div class="book-title">
                                <i class="fas fa-book"></i>
                                <?= htmlspecialchars($row['judul']) ?>
                            </div>
                            <div class="book-info">
                                <div class="info-item">
                                    <i class="fas fa-user-edit"></i>
                                    <?= htmlspecialchars($row['penulis']) ?>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-building"></i>
                                    <?= htmlspecialchars($row['penerbit']) ?>
                                </div>
                                <div class="info-item">
                                    <i class="fas fa-calendar"></i>
                                    <?= htmlspecialchars($row['tahun_terbit']) ?>
                                </div>
                            </div>
                            <div class="book-info">
                                <div class="info-item">
                                <i class="fas fa-calendar"></i>
                                <?= htmlspecialchars($row['tanggal_peminjaman']) ?> ||
                                <i class="fas fa-calendar"></i>
                                <?= htmlspecialchars($row['tanggal_pengembalian']) ?> 
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; endforeach; 
            if (!$ada_peminjaman) echo "<br><p align='center'>Tidak ada buku yang sedang dipinjam.</p></br>";
            ?>
        </div>

  <!-- Sections untuk dikembalikan  mengikuti format yang sama -->
        <div id="dikembalikan" class="section">
        <?php 
            $ada_dikembalikan = false;
            foreach ($result as $row): 
                if ($row['status_peminjaman'] == 'dikembalikan'):
                    $ada_dikembalikan = true;
            ?>
                <div class="book-entry">
            <div class="top-info">
                <div class="info-item">
                    <i class="fas fa-user"></i>
                    <?= htmlspecialchars($row['username']) ?>
                </div>
                <span class="status-badge status-dikembalikan">
                    <i class="fas fa-check-circle"></i> Dikembalikan
                </span>
            </div>

            <div class="book-content">
                <div class="book-image">
                    <?php if (!empty($row['foto_buku'])) { ?>
                        <img src="uploads/<?php echo htmlspecialchars($row['foto_buku']); ?>" class="book-img" alt="Gambar Buku">
                    <?php } else { ?>
                        <img src="uploads/default.jpg" class="book-img" alt="Gambar Default">
                    <?php } ?>
                </div>
                <div class="book-details">
                    <div class="book-title">
                        <i class="fas fa-book"></i>
                        <?= htmlspecialchars($row['judul']) ?>
                    </div>
                    <div class="book-info">
                        <div class="info-item">
                            <i class="fas fa-user-edit"></i>
                            <?= htmlspecialchars($row['penulis']) ?>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-building"></i>
                            <?= htmlspecialchars($row['penerbit']) ?>
                        </div>
                        <div class="info-item">
                            <i class="fas fa-calendar"></i>
                            <?= htmlspecialchars($row['tahun_terbit']) ?>
                        </div>
                    </div>
                    <div class="book-info">
                        <div class="info-item">
                            <i class="fas fa-calendar"></i>
                            <?= htmlspecialchars($row['tanggal_peminjaman']) ?> ||
                            <i class="fas fa-calendar"></i>
                            <?= htmlspecialchars($row['tanggal_pengembalian']) ?> 
                        </div>
                    </div>
                </div>
            </div>

            <div class="actions">
    <?php if (!isset($ulasan_map[$row['id_peminjaman']])): ?>
        <a href="?page=ulasan_tambah&id_peminjaman=<?= htmlspecialchars($row['id_peminjaman']); ?>" class="btn btn-primary">
            <i class="fas fa-star"></i> Beri Nilai
        </a>
    <?php endif; ?>
    
    <a href="?page=detail_buku&id=<?= htmlspecialchars($row['id_buku']); ?>" class="btn btn-success">
                <i class="fas fa-redo"></i> Pinjam Lagi
                </a>
            </div>
        </div>


        <?php endif; endforeach; 
            if (!$ada_dikembalikan) echo "<br><p align='center'>Tidak ada buku yang dikembalikan.</p></br>";
            ?>
</div>

  <!-- Sections untuk diulasan mengikuti format yang sama -->
<div id="diulas" class="section">
            <?php 
            $ada_ulasan = false;
            foreach ($result as $row): 
                if ($row['status_peminjaman'] == 'dikembalikan' && isset($ulasan_map[$row['id_peminjaman']])): 
                    foreach ($ulasan_map[$row['id_peminjaman']] as $ulasan):
                        $ada_ulasan = true;
            ?>
                <div class="book-entry">
                    <div class="top-info">
                        <div class="info-item">
                            <i class="fas fa-user"></i>
                            <?= htmlspecialchars($row['username']) ?>
                        </div>
                        <span class="status-badge status-dikembalikan">
                            <i class="fas fa-check-circle"></i> Sudah Diulas
                        </span>
                    </div>

                    <div class="book-content">
                        <div class="book-image">
                            <?php if (!empty($row['foto_buku'])) { ?>
                                <img src="uploads/<?php echo htmlspecialchars($row['foto_buku']); ?>" class="book-img" alt="Gambar Buku">
                            <?php } else { ?>
                                <img src="uploads/default.jpg" class="book-img" alt="Gambar Default">
                            <?php } ?>
                        </div>
                        <div class="book-details">
                            <div class="book-title">
                                <i class="fas fa-book"></i>
                                <?= htmlspecialchars($row['judul']) ?>
                            </div>
                            <div class="book-info">
                                <div class="info-item">
                                    <i class="fas fa-calendar"></i>
                                    <?= htmlspecialchars($row['tanggal_peminjaman']) ?> ||
                                    <i class="fas fa-calendar"></i>
                                    <?= htmlspecialchars($row['tanggal_pengembalian']) ?> 
                                </div>
                            </div>

                            <!-- Menampilkan ulasan dan rating -->
                            <div class="review">
                                <p><strong>Ulasan:</strong> <?= htmlspecialchars($ulasan['ulasan']) ?></p>
                                <p><strong>Rating:</strong> <?= str_repeat('⭐', $ulasan['reting']) ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="actions">
                        <a href="?page=ulasan_ubah&id_ulasan=<?= htmlspecialchars($ulasan['id_ulasan']); ?>" class="btn btn-warning">
                            <i class="fas fa-edit"></i> Edit Ulasan
                        </a>
                        <a onclick="return confirm('Apakah anda yakin menghapus data ini?');" 
   href="ulasan_hapus.php?id=<?= htmlspecialchars($ulasan['id_ulasan']); ?>&redirect=peminjam" 
   class="btn btn-danger btn-sm">
   <i class="fas fa-trash"></i> Hapus Ulasan
</a>


                    </div>
                </div>
                <?php endforeach; endif; endforeach; 
            if (!$ada_ulasan) echo "<br><p align='center'>Tidak ada ulasan buku.</p></br>";
            ?>
</div>

        
    </div>

    <script>
    function showSection(id) {
        document.querySelectorAll('.section').forEach(sec => sec.style.display = 'none');
        document.getElementById(id).style.display = 'block';
        document.querySelectorAll('.tab').forEach(tab => tab.classList.remove('active'));
        event.target.classList.add('active');
    }
    document.addEventListener("DOMContentLoaded", function () {
        document.querySelector(".tab").classList.add("active");
    });
    
    </script>

</body>
</html>
<?php $koneksi->close(); ?>
