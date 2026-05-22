<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">

    <style>
        body {
            background-color: #f8f9fa;
        }

        .header-banner {
            width: 100%;
            height: 400px;
            object-fit: cover;
            border-radius: 0 0 30px 30px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            display: block;
            position: relative;
            z-index: 0;
        }

        .overlay-dark {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.4);
            z-index: 1;
            border-radius: 0 0 30px 30px;
        }

        .welcome-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            text-align: center;
            text-shadow: 2px 2px 6px rgba(0,0,0,0.6);
            z-index: 2;
            padding: 20px;
        }

        .features i {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .footer {
            background-color: #343a40;
            color: white;
            padding: 40px 0 20px;
            margin-top: 100px;
        }

        .breadcrumb {
            background: transparent;
            padding: 0;
        }

        @media (max-width: 768px) {
            .header-banner {
                height: 300px;
            }
            .welcome-text h1 {
                font-size: 28px;
            }
            .welcome-text p {
                font-size: 16px;
            }
        }
    </style>
</head>

<body>
    <?php require "navbar.php"; ?>

    <!-- Header Section -->
    <div class="position-relative">
        <!-- Gambar banner -->
        <img src="../assets/header.png" alt="Banner Health Care from Tsabi" class="header-banner">

        <!-- Overlay gelap -->
        <div class="overlay-dark"></div>

        <!-- Teks sambutan -->
        <div class="welcome-text">
            <h1 class="display-4 fw-bold">Health Care from Tsabi</h1>
            <p class="lead">Toko Alat Kesehatan Terpercaya & Berkualitas</p>
        </div>
    </div>

    <!-- Content Section -->
    <div class="container py-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <i class="fas fa-home"></i> Home
                </li>
            </ol>
        </nav>

        <h3 class="mb-3">Halo, 
            <?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Pengguna'; ?> 👋
        </h3>

        <p class="mb-5">Selamat datang di <strong>Health Care from Tsabi</strong>. Kami menyediakan berbagai perlengkapan kesehatan terbaik untuk menunjang kebutuhan Anda. Dapatkan pelayanan maksimal, produk terpercaya, dan pengalaman belanja yang mudah.</p>

        <div class="row text-center features">
            <div class="col-md-4 mb-4">
                <i class="fas fa-truck text-primary"></i>
                <h5 class="mt-3">Pengiriman Cepat</h5>
                <p class="text-muted">Barang dikirim dalam waktu 1-2 hari kerja ke seluruh Indonesia.</p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="fas fa-shield-alt text-success"></i>
                <h5 class="mt-3">Produk Terjamin</h5>
                <p class="text-muted">Semua produk original, bergaransi, dan bersertifikat resmi.</p>
            </div>
            <div class="col-md-4 mb-4">
                <i class="fas fa-headset text-warning"></i>
                <h5 class="mt-3">Layanan 24/7</h5>
                <p class="text-muted">Tim kami siap membantu kapan saja dengan layanan ramah.</p>
            </div>
        </div>
    </div>

    <!-- Footer Section -->
    <footer class="footer text-center">
        <div class="container">
            <p class="mb-2">© <?= date("Y") ?> <strong>Health Care from Tsabi</strong>. All rights reserved.</p>
            <p>
                <i class="fas fa-map-marker-alt"></i> Surabaya, Indonesia &nbsp; | &nbsp;
                <i class="fas fa-phone-alt"></i> +62 815 545 26400 &nbsp; | &nbsp;
                <i class="fas fa-envelope"></i> support@healthcaretsabi.com
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
