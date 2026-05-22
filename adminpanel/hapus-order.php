<?php
session_start();
require "../koneksi.php";

// Pastikan hanya admin yang bisa akses
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses ditolak.'); window.location='../login.php';</script>";
    exit();
}

// Ambil ID order
$id = intval($_GET['id'] ?? 0);

// Hapus data dari tabel order_items terlebih dahulu
mysqli_query($con, "DELETE FROM order_items WHERE order_id = $id");

// Baru hapus dari tabel orders
mysqli_query($con, "DELETE FROM orders WHERE id = $id");

echo "<script>alert('Pesanan berhasil dihapus.'); window.location='shipping-admin.php';</script>";
