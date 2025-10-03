<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['confirm_pinjam'])) {
    $id_buku = intval($_POST['id_buku']);
    $id_user = $_SESSION['user']['id_user'];
    $tanggal_pinjam = $_POST['tanggal_pinjam'];
    $tanggal_kembali = $_POST['tanggal_kembali'];

    if (empty($tanggal_pinjam) || empty($tanggal_kembali)) {
        echo "<div class='alert alert-error'>Tanggal peminjaman tidak boleh kosong!</div>";
    } else {
        // Cek status user (aktif atau tidak)
        $cek_status_user = "SELECT status FROM user WHERE id_user = ?";
        $stmt = $koneksi->prepare($cek_status_user);
        $stmt->bind_param("i", $id_user);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        // Jika status user pasif, tidak bisa meminjam buku
        if ($user['status'] == 'pasif') {
            echo "<script>alert('Akun Anda tidak aktif karena masih ada buku yang belum dikembalikan.'); window.location.href='?page=peminjaman_buku';</script>";
            exit();
        }

        // Cek apakah user sudah meminjam buku yang sama
        $cek_peminjaman = "SELECT id_peminjaman FROM peminjaman WHERE id_user = ? AND id_buku = ? AND status_peminjaman = 'dipinjam'";
        $stmt = $koneksi->prepare($cek_peminjaman);
        $stmt->bind_param("ii", $id_user, $id_buku);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // User sudah meminjam buku ini
            echo "<script>alert('Anda sudah meminjam buku ini, harap kembalikan terlebih dahulu!'); window.location.href='?page=peminjamanp';</script>";
            exit();
        } else {
            // Cek stok buku sebelum peminjaman
            $query = "SELECT stok, stok_dipinjam, judul FROM buku WHERE id_buku = ?";
            $stmt = $koneksi->prepare($query);
            $stmt->bind_param("i", $id_buku);
            $stmt->execute();
            $result = $stmt->get_result();
            $buku = $result->fetch_assoc();

            if ($buku && $buku['stok'] > 0) { // Cek jika stok masih tersedia
                // Mulai transaksi
                $koneksi->begin_transaction();
                try {
                    // Tambahkan peminjaman ke database
                    $query_peminjaman = "INSERT INTO peminjaman (id_user, id_buku, tanggal_peminjaman, tanggal_pengembalian, status_peminjaman) VALUES (?, ?, ?, ?, 'dipinjam')";
                    $stmt = $koneksi->prepare($query_peminjaman);
                    $stmt->bind_param("iiss", $id_user, $id_buku, $tanggal_pinjam, $tanggal_kembali);
                    $stmt->execute();

                    // Update stok: kurangi stok dan tambah stok dipinjam
                    $update_stok = "UPDATE buku SET stok = stok - 1, stok_dipinjam = stok_dipinjam + 1 WHERE id_buku = ?";
                    $stmt = $koneksi->prepare($update_stok);
                    $stmt->bind_param("i", $id_buku);
                    $stmt->execute();

                    // Commit transaksi
                    $koneksi->commit();
                    echo "<script>alert('Peminjaman buku \"" . $buku['judul'] . "\" berhasil!'); window.location.href='?page=peminjamanp';</script>";
                    exit();
                } catch (Exception $e) {
                    // Rollback transaksi jika terjadi kesalahan
                    $koneksi->rollback();
                    echo "<div class='alert alert-error'>Terjadi kesalahan: " . htmlspecialchars($e->getMessage()) . "</div>";
                }
            } else {
                echo "<div class='alert alert-error'>Stok buku tidak tersedia!</div>";
            }
        }
    }
}
?>

<!-- Modal Peminjaman -->
<div class="book-container">
    <div id="borrowModal" class="modal">
        <div class="modal-content">
            <span class="close-modal" onclick="closeBorrowModal()">&times;</span>
            <h2 id="modalBookTitle"></h2> <!-- Judul buku akan ditampilkan di sini -->
            <div class="modal-book-info">
                <img id="modalBookImage" src="" alt="Gambar Buku" class="modal-book-image">
                <form method="post" id="borrowForm" onsubmit="return confirmPeminjaman()">
                    <p id="modalBookStock" class="stock-info"></p>
                    <input type="hidden" name="id_buku" id="modalBookId">
                    <div class="tanggal-container">
                        <div class="form-group">
                            <label for="tanggal_pinjam">Tanggal Peminjaman:</label>
                            <input type="date" id="tanggal_pinjam" name="tanggal_pinjam" required>
                        </div>
                        <div class="form-group">
                            <label for="tanggal_kembali">Tanggal Pengembalian:</label>
                            <input type="date" id="tanggal_kembali" name="tanggal_kembali" readonly>
                        </div>
                    </div>
                    <button type="submit" name="confirm_pinjam" class="submit-button">Konfirmasi Peminjaman</button>
                </form>
            </div>
        </div>
    </div>
    
</div>
