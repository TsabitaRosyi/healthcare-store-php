<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require "../koneksi.php";

// Cek apakah user boleh mengajukan toko
$showAjukan = false;
if (isset($_SESSION['login']) && $_SESSION['role'] === 'user') {
    $user_id = $_SESSION['user_id'];
    $cek = mysqli_query($con, "SELECT * FROM shop_request WHERE owner_id = $user_id AND status = 'approved'");
    if (mysqli_num_rows($cek) === 0) {
        $showAjukan = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Navbar</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

  <!-- Bootstrap Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <style>
    .navbar-brand img {
      height: 40px;
      width: auto;
    }
  </style>
</head>
<body>

<!-- Navbar Start -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#000000;">
  <div class="container-fluid align-items-center">

    <!-- Logo -->
    <a class="navbar-brand" href="home.php">
      <img src="../assets/logo_login.png" alt="Logo" class="d-inline-block align-text-top">
    </a>

    <!-- Toggler for mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02"
      aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar Content -->
    <div class="collapse navbar-collapse justify-content-center" id="navbarTogglerDemo02">
      <ul class="navbar-nav mb-2 mb-lg-0">

        <!-- Menu Home -->
        <li class="nav-item me-4">
          <a class="nav-link active text-white fw-bold" href="home.php">Home</a>
        </li>

        <!-- Menu Produk -->
        <li class="nav-item me-4">
          <a class="nav-link text-white fw-bold" href="produk.php">Produk</a>
        </li>

        <!-- Menu Keranjang -->
        <li class="nav-item me-4">
          <a class="nav-link text-white fw-bold" href="keranjang.php">
            <i class="bi bi-cart-fill"></i> Keranjang
          </a>
        </li>

        <?php if (isset($_SESSION['user_id'])): ?>
          <!-- Menu Pesanan -->
          <li class="nav-item me-4">
            <a class="nav-link text-white fw-bold" href="pesanan.php">
              <i class="bi bi-clipboard2-check-fill"></i> Pesanan
            </a>
          </li>

          <!-- Menu Ajukan Toko (jika belum punya toko aktif) -->
          <?php if (isset($_SESSION['login']) && $_SESSION['role'] === 'user'): ?>
            <li class="nav-item me-4">
              <a class="nav-link text-white fw-bold" href="ajukan-toko.php">
                <i class="bi bi-shop-window"></i> Ajukan
              </a>
            </li>
          <?php endif; ?>

          <!-- Menu Logout -->
          <li class="nav-item me-4">
            <a class="nav-link text-white fw-bold" href="logout.php">
              <i class="bi bi-box-arrow-right"></i> Logout
            </a>
          </li>
        <?php else: ?>
          <!-- Menu Daftar -->
          <li class="nav-item me-4">
            <a class="nav-link text-white fw-bold" href="register.php">
              <i class="bi bi-person-plus-fill"></i> Daftar
            </a>
          </li>
        <?php endif; ?>
      </ul>

      <!-- Icon Akun di Pojok Kanan -->
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link text-white" href="<?php 
              if (isset($_SESSION['user_id'])) {
                  echo 'profil.php';
              } else {
                  echo 'pilihan.php';
              }
          ?>">
            <i class="bi bi-person-circle fs-4"></i>
          </a>
        </li>
      </ul>
    </div>

  </div>
</nav>
<!-- Navbar End -->

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
