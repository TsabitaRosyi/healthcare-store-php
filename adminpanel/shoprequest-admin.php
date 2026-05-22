<?php
session_start();
require "../koneksi.php";

// Cek akses admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses ditolak.'); window.location='../login.php';</script>";
    exit();
}

// Ambil data permintaan toko
$query = mysqli_query($con, "SELECT sr.*, u.username FROM shop_request sr LEFT JOIN users u ON sr.owner_id = u.id ORDER BY sr.created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Permintaan Toko</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        .no-decoration {
            text-decoration: none;
        }
    </style>
</head>
<body>

<?php include 'navbar-admin.php'; ?>

<div class="container mt-5">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="proses-admin.php" class="no-decoration text-muted">
                    <i class="fas fa-home"></i> Admin
                </a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">Permintaan Toko</li>
        </ol>
    </nav>

    <h2 class="mb-4">Daftar Permintaan Toko</h2>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>No</th>
                <th>Nama Toko</th>
                <th>Owner (Username)</th>
                <th>Deskripsi</th>
                <th>Tanggal Permintaan</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; while ($row = mysqli_fetch_assoc($query)) : ?>
            <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['shop_name']) ?></td>
                <td><?= htmlspecialchars($row['username']) ?></td>
                <td><?= htmlspecialchars($row['description']) ?></td>
                <td><?= htmlspecialchars($row['created_at']) ?></td>
                <td>
                    <?php
                    if ($row['status'] === 'pending') {
                        echo '<span class="badge bg-warning text-dark">Pending</span>';
                    } elseif ($row['status'] === 'approved') {
                        echo '<span class="badge bg-success">Disetujui</span>';
                    } else {
                        echo '<span class="badge bg-danger">Ditolak</span>';
                    }
                    ?>
                </td>
                <td class="d-flex gap-1">
                    <?php if ($row['status'] === 'pending') : ?>
                        <a href="proses-approve-shop.php?id=<?= $row['id'] ?>&aksi=approve" class="btn btn-success btn-sm">Setujui</a>
                        <a href="proses-approve-shop.php?id=<?= $row['id'] ?>&aksi=reject" class="btn btn-danger btn-sm">Tolak</a>
                    <?php else : ?>
                        <span class="text-muted">Sudah diproses</span>
                        <a href="hapus-shop-request.php?id=<?= $row['id'] ?>" onclick="return confirm('Yakin ingin menghapus permintaan toko ini?')" class="btn btn-danger btn-sm">Hapus</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

</body>
</html>
