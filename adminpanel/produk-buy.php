<?php
session_start();
require '../koneksi.php';

$idProduk = isset($_GET['id']) ? $_GET['id'] : '';

if ($idProduk) {
    // Cek apakah produk valid
    $query = mysqli_query($con, "SELECT * FROM produk WHERE id = '$idProduk'");
    if (mysqli_num_rows($query) > 0) {
        // Tambahkan ke session keranjang
        if (!isset($_SESSION['keranjang'])) {
            $_SESSION['keranjang'] = [];
        }

        if (isset($_SESSION['keranjang'][$idProduk])) {
            $_SESSION['keranjang'][$idProduk]++;
        } else {
            $_SESSION['keranjang'][$idProduk] = 1;
        }

        // Redirect ke keranjang
        header("Location: keranjang.php");
        exit;
    } else {
        echo "Produk tidak ditemukan.";
    }
} else {
    echo "ID produk tidak valid.";
}
?>
