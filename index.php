<?php
include "koneksi.php";

if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit;
}

// Ambil username dan inisial pertama
$nama = $_SESSION['user']['nama'];
$initial = strtoupper(substr($nama, 0, 1)); // Ambil huruf pertama dan ubah ke huruf besar

// Array gradasi biru untuk setiap huruf inisial
$blueShades = [
    'A' => '#1E3A8A', // Biru Navy
    'B' => '#2563EB', // Biru Standar
    'C' => '#3B82F6', // Biru Cerah
    'D' => '#60A5FA', // Biru Muda
    'E' => '#93C5FD', // Biru Langit
    'F' => '#BFDBFE', // Biru Pastel
    'G' => '#1D4ED8', // Biru Royal
    'H' => '#3B5998', // Biru Facebook
    'I' => '#0EA5E9', // Biru Cyan
    'J' => '#0284C7', // Biru Medium
    'K' => '#0369A1', // Biru Dongker
    'L' => '#0F172A', // Biru Gelap
    'M' => '#1E40AF', // Biru Indigo
    'N' => '#38BDF8', // Biru Aqua
    'O' => '#7DD3FC', // Biru Laut
    'P' => '#1C64F2', // Biru Terang
    'Q' => '#1D9BF0', // Biru Twitter
    'R' => '#60A5FA', // Biru Soft
    'S' => '#93C5FD', // Biru Langit Terang
    'T' => '#2563EB', // Biru Standar Lagi
    'U' => '#3B82F6', // Biru Cerah Lagi
    'V' => '#1E40AF', // Biru Indigo Lagi
    'W' => '#0284C7', // Biru Medium Lagi
    'X' => '#0EA5E9', // Biru Cyan Lagi
    'Y' => '#1D4ED8', // Biru Royal Lagi
    'Z' => '#0F172A'  // Biru Gelap Lagi
];

// Set warna berdasarkan inisial, jika tidak ada default ke biru standar
$profileColor = $blueShades[$initial] ?? '#2563EB';

// Ambil halaman yang sedang dibuka
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Perpustakaan Digital</title>
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

    <style>
        :root {
            --primaryy-color: #4a90e2;
            --primary-color: #2C3E50;
            --secondary-color: #E74C3C;
            --accent-color: #3498DB;
            --background-color: #ECF0F1;
        }

        .sb-topnav {
            background: #24364B!important;
            padding: 12px;
        }

        .navbar-brand {
            font-size: 18px;
            font-weight: bold;
            color: #fff;
        }

        .sb-sidenav {
            background: #24364B;
            height: 100vh;
            padding: 10px;
        }

        .sb-sidenav .nav-link {
            display: flex;
            align-items: center;
            padding: 10px 12px;
            color: #ffffff;
            font-size: 15px;
            font-weight: 500;
            border-radius: 5px;
            transition: background 0.3s, color 0.3s;
        }

        .sb-sidenav .nav-link.active {
            background: linear-gradient(134deg, #2C3E50, #4a90e2) !important;
            color: #fff !important;
        }
                /* Menghilangkan scrollbar dari seluruh halaman */
        * {
            -ms-overflow-style: none;  /* IE dan Edge */
            scrollbar-width: none;  /* Firefox */
        }

        /* Menghilangkan scrollbar untuk Chrome, Safari dan Opera */
        *::-webkit-scrollbar {
            display: none;
        }

        /* Pastikan konten sidebar tetap bisa di-scroll tanpa menampilkan scrollbar */
        #layoutSidenav_nav, 
        .sb-sidenav-menu,
        #layoutSidenav_content {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        #layoutSidenav_nav::-webkit-scrollbar, 
        .sb-sidenav-menu::-webkit-scrollbar,
        #layoutSidenav_content::-webkit-scrollbar {
            display: none;
        }

        /* Memastikan tinggi sidebar sesuai dengan konten */
        .sb-sidenav {
            height: 100%;
            overflow-y: auto;
        }

        /* Profil User - Avatar */
        .profile-card {
            display: flex;
            align-items: center;
            padding: 10px;
            background: #1F2A38;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .profile-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
            border: 3px solid; /* Warna border mengikuti warna background */
            margin-right: 10px;
        }

        .profile-info h6 {
            margin: 0;
            font-size: 14px;
            font-weight: bold;
            color: #ffffff;
        }

        .profile-info .badge {
            font-size: 12px;
            font-weight: bold;
            padding: 4px 8px;
        }

        footer {
            text-align: center;
            background: #2C3E50;
            color: white;
            
        }
    </style>
</head>
<body class="sb-nav-fixed">
<?php

// Pastikan pengguna sudah login
if (!isset($_SESSION['user']['username'])) {
    header("Location: login.php");
    exit;

}

date_default_timezone_set('Asia/Jakarta');

// Ambil tanggal & jam sekarang
$tanggal_sekarang = date('l, d F Y');
$jam_sekarang = date('H:i:s');

// Tentukan sapaan berdasarkan jam
$jam = date('H');
// Tentukan sapaan berdasarkan waktu saat ini
$hour = date('H');
if ($hour >= 5 && $hour < 12) {
    $sapaan = "Selamat Pagi";
} elseif ($hour >= 12 && $hour < 15) {
    $sapaan = "Selamat Siang";
} elseif ($hour >= 15 && $hour < 18) {
    $sapaan = "Selamat Sore";
} else {
    $sapaan = "Selamat Malam";
}

// Tanggal hari ini
$tanggal = date('l, d F Y');

// Waktu sekarang saat pertama kali halaman dimuat
$waktu_sekarang = date('H:i');

