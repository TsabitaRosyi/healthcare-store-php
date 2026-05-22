<?php
require "../koneksi.php";

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    mysqli_query($con, "UPDATE orders SET status_pengiriman = 'Terkirim' WHERE id = $id");
}

header("Location: shipping-admin.php");
exit();
?>