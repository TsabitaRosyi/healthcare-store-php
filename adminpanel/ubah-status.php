<?php
require "../koneksi.php";

if (isset($_GET['id']) && isset($_GET['to'])) {
    $id = intval($_GET['id']);
    $status = mysqli_real_escape_string($con, $_GET['to']);

    // Update status_pengiriman di tabel orders
    $query = mysqli_query($con, "UPDATE orders SET status_pengiriman = '$status' WHERE id = $id");

    if ($query) {
        header("Location: pesanan-admin.php");
        exit;
    } else {
        echo "Gagal mengubah status.";
    }
}
?>
