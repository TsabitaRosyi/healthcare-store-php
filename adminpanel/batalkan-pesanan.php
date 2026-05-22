<?php
session_start();
require "../koneksi.php";

// Pastikan hanya user yang login yang bisa akses
if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Silakan login terlebih dahulu.'); window.location='login.php';</script>";
    exit;
}

if (isset($_POST['order_id'])) {
    $order_id = intval($_POST['order_id']);
    $user_id = $_SESSION['user_id'];

    // Update status_pengiriman menjadi "Dibatalkan"
    $update = mysqli_query($con, "UPDATE orders SET status_pengiriman = 'Dibatalkan' WHERE id = '$order_id' AND user_id = '$user_id'");

    if ($update) {
        echo "<script>alert('Pesanan berhasil dibatalkan.'); window.location='pesanan.php';</script>";
    } else {
        echo "<script>alert('Gagal membatalkan pesanan.'); window.location='pesanan.php';</script>";
    }
} else {
    echo "<script>alert('Permintaan tidak valid.'); window.location='pesanan.php';</script>";
}
?>
