<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Digital</title>
    <link rel="stylesheet" href="css/style_modal.css">
</head>
<body>

<div class="header">
    <h2>Peminjaman Digital</h2>
    <p>Mau baca buku apa hari ini?</p>
</div>

<div class="search-box">
    <i class="fas fa-search search-icon"></i>
    <input type="text" id="searchInput" placeholder="Cari judul buku...">
    
    <select id="categoryFilter">
        <option value="">Semua Kategori</option>
        <?php
        // Koneksi ke database
        include 'koneksi.php';
        
        $kategoriQuery = mysqli_query($koneksi, "SELECT * FROM kategori");
        while ($kategori = mysqli_fetch_assoc($kategoriQuery)) {
            echo "<option value='{$kategori['kategori']}'>{$kategori['kategori']}</option>";
        }
        ?>
    </select>
</div>

<?php
include 'koneksi.php';

$query = "
    SELECT b.*, 
           GROUP_CONCAT(k.kategori SEPARATOR ', ') AS kategori
    FROM buku b
    LEFT JOIN buku_kategori bk ON b.id_buku = bk.id_buku
    LEFT JOIN kategori k ON bk.id_kategori = k.id_kategori
    GROUP BY b.id_buku";


$result = $koneksi->query($query);

if (!$result) {
    die("Error pada query: " . $koneksi->error);
}
?>

<div class="book-container" id="bookContainer">
    <?php if ($result->num_rows > 0) { ?>
        <?php while ($buku = $result->fetch_assoc()) { ?>
            <div class="book-card" 
     data-category="<?= htmlspecialchars(strtolower($buku['kategori'] ?: 'tidak ada kategori')); ?>"
     onclick="window.location.href='?page=detail_buku&id=<?= htmlspecialchars($buku['id_buku']); ?>'"
     style="cursor:pointer;">

    <img src="<?= !empty($buku['foto_buku']) ? 'uploads/' . htmlspecialchars($buku['foto_buku']) : 'uploads/default.jpg'; ?>" 
         alt="<?= htmlspecialchars($buku['judul']); ?>" class="book-image">
    <div class="book-info">
        <h3><?= htmlspecialchars($buku['judul']); ?></h3>
        <p><strong>Kategori:</strong> <?= htmlspecialchars($buku['kategori'] ?: 'Tidak ada kategori'); ?></p>
        <p class="stock-info">Stok tersedia: <?= htmlspecialchars($buku['stok']); ?></p>

        <!-- Tombol Pinjam Buku tetap membuka modal -->
        <button class="pinjam-button"
                onclick="event.stopPropagation(); showBorrowModal(<?= htmlspecialchars(json_encode($buku), ENT_QUOTES, 'UTF-8'); ?>)"
                <?= $buku['stok'] <= 0 ? 'disabled' : ''; ?>>
            <?= $buku['stok'] > 0 ? 'Pinjam Buku' : 'Stok Habis'; ?>
        </button>
    </div>
</div>
<p id="noResultsMessage" style="display: none; text-align: center; color: black;">
    Tidak ada buku yang cocok dengan pencarian atau kategori yang dipilih.
</p>
        <?php } ?>
    <?php } else { ?>
        <p>Tidak ada buku yang tersedia.</p>
    <?php } ?>

</div>

<!-- Import Modal -->
<?php include 'modal_pinjam.php'; ?>

<script src="js/modal.js"></script>

</body>
</html>
