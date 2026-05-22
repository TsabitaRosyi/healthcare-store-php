<?php
session_start();
require "../koneksi.php";

// Cek apakah pengguna sudah login dan memiliki peran 'admin'
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses ditolak. Anda bukan admin.'); window.location='../login.php';</script>";
    exit();
}

// Ambil jumlah kategori dan produk dari database
$querykategori = mysqli_query($con, "SELECT * FROM kategori");
$jumlahkategori = mysqli_num_rows($querykategori);

$queryproduk = mysqli_query($con, "SELECT * FROM produk");
$jumlahproduk = mysqli_num_rows($queryproduk);

// Hitung jumlah entri di tabel orders, shop_request, dan orders dengan status pengiriman yang belum terkirim
$jumlahGuestBook = mysqli_num_rows(mysqli_query($con, "SELECT id FROM orders"));
$jumlahShopRequest = mysqli_num_rows(mysqli_query($con, "SELECT id FROM shop_request WHERE status = 'pending'"));

// Hitung jumlah pesanan yang status_pengiriman-nya "Belum Dikirim" saja
$resultShipping = mysqli_query($con, "SELECT COUNT(*) AS jumlah FROM orders WHERE status_pengiriman = 'Belum Dikirim'");
$dataShipping = mysqli_fetch_assoc($resultShipping);
$jumlahShipping = $dataShipping['jumlah'];

// Hitung jumlah pesanan yang status_pengiriman-nya "Dibatalkan"
$resultBatal = mysqli_query($con, "SELECT COUNT(*) AS jumlah FROM orders WHERE status_pengiriman = 'Dibatalkan'");
$dataBatal = mysqli_fetch_assoc($resultBatal);
$jumlahBatal = $dataBatal['jumlah'];


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        .summary-box {
            background-color: #f1ac6c;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            color: white;
            height: 100%;
        }

        .summary-box .icon {
            font-size: 5rem;
        }

        .no-decoration {
        text-decoration: none;
    }
    </style>
</head>
<body>

<?php require "navbar-admin.php"; ?>

<div class="container mt-5">
    <h1 class="text-center">Welcome Toko Alat Kesehatan</h1>
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active" aria-current="page">
                <a href="proses-admin.php" class="no-decoration text-muted">
                <i class="fas fa-home"></i> Admin
                </a>
            </li>
        </ol>
    </nav>

    <h2 class="mb-4">Halo, <?= htmlspecialchars($_SESSION['username'] ?? 'Pengguna') ?></h2>

    <div class="row g-4">
        <!-- Kategori -->
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="summary-box">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-align-justify icon text-dark"></i>
                    </div>
                    <div>
                        <h3 class="fs-4">Kategori</h3>
                        <p class="fs-5"><?= $jumlahkategori ?> Kategori</p>
                        <a href="kategori.php" class="btn btn-light btn-sm">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Produk -->
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="summary-box">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-box icon text-dark"></i>
                    </div>
                    <div>
                        <h3 class="fs-4">Produk</h3>
                        <p class="fs-5"><?= $jumlahproduk ?> Produk</p>
                        <a href="produk-admin.php" class="btn btn-light btn-sm">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Guest Book -->
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="summary-box">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-book icon text-dark"></i>
                    </div>
                    <div>
                        <h3 class="fs-4">Guest Book</h3>
                        <p class="fs-5"><?= $jumlahGuestBook ?> Entri Buku Tamu</p>
                        <a href="guestbook-admin.php" class="btn btn-light btn-sm">Lihat</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shop Requests -->
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="summary-box">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-store icon text-dark"></i>
                    </div>
                    <div>
                        <h3 class="fs-4">Shop Requests</h3>
                        <p class="fs-5"> <?= $jumlahShopRequest ?> Permintaan Toko</p>
                        <a href="shoprequest-admin.php" class="btn btn-light btn-sm">Lihat</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Shipping Orders -->
        <div class="col-lg-4 col-md-6 col-sm-12">
            <div class="summary-box">
                <div class="d-flex">
                    <div class="me-3">
                        <i class="fas fa-truck icon text-dark"></i>
                    </div>
                    <div>
                        <h3 class="fs-4">Shipping</h3>
                        <p class="fs-5"> <?= $jumlahShipping ?> Pesanan Belum Terkirim</p>
                        <p class="text-danger"><?= $jumlahBatal ?> Pesanan Dibatalkan</p>
                        <a href="shipping-admin.php" class="btn btn-light btn-sm">Lihat</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>

</body>
</html>
