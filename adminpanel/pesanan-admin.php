<?php
session_start();
require "../koneksi.php";
require "session.php"; // validasi admin login (jika ada)

// Ambil semua pesanan dari tabel `orders`
$query = mysqli_query($con, "SELECT orders.*, users.username 
    FROM orders 
    JOIN users ON orders.user_id = users.id 
    ORDER BY orders.id DESC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Admin - Pesanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h2 class="mb-4">📦 Daftar Pesanan Pelanggan</h2>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Metode</th>
                <th>Bank</th>
                <th>Total</th>
                <th>Tanggal</th>
                <th>Status Pengiriman</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($query)): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= $row['metode'] ?></td>
                <td><?= $row['bank'] ?></td>
                <td>Rp <?= number_format($row['total'], 0, ',', '.') ?></td>
                <td><?= $row['tanggal'] ?></td>
                <td><?= $row['status_pengiriman'] ?></td>
                <td>
                    <?php if ($row['status_pengiriman'] == 'Belum Dikirim'): ?>
                        <a href="ubah-status-pesanan.php?id=<?= $row['id'] ?>&to=Dikirim" class="btn btn-sm btn-warning">Kirim</a>
                    <?php elseif ($row['status_pengiriman'] == 'Dikirim'): ?>
                        <a href="ubah-status-pesanan.php?id=<?= $row['id'] ?>&to=Selesai" class="btn btn-sm btn-success">Selesai</a>
                    <?php else: ?>
                        <span class="text-muted">✔</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>
</html>
