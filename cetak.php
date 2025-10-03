<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman Buku</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            background: white;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h2 {
            color: #2c3e50;
            font-size: 24px;
            margin-bottom: 10px;
        }
        .header p {
            color: #7f8c8d;
            margin: 5px 0;
        }
        .logo {
            font-size: 40px;
            color: #2c3e50;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
            background: white;
        }
        th, td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #3498db;
            color: white;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #f5f5f5;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
            color: #7f8c8d;
        }
        @media print {
            body {
                margin: 20px;
            }
            .no-print {
                display: none;
            }
            @page {
                margin: 0;
            }
            body {
                margin: 1.6cm;
            }
        }
    </style>
</head>
<body onload="window.print(); setTimeout(() => window.close(), 100);">

<?php
include 'koneksi.php';

// Ambil parameter dari URL
$tanggal_peminjaman = isset($_GET['tanggal_peminjaman']) ? $_GET['tanggal_peminjaman'] : '';
$tanggal_pengembalian = isset($_GET['tanggal_pengembalian']) ? $_GET['tanggal_pengembalian'] : '';
$judul_buku = isset($_GET['judul_buku']) ? $_GET['judul_buku'] : '';
$status_peminjaman = isset($_GET['status_peminjaman']) ? $_GET['status_peminjaman'] : '';

// Mulai membangun query
$query = "SELECT * FROM peminjaman 
          LEFT JOIN user ON user.id_user = peminjaman.id_user 
          LEFT JOIN buku ON buku.id_buku = peminjaman.id_buku 
          WHERE 1";

// Filter berdasarkan tanggal peminjaman
if ($tanggal_peminjaman) {
    $query .= " AND peminjaman.tanggal_peminjaman = '$tanggal_peminjaman'";
}

// Filter berdasarkan tanggal pengembalian
if ($tanggal_pengembalian) {
    $query .= " AND peminjaman.tanggal_pengembalian = '$tanggal_pengembalian'";
}

// Filter berdasarkan judul buku
if ($judul_buku) {
    $query .= " AND buku.judul LIKE '%$judul_buku%'";
}

// Filter berdasarkan status peminjaman
if ($status_peminjaman) {
    $query .= " AND peminjaman.status_peminjaman = '$status_peminjaman'";
}

$result = mysqli_query($koneksi, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cetak Laporan Peminjaman Buku</title>
</head>
<body>
    <div class="header">
    <div class="logo">📚</div>
    <h2>LAPORAN PEMINJAMAN BUKU</h2>
    <p>Perpustakaan Digital</p>
    <p>Tanggal Cetak: <?php echo date('d-m-Y'); ?></p>
</div>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>User</th>
                            <th>Buku</th>
                            <th>Tanggal Peminjaman</th>
                            <th>Tanggal Pengembalian</th>
                            <th>Status Peminjaman</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $i = 1;
                        while ($data = mysqli_fetch_array($result)) {
                        ?>
                        <tr>
                            <td><?php echo $i++; ?></td>
                            <td><?php echo $data['username']; ?></td>
                            <td><?php echo $data['judul']; ?></td>
                            <td><?php echo $data['tanggal_peminjaman']; ?></td>
                            <td><?php echo $data['tanggal_pengembalian']; ?></td>
                            <td><?php echo $data['status_peminjaman']; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        window.print();
    </script>
</body>
</html>
