<?php
include 'koneksi.php'; // Pastikan koneksi ke database sudah ada

?>

<div class="card">

    <style>
        .navbar-custom {
            background: linear-gradient(135deg, rgb(4, 15, 27), rgb(30, 211, 30));
            padding: 10px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: white;
            font-size: 16px;
            font-weight: bold;
            border-radius: 8px;
        }
        .notif {
            background: #ffcccc;
            color: red;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
            font-weight: bold;
        }
        .navbar-custom h1 {
            margin: 0;
        }
        .bg-purple {
            background: linear-gradient(135deg, rgb(136, 153, 173), rgb(61, 66, 102)) !important;
        }
        .text-white {
            color: white !important;
        }
        .card-footer .text-white, .small.text-white {
            color: white !important;
        }
        .card-body i {
            margin-bottom: 10px;
            color: white;
        }
    </style>

    <!-- Navbar -->
    <div class="card-body">
    <div class="navbar-custom">
        <h1>Dashboard</h1>
    </div>
    </div>
    <div class="card-body">
        <div class="row">
            <?php if ($_SESSION['user']['level'] != 'peminjam') { ?>
                <!-- Total Peminjam -->
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-purple text-white mb-4">
                        <div class="card-body">
                        <i class="fas fa-users fa-2x"></i>
                            <br>
                            <?php
                            $result = mysqli_query($koneksi, "SELECT COUNT(DISTINCT id_user) FROM peminjaman");
                            echo mysqli_fetch_array($result)[0];
                            ?>
                            Total Anggota Peminjam
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="index.php?page=user_kelola">View Details</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
                 <!-- Total kategori -->
                 <div class="col-xl-3 col-md-6">
                    <div class="card bg-purple text-white mb-4">
                        <div class="card-body">
                        <i class="fas fa-tags fa-2x"></i>
                            <br>
                            <?php echo mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM kategori")); ?>
                            Total kategori
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="index.php?page=kategori">View Details</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>


                <!-- Total Buku -->
                <div class="col-xl-3 col-md-6">
                    <div class="card bg-purple text-white mb-4">
                        <div class="card-body">
                            <i class="fas fa-book fa-2x"></i>
                            <br>
                            <?php echo mysqli_num_rows(mysqli_query($koneksi, "SELECT * FROM buku")); ?>
                            Total Buku
                        </div>
                        <div class="card-footer d-flex align-items-center justify-content-between">
                            <a class="small text-white stretched-link" href="index.php?page=buku">View Details</a>
                            <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                        </div>
                    </div>
                </div>
            <?php } ?>

  <!-- Total Buku Dipinjam -->
  <div class="col-xl-3 col-md-6">
                <div class="card bg-purple text-white mb-4">
                    <div class="card-body">
                        <i class="fas fa-book-open fa-2x"></i>
                        <br>
                        <?php
                        if ($_SESSION['user']['level'] == 'peminjam') {
                            $query_dipinjam = mysqli_query($koneksi, "SELECT COUNT(*) FROM peminjaman WHERE status_peminjaman = 'dipinjam' AND id_user = '{$_SESSION['user']['id_user']}'");
                        } else {
                            $query_dipinjam = mysqli_query($koneksi, "SELECT COUNT(*) FROM peminjaman WHERE status_peminjaman = 'dipinjam'");
                        }
                        echo mysqli_fetch_array($query_dipinjam)[0];
                        ?>
                        Total Buku Dipinjam
                    </div>
                    <?php if ($_SESSION['user']['level'] == 'peminjam') { ?>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="index.php?page=peminjamanp">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                    <?php } ?>
                    <?php if ($_SESSION['user']['level'] != 'peminjam') { ?>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="index.php?page=peminjaman">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                    <?php } ?>
                </div>
            </div>

            <!-- Total Buku Dikembalikan -->
            <div class="col-xl-3 col-md-6">
                <div class="card bg-purple text-white mb-4">
                    <div class="card-body">
                        <i class="fas fa-undo fa-2x"></i>
                        <br>
                        <?php
                        if ($_SESSION['user']['level'] == 'peminjam') {
                            $query_dikembalikan = mysqli_query($koneksi, "SELECT COUNT(*) FROM peminjaman WHERE status_peminjaman = 'dikembalikan' AND id_user = '{$_SESSION['user']['id_user']}'");
                        } else {
                            $query_dikembalikan = mysqli_query($koneksi, "SELECT COUNT(*) FROM peminjaman WHERE status_peminjaman = 'dikembalikan'");
                        }
                        echo mysqli_fetch_array($query_dikembalikan)[0];
                        ?>
                        Total Buku Dikembalikan
                    </div>
                    <?php if ($_SESSION['user']['level'] == 'peminjam') { ?>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="index.php?page=peminjamanp">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                    <?php } ?>
                    <?php if ($_SESSION['user']['level'] != 'peminjam') { ?>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="index.php?page=peminjaman">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                    <?php } ?>
                </div>
            </div>
         <!-- Total Buku terlambat -->
         <div class="col-xl-3 col-md-6">
                <div class="card bg-purple text-white mb-4">
                    <div class="card-body">
                        
                    <i class="fas fa-clock fa-2x"></i>
                        <br>
                        <?php
                        $query_terlambat = ($_SESSION['user']['level'] == 'peminjam') ?
                            mysqli_query($koneksi, "SELECT COUNT(*) FROM peminjaman WHERE status_peminjaman = 'dikembalikan' AND id_user = '{$_SESSION['user']['id_user']}' AND tanggal_pengembalian > DATE_ADD(tanggal_peminjaman, INTERVAL 7 DAY)") :
                            mysqli_query($koneksi, "SELECT COUNT(*) FROM peminjaman WHERE status_peminjaman = 'dikembalikan' AND tanggal_pengembalian > DATE_ADD(tanggal_peminjaman, INTERVAL 7 DAY)");
                        echo mysqli_fetch_array($query_terlambat)[0];
                        ?>
                        Total Buku keterlambatan
                    </div>
                    <?php if ($_SESSION['user']['level'] == 'peminjam') { ?>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="index.php?page=peminjamanp">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                    <?php } ?>
                    <?php if ($_SESSION['user']['level'] != 'peminjam') { ?>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="index.php?page=peminjaman">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                    <?php } ?>
                </div>
            </div>
             <!-- Total ulasan -->
             <div class="col-xl-3 col-md-6">
                <div class="card bg-purple text-white mb-4">
                    <div class="card-body">
                    <i class="fas fa-comments fa-2x"></i>
                        <br>
                        <?php
                        $query_ulasan = ($_SESSION['user']['level'] == 'peminjam') ?
                            mysqli_query($koneksi, "SELECT COUNT(*) FROM ulasan WHERE id_user = '{$_SESSION['user']['id_user']}'") :
                            mysqli_query($koneksi, "SELECT COUNT(*) FROM ulasan");
                        echo mysqli_fetch_array($query_ulasan)[0];
                        ?>
                        Total  ulasan
                    </div>
                    <?php if ($_SESSION['user']['level'] == 'peminjam') { ?>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="index.php?page=peminjamanp">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                    <?php } ?>
                    <?php if ($_SESSION['user']['level'] != 'peminjam') { ?>
                    <div class="card-footer d-flex align-items-center justify-content-between">
                        <a class="small text-white stretched-link" href="index.php?page=ulasan">View Details</a>
                        <div class="small text-white"><i class="fas fa-angle-right"></i></div>
                    </div>
                    <?php } ?>
                </div>
            </div>
            <div class="card-body">
    <?php if ($_SESSION['user']['level'] == 'peminjam') { ?>
        <?php
        $user_id = mysqli_real_escape_string($koneksi, $_SESSION['user']['id_user']);
        
        // Query untuk buku yang akan dipinjam dalam beberapa hari ke depan
        $query_akan_dipinjam = mysqli_query($koneksi, "SELECT b.judul, DATEDIFF(p.tanggal_peminjaman, CURDATE()) AS selisih_hari 
            FROM peminjaman p 
            JOIN buku b ON p.id_buku = b.id_buku 
            WHERE p.id_user = '$user_id' 
            AND p.tanggal_peminjaman >= CURDATE()");

        // Query untuk buku yang sudah terlambat dikembalikan
        $query_terlambat = mysqli_query($koneksi, "SELECT b.judul 
            FROM peminjaman p 
            JOIN buku b ON p.id_buku = b.id_buku 
            WHERE p.id_user = '$user_id' 
            AND p.status_peminjaman = 'dipinjam' 
            AND p.tanggal_pengembalian < CURDATE()");
        ?>
        
        <div class="notif-container">
            <?php if (mysqli_num_rows($query_akan_dipinjam) > 0) { ?>
                <div class="notif">
                    <h4>Buku yang akan diambil dalam beberapa hari :</h4>
                    <ul>
                        <?php while ($row = mysqli_fetch_assoc($query_akan_dipinjam)) { ?>
                            <li><?php echo htmlspecialchars($row['judul']); ?> 
                                (Akan diambil dalam <?php echo $row['selisih_hari']; ?> hari)</li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>
            
            <?php if (mysqli_num_rows($query_terlambat) > 0) { ?>
                <div class="notif">
                    <h4>Buku yang terlambat dikembalikan :</h4>
                    <ul>
                        <?php while ($row = mysqli_fetch_assoc($query_terlambat)) { ?>
                            <li> Buku (<?php echo htmlspecialchars($row['judul']); ?>) mohon segera dikembalikan</li>
                        <?php } ?>
                    </ul>
                </div>
            <?php } ?>
        </div>
    <?php } ?>
</div>

<style>
    .notif-container {
        display: flex;
        gap: 20px; /* Jarak antar elemen */
        justify-content: space-between; /* Menyusun agar ada jarak */
    }

    .notif {
        flex: 1; /* Agar masing-masing kotak memiliki ukuran yang sama */
        background: #f8f9fa;
        padding: 15px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .notif h4 {
        margin-bottom: 10px;
        font-size: 16px;
        color: #333;
    }

    .notif ul {
        list-style-type: none;
        padding: 0;
        margin: 0;
    }

    .notif ul li {
        font-size: 14px;
        color: #555;
        margin-bottom: 5px;
    }
</style>



    </div>
</div>
















