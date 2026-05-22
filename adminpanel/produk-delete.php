<?php
session_start();
require "../koneksi.php";

// Cek apakah ada ID yang dikirimkan
if (!isset($_GET['id'])) {
    header("Location: produk-admin.php");
    exit();
}

$id = intval($_GET['id']);

// Cek apakah produk dengan ID tersebut ada
$query = mysqli_query($con, "SELECT * FROM produk WHERE id = $id");
$data = mysqli_fetch_assoc($query);

if ($data) {
    // Hanya hapus data dari database (gambar tetap disimpan di folder)
    $delete = mysqli_query($con, "DELETE FROM produk WHERE id = $id");

    if ($delete) {
        echo "<script>
            alert('Produk berhasil dihapus!');
            window.location.href = 'produk-admin.php';
        </script>";
    } else {
        echo "<script>
            alert('Gagal menghapus produk dari database!');
            window.location.href = 'produk-admin.php';
        </script>";
    }
} else {
    echo "<script>
        alert('Produk tidak ditemukan!');
        window.location.href = 'produk-admin.php';
    </script>";
}
?>
