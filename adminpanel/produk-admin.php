<?php
session_start();
require "../koneksi.php";

// Ambil data kategori untuk dropdown
$querykategori = mysqli_query($con, "SELECT * FROM kategori");

// Tambah produk
if (isset($_POST['simpan_produk'])) {
    $nama = htmlspecialchars($_POST['nama']);
    $deskripsi = htmlspecialchars($_POST['deskripsi']);
    $harga = intval($_POST['harga']);
    $id_kategori = intval($_POST['id_kategori']);

    // echo "<pre>";
    // print_r($_POST);
    // print_r($_FILES);
    // echo "</pre>";


    // Upload gambar
    $target_dir = "../assets/";
    $nama_file = basename($_FILES["foto"]["name"]);
    $target_file = $target_dir . $nama_file;

    $uploadOk = move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);

    if ($uploadOk) {
        // echo "✅ Upload gambar berhasil<br>";

        $query = mysqli_query($con, "INSERT INTO produk (nama, deskripsi, harga, foto, id_kategori)
            VALUES ('$nama', '$deskripsi', '$harga', '$nama_file', '$id_kategori')");

        if ($query) {
            $success = "Produk berhasil ditambahkan!";
        } else {
            $error = "❌ Gagal menyimpan produk: " . mysqli_error($con);
        }
    } else {
        $error = "❌ Upload gambar gagal!";
    }
}

// Ambil semua produk
$queryproduk = mysqli_query($con, "
    SELECT produk.*, kategori.nama_kategori 
    FROM produk 
    LEFT JOIN kategori ON produk.id_kategori = kategori.id
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Produk Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
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
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <a href="proses-admin.php" class="no-decoration text-muted">
                        <i class="fas fa-home"></i> Admin
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">
                    Produk
                </li>
            </ol>
        </nav>
    </div>

    <div class="container mt-5">
        <h2>Tambah Produk</h2>
        <?php if (!empty($error)) : ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <?php if (!empty($success)) : ?>
            <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <meta http-equiv="refresh" content="2; url=produk-admin.php">
        <?php endif; ?>

        <form action="" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label>Nama Produk</label>
                <input type="text" name="nama" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Deskripsi</label>
                <textarea name="deskripsi" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label>Harga</label>
                <input type="number" name="harga" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Kategori</label>
                <select name="id_kategori" class="form-select" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php while ($kategori = mysqli_fetch_array($querykategori)) : ?>
                        <option value="<?= $kategori['id'] ?>"><?= $kategori['nama_kategori'] ?></option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="mb-3">
                <label>Upload Gambar</label>
                <input type="file" name="foto" class="form-control" required>
            </div>
            <button type="submit" name="simpan_produk" class="btn btn-primary">Simpan Produk</button>
        </form>

        <hr class="my-5">

        <h2>Daftar Produk</h2>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>Nama</th>
                    <th>Harga</th>
                    <th>Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($queryproduk) == 0) : ?>
                    <tr><td colspan="6" class="text-center">Belum ada produk</td></tr>
                <?php else : ?>
                    <?php $no = 1; ?>
                    <?php while ($produk = mysqli_fetch_array($queryproduk)) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><img src="../assets/<?= $produk['foto'] ?>" width="60"></td>
                            <td><?= htmlspecialchars($produk['nama']) ?></td>
                            <td>Rp<?= number_format($produk['harga'], 0, ',', '.') ?></td>
                            <td><?= $produk['nama_kategori'] ?></td>
                            <td>
                                <a href="produk-edit.php?id=<?= $produk['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="produk-delete.php?id=<?= $produk['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus produk ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
