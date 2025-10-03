<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Peminjaman Buku</title>
    <link rel="stylesheet" href="css/awal_buku.css">
    <style>

        .search-box-container {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 15px;
    background: #f8f9fa;
    padding: 10px;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    justify-content: flex-start;
}

.search-laporan-wrapper {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}

.search-laporan-wrapper label {
    display: flex;
    align-items: center;
    gap: 5px;
    background: #fff;
    padding: 5px 10px;
    border-radius: 6px;
    border: 1px solid #ccc;
    cursor: pointer;
    transition: 0.3s;
}

.search-laporan-wrapper label:hover {
    background: #e9ecef;
}

#searchFields {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    align-items: center;
    margin-top: 10px;
}

.search-input-wrapper {
    position: relative;
    display: flex;
    align-items: center;
    width: auto;
}

.search-input, select {
    padding: 8px 12px;
    border-radius: 6px;
    border: 1px solid #ccc;
    width: 200px;
    transition: all 0.3s ease;
}

.search-input:focus, select:focus {
    box-shadow: 0 0 0 2px rgba(74, 144, 226, 0.2);
    border-color: #4a90e2;
}

@media (max-width: 768px) {
    .search-box-container {
        flex-direction: column;
        align-items: stretch;
    }

    #searchFields {
        flex-direction: column;
    }

    .search-input, select {
        width: 100%;
    }
}

    </style>
</head>
<body class="bg-light">
<div class="container mt-4">
    <div class="dashboard-header">
        <h2><i class="fas fa-file-alt me-2"></i> Laporan Peminjaman Buku</h2>
        <p class="mb-0">Lihat dan kelola data peminjaman buku</p>
    </div>

    <div class="card">
        <div class="card-body">
            <a href="#" id="cetakLaporan" class="btn btn-primary mb-3">
                <i class="fa fa-print"></i> Cetak Data
            </a>
            
            <div class="search-box-container">
    <div class="search-laporan-wrapper">
        <label><input type="checkbox" class="search-checkbox" value="tanggal_peminjaman"> Tanggal Peminjaman</label>
        <label><input type="checkbox" class="search-checkbox" value="tanggal_pengembalian"> Tanggal Pengembalian</label>
        <label><input type="checkbox" class="search-checkbox" value="judul_buku"> Judul Buku</label>
        <label><input type="checkbox" class="search-checkbox" value="status_peminjaman"> Status Peminjaman</label>
    </div>
</div>

<div id="searchFields">
    <div class="search-input-wrapper" id="searchInput1Wrapper" style="display: none;">
        <input type="date" id="searchInput1" class="form-control search-input">
    </div>

    <div class="search-input-wrapper" id="searchInput2Wrapper" style="display: none;">
        <input type="date" id="searchInput2" class="form-control search-input">
    </div>

    <div class="search-input-wrapper" id="searchInputJudulWrapper" style="display: none;">
        <input type="text" id="searchInputJudul" class="form-control search-input" placeholder="Cari judul buku...">
    </div>

    <div class="search-input-wrapper" id="searchInputStatusWrapper" style="display: none;">
        <select id="searchInputStatus" class="form-control">
            <option value="">Semua Status</option>
            <option value="Dipinjam">Dipinjam</option>
            <option value="Dikembalikan">Dikembalikan</option>
        </select>
    </div>
</div>



            <div class="table-responsive mt-3">
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
                        include 'koneksi.php';
                        $i = 1;
                        $query = mysqli_query($koneksi, "SELECT * FROM peminjaman 
                            LEFT JOIN user ON user.id_user = peminjaman.id_user 
                            LEFT JOIN buku ON buku.id_buku = peminjaman.id_buku");
                        while ($data = mysqli_fetch_array($query)) {
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
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Ambil elemen checkbox dan input pencarian
    const checkboxes = document.querySelectorAll('.search-checkbox');
    const inputWrappers = {
        tanggal_peminjaman: document.getElementById('searchInput1Wrapper'),
        tanggal_pengembalian: document.getElementById('searchInput2Wrapper'),
        judul_buku: document.getElementById('searchInputJudulWrapper'),
        status_peminjaman: document.getElementById('searchInputStatusWrapper'),
    };

    const searchInputs = {
        tanggal_peminjaman: document.getElementById('searchInput1'),
        tanggal_pengembalian: document.getElementById('searchInput2'),
        judul_buku: document.getElementById('searchInputJudul'),
        status_peminjaman: document.getElementById('searchInputStatus'),
    };

    // Fungsi untuk menampilkan atau menyembunyikan input berdasarkan checkbox yang dipilih
    function updateSearchInputs() {
        Object.keys(inputWrappers).forEach(key => {
            inputWrappers[key].style.display = document.querySelector(`input[value="${key}"]:checked`) ? "block" : "none";
        });
        filterTable();
    }

    // Event listener untuk checkbox
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateSearchInputs);
    });

    // Fungsi untuk memfilter tabel berdasarkan input yang diisi
    function filterTable() {
        let rows = document.querySelectorAll('tbody tr');

        rows.forEach(row => {
            let match = true;

            let data = {
                tanggal_peminjaman: row.cells[3]?.textContent.trim() || '',
                tanggal_pengembalian: row.cells[4]?.textContent.trim() || '',
                judul_buku: row.cells[2]?.textContent.trim().toLowerCase() || '',
                status_peminjaman: row.cells[5]?.textContent.trim().toLowerCase() || '',
            };

            Object.keys(searchInputs).forEach(key => {
                if (document.querySelector(`input[value="${key}"]:checked`)) {
                    let searchValue = searchInputs[key].value.trim().toLowerCase();
                    if (searchValue && !data[key].includes(searchValue)) {
                        match = false;
                    }
                }
            });

            row.style.display = match ? '' : 'none';
        });
    }

    // Tambahkan event listener ke input pencarian
    Object.values(searchInputs).forEach(input => {
        input.addEventListener('input', filterTable);
        input.addEventListener('change', filterTable);
    });

    // Fungsi untuk mencetak laporan berdasarkan filter yang dipilih
    document.getElementById('cetakLaporan').addEventListener('click', function (event) {
    event.preventDefault();

    let params = Object.keys(searchInputs)
        .filter(key => document.querySelector(`input[value="${key}"]:checked`) && searchInputs[key].value)
        .map(key => `${key}=${encodeURIComponent(searchInputs[key].value)}`)
        .join('&');

    let url = params ? `cetak.php?${params}` : `cetak.php`;

    window.open(url, "_blank");
});


});


</script>
</body>
</html>