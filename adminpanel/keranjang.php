<?php
session_start();
require "../koneksi.php";

$keranjang = isset($_SESSION['keranjang']) ? $_SESSION['keranjang'] : [];
$total = 0;
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Keranjang Belanja</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f9f9f9;
        }

        .no-decoration {
            text-decoration: none;
        }

        .breadcrumb {
            background: none;
            padding-left: 0;
        }

        .breadcrumb-item a {
            color: #6c757d;
        }

        .breadcrumb-item a:hover {
            color: #343a40;
        }

        .breadcrumb-item.active {
            color: #343a40;
        }

        .table thead th {
            background-color: #f7c04a;
            color: #212529;
            text-align: center;
        }

        .btn-sm {
            width: 32px;
            height: 32px;
            padding: 0;
            font-weight: bold;
        }

        .btn-success {
            background-color: #28a745;
            border: none;
        }

        .btn-danger {
            background-color: #dc3545;
            border: none;
        }

        .total-belanja {
            font-size: 20px;
            font-weight: 600;
            color: #212529;
            text-align: right;
        }

        .card-keranjang {
            background-color: #fff;
            padding: 20px;
            border-radius: 16px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        }

        .judul-keranjang {
            font-weight: 600;
            color: #333;
        }
    </style>
</head>

<body>
    <?php require "navbar.php"; ?>

    <div class="container mt-5">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="home.php" class="no-decoration"><i class="fas fa-home"></i> Home</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="produk.php" class="no-decoration">Produk</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Keranjang</li>
            </ol>
        </nav>

        <!-- Keranjang Section -->
        <div class="card-keranjang mt-4">
            <h3 class="text-center mb-4 judul-keranjang">Keranjang Belanja</h3>

            <?php if (empty($keranjang)): ?>
                <p class="text-center">Keranjang kosong. Silakan <a href="produk.php">belanja produk</a> terlebih dahulu.</p>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Nama Produk</th>
                                <th class="text-center">Jumlah</th>
                                <th>Harga Satuan</th>
                                <th>Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $no = 1;
                            foreach ($keranjang as $id => $jumlah):
                                $produkQuery = mysqli_query($con, "SELECT * FROM produk WHERE id = '$id'");
                                $produk = mysqli_fetch_assoc($produkQuery);
                                $subtotal = $produk['harga'] * $jumlah;
                                $total += $subtotal;
                            ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td><?= htmlspecialchars($produk['nama']) ?></td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center align-items-center gap-2">
                                        <a href="keranjang-action.php?aksi=kurang&id=<?= $id ?>" class="btn btn-sm btn-danger">-</a>
                                        <span><?= $jumlah ?></span>
                                        <a href="keranjang-action.php?aksi=tambah&id=<?= $id ?>" class="btn btn-sm btn-success">+</a>
                                    </div>
                                </td>
                                <td>Rp <?= number_format($produk['harga'], 0, ',', '.') ?></td>
                                <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                            </tr>
                            <?php endforeach; ?>
                            <tr>
                                <td colspan="4" class="text-end"><strong>Total Belanja (Termasuk Pajak):</strong></td>
                                <td><strong>Rp <?= number_format($total, 0, ',', '.') ?></strong></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="text-end mt-3">
                    <div class="d-flex justify-content-between mt-3">
                        <a href="produk.php" class="btn btn-warning">
                            <i class="fas fa-plus"></i> Tambah Produk
                        </a>
                        <a href="checkout.php" class="btn btn-primary">
                            Lanjut ke Checkout
                        </a>
                    </div>
            <?php endif; ?>

        </div>
    </div>

    <!-- Bootstrap Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
