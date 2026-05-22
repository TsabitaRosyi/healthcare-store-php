<?php
session_start();
require "../koneksi.php";

$user_id = $_SESSION['user_id'];
$orders = mysqli_query($con, "SELECT * FROM orders WHERE user_id = '$user_id' ORDER BY tanggal DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php require "navbar.php"; ?>

<div class="container mt-5">
    <h3>Riwayat Pesanan Anda</h3>
    <table class="table table-bordered mt-3">
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Status Pengiriman</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($orders)) : ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= date("d-m-Y", strtotime($row['tanggal'])) ?></td>
                <td>Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                <td><?= $row['status_pengiriman'] ?></td>
                <td>
                    <?php if ($row['status_pengiriman'] == 'Belum Dikirim') : ?>
                        <form action="batalkan-pesanan.php" method="POST" onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?');">
                            <input type="hidden" name="order_id" value="<?= $row['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Batalkan</button>
                        </form>
                    <?php elseif ($row['status_pengiriman'] == 'Dibatalkan') : ?>
                        <span class="text-danger">Sudah Dibatalkan</span>
                    <?php else: ?>
                        <span class="text-muted">Tidak dapat dibatalkan</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
