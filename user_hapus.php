<?php
$id = $_GET['id'];
$query= mysqli_query($koneksi,"DELETE FROM user WHERE id_user=$id");
?>
<script>
    alert('hapus data berhasil');
    location.href = "index.php?page=user_kelola";
    </script>