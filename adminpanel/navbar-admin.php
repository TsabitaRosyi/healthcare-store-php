<?php
  if (session_status() === PHP_SESSION_NONE) {
      session_start();
  }
  require "../koneksi.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Navbar Admin</title>
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

<!-- Navbar Admin Start -->
<nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#000000;">
  <div class="container-fluid align-items-center">

    <!-- Logo -->
    <a class="navbar-brand" href="proses-admin.php">
      <img src="../assets/logo_login.png" alt="Logo" class="d-inline-block align-text-top">
    </a>

    <!-- Toggler for mobile -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarAdmin"
      aria-controls="navbarAdmin" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Navbar Content -->
    <div class="collapse navbar-collapse justify-content-center" id="navbarAdmin">
      <ul class="navbar-nav mb-2 mb-lg-0">

        <li class="nav-item me-4">
          <a class="nav-link active text-white fw-bold" href="proses-admin.php">Dashboard</a>
        </li>

        <li class="nav-item me-4">
          <a class="nav-link text-white fw-bold" href="kategori.php">Kategori</a>
        </li>

        <li class="nav-item me-4">
          <a class="nav-link text-white fw-bold" href="produk-admin.php">Produk</a>
        </li>

        <li class="nav-item me-4">
          <a class="nav-link text-white fw-bold" href="guestbook-admin.php">Guest Book</a>
        </li>

        <li class="nav-item me-4">
          <a class="nav-link text-white fw-bold" href="shoprequest-admin.php">Shop Requests</a>
        </li>

        <li class="nav-item me-4">
          <a class="nav-link text-white fw-bold" href="shipping-admin.php">Shipping</a>
        </li>

        <li class="nav-item me-4">
          <a class="nav-link text-white fw-bold" href="logout.php">
            <i class="bi bi-box-arrow-right"></i> Logout
          </a>
        </li>
      </ul>

      <!-- Admin Icon di Sebelah Kanan -->
      <ul class="navbar-nav ms-auto">
        <li class="nav-item">
          <a class="nav-link text-white" href="#">
            <i class="bi bi-person-fill-gear fs-4"></i>
          </a>
        </li>
      </ul>
    </div>

  </div>
</nav>
<!-- Navbar Admin End -->

<!-- Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
