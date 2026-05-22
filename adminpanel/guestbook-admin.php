<?php
session_start();
require "../koneksi.php";

// Cek akses admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses ditolak.'); window.location='../login.php';</script>";
    exit();
}

// Ambil data orders beserta nama user
$query = mysqli_query($con, "
    SELECT o.*, u.username 
    FROM orders o 
    LEFT JOIN users u ON o.user_id = u.id 
    ORDER BY o.tanggal DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Guest Book Entries</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>

<style>
    .no-decoration {
        text-decoration: none;
    }
</style>

<body>

    <?php require "navbar-admin.php"; ?>

    <div class="container mt-5">
        
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="proses-admin.php" class="no-decoration text-muted">
                        <i class="fas fa-home"></i> Admin
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Guest Book</li>
            </ol>
        </nav>

        <!-- Judul -->
        <h2 class="mb-4">Guest Book Entries (Pesanan User)</h2>

        <!-- Tabel Pesanan -->
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Username</th>
                    <th>Nama</th>
                    <th>Alamat</th>
                    <th>Bank</th>
                    <th>Metode</th>
                    <th>Contact</th>
                    <th>Tanggal</th>
                    <th>Total</th>
                    <th>Detail Pesanan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($query)) : ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= htmlspecialchars($row['username']) ?></td>
                    <td><?= htmlspecialchars($row['nama']) ?></td>
                    <td><?= htmlspecialchars($row['alamat']) ?></td>
                    <td><?= htmlspecialchars($row['bank']) ?></td>
                    <td><?= htmlspecialchars($row['metode']) ?></td>
                    <td><?= htmlspecialchars($row['contact_no']) ?></td>
                    <td><?= htmlspecialchars($row['tanggal']) ?></td>
                    <td>Rp<?= number_format($row['total'], 0, ',', '.') ?></td>
                    <td>
                        <ul class="mb-0 ps-3">
                            <?php
                            $order_id = $row['id'];
                            $items = mysqli_query($con, "
                                SELECT oi.*, p.nama 
                                FROM order_items oi
                                JOIN produk p ON oi.produk_id = p.id
                                WHERE oi.order_id = $order_id
                            ");
                            while ($item = mysqli_fetch_assoc($items)) {
                                echo "<li>" . htmlspecialchars($item['nama']) . 
                                    " (" . $item['jumlah'] . "x) - Rp" . number_format($item['subtotal'], 0, ',', '.') . "</li>";
                            }
                            ?>
                        </ul>
                    </td>
                    <td>
                        <a href="hapus-order.php?id=<?= $row['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus pesanan ini?')">Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    </div>

<!-- JS Bootstrap (jika butuh interaktivitas seperti dropdown/modal) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>


</body>
</html>
