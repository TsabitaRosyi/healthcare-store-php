<?php
session_start();
require "../koneksi.php";

// Validasi parameter ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: produk-admin.php");
    exit;
}

$id = intval($_GET['id']);

// Ambil data produk berdasarkan ID
$query = mysqli_query($con, "SELECT * FROM produk WHERE id = $id");
$produk = mysqli_fetch_assoc($query);

// Jika produk tidak ditemukan
if (!$produk) {
    echo "<script>alert('Produk tidak ditemukan!'); window.location='produk-admin.php';</script>";
    exit;
}

// Proses update data produk
if (isset($_POST['update_produk'])) {
    $nama_baru = htmlspecialchars($_POST['nama']);
    $harga_baru = intval($_POST['harga']);

    $update = mysqli_query($con, "UPDATE produk SET nama = '$nama_baru', harga = $harga_baru WHERE id = $id");

    if ($update) {
        echo "<script>alert('Produk berhasil diperbarui!'); window.location='produk-admin.php';</script>";
    } else {
        echo "<script>alert('Gagal memperbarui produk!');</script>";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Produk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <?php require "navbar-admin.php"; ?>
    
    <div class="container mt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="proses-admin.php" class="no-decoration text-muted">
                        <i class="fas fa-home"></i> Admin
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="produk-admin.php" class="text-muted">Produk</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Edit Produk
                </li>
            </ol>
        </nav>

        <h2>Edit Produk</h2>
        <form method="post">
            <div class="mb-3">
                <label>Nama Produk</label>
                <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($produk['nama']) ?>" required>
            </div>
            <div class="mb-3">
                <label>Harga Sekarang</label>
                <input type="number" name="harga" class="form-control" value="<?= $produk['harga'] ?>" required>
            </div>
            <button type="submit" name="update_produk" class="btn btn-success">Update</button>
            <a href="produk-admin.php" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>
</html>
