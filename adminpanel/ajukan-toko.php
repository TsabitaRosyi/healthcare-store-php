<?php
session_start();
require "../koneksi.php";

// Cek apakah user sudah login dan bukan admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'user') {
    echo "<script>alert('Akses ditolak'); window.location='login.php';</script>";
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil pengajuan terakhir user
$cek = mysqli_query($con, "SELECT * FROM shop_request WHERE owner_id = $user_id ORDER BY created_at DESC LIMIT 1");
$dataShop = mysqli_fetch_assoc($cek);

// Tentukan apakah boleh mengajukan
$bolehAjukan = false;
if (!$dataShop || $dataShop['status'] === 'rejected') {
    $bolehAjukan = true;
}

// Proses submit form
if (isset($_POST['submit']) && $bolehAjukan) {
    $nama = mysqli_real_escape_string($con, $_POST['nama']);
    $deskripsi = mysqli_real_escape_string($con, $_POST['deskripsi']);

    $insert = mysqli_query($con, "INSERT INTO shop_request (shop_name, owner_id, description) 
                                  VALUES ('$nama', $user_id, '$deskripsi')");

    if ($insert) {
        echo "<script>alert('Permintaan toko berhasil diajukan.'); window.location='ajukan-toko.php';</script>";
    } else {
        echo "<script>alert('Gagal mengajukan toko.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ajukan Toko</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <style>
        body { background-color: #f9f9f9; }
        .no-decoration { text-decoration: none; }
        .breadcrumb { background: none; padding-left: 0; }
        .breadcrumb-item a { color: #6c757d; }
        .breadcrumb-item a:hover { color: #343a40; }
        .breadcrumb-item.active { color: #343a40; }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container mt-5">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="home.php" class="no-decoration"><i class="fas fa-home"></i> Home</a></li>
            <li class="breadcrumb-item active" aria-current="page">Ajukan Toko</li>
        </ol>
    </nav>

    <div class="card p-4 shadow-sm">
        <?php if ($bolehAjukan): ?>
            <!-- Form pengajuan toko -->
            <h3 class="mb-4">Ajukan Pembuatan Toko</h3>
            <form method="post">
                <div class="mb-3">
                    <label for="nama" class="form-label">Nama Toko</label>
                    <input type="text" name="nama" id="nama" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4" required></textarea>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">Ajukan</button>
            </form>

        <?php else: ?>
            <!-- Informasi pengajuan toko -->
            <h3 class="mb-4">Informasi Pengajuan Toko</h3>
            <div class="mb-3">
                <strong>Nama Toko:</strong> <?= htmlspecialchars($dataShop['shop_name']) ?>
            </div>
            <div class="mb-3">
                <strong>Deskripsi:</strong><br>
                <?= nl2br(htmlspecialchars($dataShop['description'])) ?>
            </div>
            <div class="mb-3">
                <strong>Status:</strong>
                <?php if ($dataShop['status'] === 'pending'): ?>
                    <span class="badge bg-warning text-dark">Pending</span>
                <?php elseif ($dataShop['status'] === 'approved'): ?>
                    <span class="badge bg-success">Disetujui</span>
                <?php else: ?>
                    <span class="badge bg-danger">Ditolak</span>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