// Ambil nama pengguna & tanggal login terakhir
$nama_pengguna = isset($_SESSION['user']['username']) ? $_SESSION['user']['username'] : "Pengguna";
$tanggal_login = isset($_SESSION['user']['last_login']) ? date('l, d F Y H:i:s', strtotime($_SESSION['user']['last_login'])) : "Belum ada data";
?>




        <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <ul class="navbar-nav ">


            <!-- Sidebar Toggle-->
           
           <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i class="fas fa-bars"></i></button>
</ul>         
            <ul class="navbar-nav mx-auto" >
            <li class="nav-item">
        <span class="nav-link text-white">
            <?php echo "Hi, $sapaan, $nama_pengguna!"; ?>
        </span>
        </li>
    <li class="nav-item" >
        <span class="nav-link text-white">
             <span id="waktu-sekarang"><?php echo $waktu_sekarang; ?></span>
        </span>
    </li>
</ul>

        <!-- Profil Pengguna -->
        <?php if ($_SESSION['user']['level'] != 'peminjam') { ?>
        <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                    <i class="fas fa-user fa-fw"></i> <?php echo ucfirst($_SESSION['user']['level']); ?>
                </a>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
        <?php } ?>
    </nav>

    <div id="layoutSidenav">
        <!-- Sidebar -->
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark">
                <div class="sb-sidenav-menu" >
                    <!-- Profil User -->
                    <br><div class="profile-card" >
    <div class="profile-avatar" style="background-color: <?php echo $profileColor; ?>; border-color: <?php echo $profileColor; ?>;">
        <?php echo $initial; ?>
    </div>
    <div class="profile-info">
        <h6><?php echo $nama; ?></h6>
        <small><?php echo $_SESSION['user']['email']; ?></small>
    </div>
<br>
</div>

                    <!-- Menu Navigasi -->
                    <div class="nav">
                        <div class="sb-sidenav-menu-heading">Core</div>
                        <a class="nav-link <?php echo ($page == 'home') ? 'active' : ''; ?>" href="index.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-th-large"></i></div>
                            Dashboard
                        </a>
                        <?php if ($_SESSION['user']['level'] != 'peminjam') { ?>
                            <a class="nav-link <?php echo ($page == 'user_kelola') ? 'active' : ''; ?>" href="?page=user_kelola">
                                <div class="sb-nav-link-icon"><i class="fas fa-users"></i></div>
                                user
                            </a>
                        <div class="sb-sidenav-menu-heading">Navigasi</div>
                        
                            <a class="nav-link <?php echo ($page == 'buku') ? 'active' : ''; ?>" href="?page=buku">
                                <div class="sb-nav-link-icon"><i class="fas fa-book"></i></div>
                                Buku
                            </a>
                            
                            <a class="nav-link <?php echo ($page == 'kategori') ? 'active' : ''; ?>" href="?page=kategori">
                                <div class="sb-nav-link-icon"><i class="fas fa-tags"></i></div>
                                Kategori Buku
                        </a>
                        <a class="nav-link <?php echo ($page == 'ulasan') ? 'active' : ''; ?>" href="?page=ulasan">
                                <div class="sb-nav-link-icon"> <i class="fas fa-comments"></i></div>
                                ulasan
                        </a>
                        
                                <?php } ?>
                        <?php if ($_SESSION['user']['level'] == 'peminjam') { ?>
                            <div class="sb-sidenav-menu-heading">Navigasi</div>
                            <a class="nav-link <?php echo ($page == 'peminjaman_buku') ? 'active' : ''; ?>" href="?page=peminjaman_buku">
                                <div class="sb-nav-link-icon"><i class="fas fa-hand-holding"></i></div>
                                pinjam buku
                            </a>
                        <?php } ?>
                        <a class="nav-link <?php echo (isset($_GET['page']) && ($_GET['page'] == 'peminjaman' || $_GET['page'] == 'peminjamanp'))  ? 'active' : ''; ?>"  
   href="<?php echo (isset($_SESSION['user']['level']) && ($_SESSION['user']['level'] == 'admin' || $_SESSION['user']['level'] == 'petugas')) ? '?page=peminjaman' : '?page=peminjamanp'; ?>">

   <div class="sb-nav-link-icon"><i class="fas fa-hand-holding"></i></div>
   Peminjaman
</a>

                        <?php if ($_SESSION['user']['level'] == 'admin') { ?>
                            <a class="nav-link <?php echo ($page == 'laporan') ? 'active' : ''; ?>" href="?page=laporan">
                                <div class="sb-nav-link-icon"><i class="fas fa-file-alt"></i></div>
                                Laporan
                            </a>
                        <?php } ?>
                        

                        <a class="nav-link text-danger" href="logout.php">
                            <div class="sb-nav-link-icon"><i class="fas fa-power-off"></i></div>
                            Logout
                        </a>
                    </div>
                </div>
            </nav>
        </div>

        <!-- Content -->
        <div id="layoutSidenav_content">
            <main>
                <div class="container-fluid px-4">
                    <?php
                    if (file_exists($page . '.php')) {
                        include $page . '.php';
                    } else {
                        include '404.php';
                    }
                    ?>
                </div>
            </main>

            <!-- Footer -->
            <footer>
                <p>© pussstakaaaan dewi</p>
            </footer>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
        <script src="js/scripts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="assets/demo/chart-area-demo.js"></script>
        <script src="assets/demo/chart-bar-demo.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>
        <script src="js/datatables-simple-demo.js"></script>
</body>
</html>
