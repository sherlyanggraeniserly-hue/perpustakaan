
<?php
include 'koneksi.php';

if (!isset($_GET['id'])) {
    echo "<script>window.location.href='?page=peminjaman';</script>";
    exit();
}

$id_buku = intval($_GET['id']);

// Query untuk mengambil detail buku
$query = "
    SELECT b.*, 
           GROUP_CONCAT(k.kategori SEPARATOR ', ') AS kategori
    FROM buku b
    LEFT JOIN buku_kategori bk ON b.id_buku = bk.id_buku
    LEFT JOIN kategori k ON bk.id_kategori = k.id_kategori
    WHERE b.id_buku = ?
    GROUP BY b.id_buku";

$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $id_buku);
$stmt->execute();
$result = $stmt->get_result();
$buku = $result->fetch_assoc();

if (!$buku) {
    echo "<script>window.location.href='?page=peminjaman';</script>";
    exit();
}

$id_buku = intval($_GET['id']);
$limit_options = [5, 10, 15, 20, 25];
$limit = isset($_GET['limit']) && in_array($_GET['limit'], $limit_options) ? intval($_GET['limit']) : 5;
$page = isset($_GET['page']) && is_numeric($_GET['page']) && $_GET['page'] > 0 ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Hitung total ulasan
$query_total = "SELECT COUNT(*) AS total FROM ulasan WHERE id_buku = ?";
$stmt_total = $koneksi->prepare($query_total);
$stmt_total->bind_param("i", $id_buku);
$stmt_total->execute();
$result_total = $stmt_total->get_result();
$total_ulasan = $result_total->fetch_assoc()['total'];

$total_pages = ceil($total_ulasan / $limit);

// Query untuk mengambil ulasan dengan pagination
$query_ulasan = "
    SELECT u.*, us.username 
    FROM ulasan u
    JOIN user us ON u.id_user = us.id_user
    WHERE u.id_buku = ?
    ORDER BY u.tanggal DESC
    LIMIT ? OFFSET ?";
$stmt_ulasan = $koneksi->prepare($query_ulasan);
$stmt_ulasan->bind_param("iii", $id_buku, $limit, $offset);
$stmt_ulasan->execute();
$result_ulasan = $stmt_ulasan->get_result();
?>

<div class="detail-container">
    <div class="book-detail">
        <div class="book-image-container">
            <img src="<?= !empty($buku['foto_buku']) ? 'uploads/' . htmlspecialchars($buku['foto_buku']) : 'uploads/default.jpg'; ?>" 
                 alt="<?= htmlspecialchars($buku['judul']); ?>" class="detail-book-image">
        </div>
        <div class="book-info-detail">
            <h1><?= htmlspecialchars($buku['judul']); ?></h1>
            <div class="book-metadata">
                <p><strong>✍️ Penulis:</strong> <?= htmlspecialchars($buku['penulis']); ?></p>
                <p><strong>🏢 Penerbit:</strong> <?= htmlspecialchars($buku['penerbit']); ?>
                <strong>📅 Tahun Terbit:</strong> <?= htmlspecialchars($buku['tahun_terbit']); ?></p>
                <p><strong>🏷️ Kategori:</strong> <?= htmlspecialchars($buku['kategori']); ?>
                <strong>📦 Stok Tersedia:</strong> <?= htmlspecialchars($buku['stok']); ?></p>
            </div>
            <div class="description">
                <h3>📝 Deskripsi</h3>
                <p><?= nl2br(htmlspecialchars($buku['deskripsi'] ?? 'Tidak ada deskripsi')); ?></p>
            </div>
            <button class="pinjam-button"
                    onclick="event.stopPropagation(); showBorrowModal(<?= htmlspecialchars(json_encode($buku), ENT_QUOTES, 'UTF-8'); ?>)"
                    <?= $buku['stok'] <= 0 ? 'disabled' : ''; ?>>
                <?= $buku['stok'] > 0 ? 'Pinjam Buku' : 'Stok Habis'; ?>
            </button>
        </div>
    </div>

    <div class="reviews-section">
        <h2>💭 Ulasan Buku</h2>

        <form method="GET" class="limit-form">
            <input type="hidden" name="id" value="<?= $id_buku; ?>">
            <label for="limit">Tampilkan:</label>
            <select name="limit" id="limit" onchange="this.form.submit()">
                <?php foreach ($limit_options as $option): ?>
                    <option value="<?= $option; ?>" <?= $limit == $option ? 'selected' : ''; ?>><?= $option; ?> per halaman</option>
                <?php endforeach; ?>
            </select>
        </form>

        <table class="reviews-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Rating</th>
                    <th>Tanggal</th>
                    <th>Ulasan</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($result_ulasan->num_rows > 0): ?>
                    <?php while ($ulasan = $result_ulasan->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($ulasan['username']); ?></td>
                            <td>
                                <p><?= str_repeat('⭐', $ulasan['reting']) ?></p>
                            </td>
                            <td><?= date('d F Y', strtotime($ulasan['tanggal'])); ?></td>
                            <td><?= nl2br(htmlspecialchars($ulasan['ulasan'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="4" class="no-reviews">Belum ada ulasan untuk buku ini.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?id=<?= $id_buku; ?>&limit=<?= $limit; ?>&page=<?= $page - 1; ?>">&laquo; Prev</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <a href="?id=<?= $id_buku; ?>&limit=<?= $limit; ?>&page=<?= $i; ?>" 
                   class="<?= $i == $page ? 'active' : ''; ?>"><?= $i; ?></a>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?id=<?= $id_buku; ?>&limit=<?= $limit; ?>&page=<?= $page + 1; ?>">Next &raquo;</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php include 'modal_pinjam.php'; ?>
<script src="js/modal.js"></script>
<link rel="stylesheet" href="css/style_modal.css">
<link rel="stylesheet" href="css/detail_buku.css">
<style>
    /* General Styles */
.detail-container {
    max-width: 1200px;
    margin: 2rem auto;
    padding: 0 20px;
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
}

/* Book Detail Section */
.book-detail {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 2rem;
    background: white;
    border-radius: 15px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 2rem;
    margin-bottom: 2rem;
}

.book-info-detail {
    padding: 1rem 0;
}

.book-info-detail h1 {
    font-size: 2rem;
    color: #2c3e50;
    margin-bottom: 1.5rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.book-info-detail h1 i {
    color: #3498db;
    margin-right: 10px;
}

.book-metadata {
    background: #f8f9fa;
    padding: 1.5rem;
    border-radius: 10px;
    margin-bottom: 1.5rem;
}

.book-metadata p {
    margin: 0.8rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #555;
}

.book-metadata p strong {
    color: #2c3e50;
    min-width: 120px;
}

</style>