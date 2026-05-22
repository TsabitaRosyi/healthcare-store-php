<?php
    require "../koneksi.php";

    $id = intval($_GET['id']);
    $result = mysqli_query($con, "SELECT * FROM produk WHERE id = $id");
    $produk = mysqli_fetch_assoc($result);

    if (!$produk) {
        echo "<div class='alert alert-danger text-center'>Produk tidak ditemukan!</div>";
        exit;
    }

    // Ambil nama kategori
    $kategoriQuery = mysqli_query($con, "SELECT nama_kategori FROM kategori WHERE id = '".mysqli_real_escape_string($con, $produk['id_kategori'])."'");
    $kategori = mysqli_fetch_assoc($kategoriQuery);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Detail Produk - <?= htmlspecialchars($produk['nama']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .produk-image {
            width: 100%;
            max-height: 450px;
            object-fit: contain;
            border-radius: 10px;
        }
        .produk-info h2 {
            font-size: 2rem;
        }
        .produk-info p {
            font-size: 1.1rem;
        }
        .label {
            font-weight: bold;
            color: #333;
        }
        .back-button {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container mt-5 mb-5">
        <a href="produk.php" class="btn btn-secondary back-button">← Kembali ke Daftar Produk</a>

        <div class="row align-items-start g-5">
            <!-- Gambar Produk -->
            <div class="col-md-5">
                <img src="../assets/<?= htmlspecialchars($produk['foto']) ?>" 
                     alt="<?= htmlspecialchars($produk['nama']) ?>" 
                     class="produk-image shadow">
            </div>

            <!-- Detail Produk -->
            <div class="col-md-7 produk-info">
                <h2><?= htmlspecialchars($produk['nama']) ?></h2>
                <hr>
                <p><span class="label">Kategori:</span> <?= htmlspecialchars($kategori['nama_kategori']) ?></p>
                <p><span class="label">Harga:</span> Rp <?= number_format($produk['harga'], 0, ',', '.') ?></p>
                <p><span class="label">Deskripsi Produk:</span></p>
                <div class="mb-3">
                    <?= nl2br(htmlspecialchars($produk['deskripsi'])) ?>
                </div>
                <a href="produk-buy.php?id=<?= $produk['id'] ?>" class="btn btn-success btn-lg">🛒 Buy Now</a>
            </div>
        </div>
    </div>
</body>
</html>
