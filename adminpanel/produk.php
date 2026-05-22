<?php
include '../koneksi.php';
include 'session.php';

/**
 * Fungsi untuk menampilkan daftar produk
 * @param mysqli_result $queryProduk hasil query produk dari database
 * @param mysqli $con koneksi ke database
 * @return void
 */
function tampilkanProduk($queryProduk, $con) {
    if (mysqli_num_rows($queryProduk) > 0) {
        while ($produk = mysqli_fetch_assoc($queryProduk)) { ?>
            <div class="col">
                <div class="card produk-card h-100">
                    <img src="../assets/<?= htmlspecialchars($produk['foto']) ?>" class="card-img-top produk-img" alt="<?= htmlspecialchars($produk['nama']) ?>">
                    <div class="card-body">
                        <h5><?= htmlspecialchars($produk['nama']) ?></h5>
                        <p class="text-muted small"><?= htmlspecialchars($produk['deskripsi']) ?></p>
                        <p class="mb-1"><strong>Harga:</strong> Rp <?= number_format($produk['harga'], 0, ',', '.') ?></p>
                        <p class="mb-0"><strong>Kategori:</strong>
                            <?php
                                $kategoriQuery = mysqli_query($con, "SELECT nama_kategori FROM kategori WHERE id = '".mysqli_real_escape_string($con, $produk['id_kategori'])."'");
                                $kategori = mysqli_fetch_assoc($kategoriQuery);
                                echo htmlspecialchars($kategori['nama_kategori']);
                            ?>
                        </p>
                    </div>
                    <div class="card-footer bg-transparent border-0 d-flex justify-content-between">
                        <a href="produk-view.php?id=<?= $produk['id'] ?>" class="btn btn-outline-primary btn-sm">View</a>
                        <a href="produk-buy.php?id=<?= $produk['id'] ?>" class="btn btn-success btn-sm">Buy</a>
                    </div>
                </div>
            </div>
        <?php }
    } else {
        echo "<p class='text-muted'>Produk tidak ditemukan.</p>";
    }
}

$queryKategori = mysqli_query($con, "SELECT * FROM kategori");

$selectedKategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$searchKeyword = isset($_GET['search']) ? mysqli_real_escape_string($con, $_GET['search']) : '';

$sql = "SELECT * FROM produk WHERE 1=1";
if (!empty($selectedKategori)) {
    $sql .= " AND id_kategori = '$selectedKategori'";
}
if (!empty($searchKeyword)) {
    $sql .= " AND nama LIKE '%$searchKeyword%'";
}
$queryProduk = mysqli_query($con, $sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Produk</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #f9f9f9;
        }

        .produk-card {
            transition: transform 0.3s ease;
            border: none;
            box-shadow: 0 4px 10px rgba(0,0,0,0.07);
            border-radius: 16px;
        }

        .produk-card:hover {
            transform: translateY(-5px);
        }

        .produk-img {
            height: 200px;
            object-fit: cover;
            border-top-left-radius: 16px;
            border-top-right-radius: 16px;
        }

        .card-footer a {
            width: 45%;
        }

        .filter-box {
            background-color: rgb(255, 186, 58);
            border-radius: 16px;
            padding: 20px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.07);
        }

        .breadcrumb {
            background: none;
            padding-left: 0;
        }

        .no-decoration {
            text-decoration: none;
        }

        .search-bar input {
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <?php include "navbar.php"; ?>

    <div class="container mt-5">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="home.php" class="no-decoration text-muted">
                        <i class="fas fa-home"></i> Home
                    </a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Produk</li>
            </ol>
        </nav>

        <div class="row mt-4">
            <!-- Sidebar Kategori -->
            <div class="col-md-3">
                <div class="filter-box">
                    <h5 class="mb-3 text-center">Filter Kategori</h5>
                    <form method="GET">
                        <select name="kategori" class="form-select mb-3" onchange="this.form.submit()">
                            <option value="">Semua Kategori</option>
                            <?php while ($kategori = mysqli_fetch_assoc($queryKategori)) { ?>
                                <option value="<?= $kategori['id'] ?>" <?= $selectedKategori == $kategori['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($kategori['nama_kategori']) ?>
                                </option>
                            <?php } ?>
                        </select>
                        <?php if ($searchKeyword): ?>
                            <input type="hidden" name="search" value="<?= htmlspecialchars($searchKeyword) ?>">
                        <?php endif; ?>
                    </form>
                </div>
            </div>

            <!-- Produk & Search -->
            <div class="col-md-9">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="fw-semibold mb-0">Daftar Produk</h3>
                    <form method="GET" class="d-flex search-bar" role="search">
                        <?php if ($selectedKategori): ?>
                            <input type="hidden" name="kategori" value="<?= htmlspecialchars($selectedKategori) ?>">
                        <?php endif; ?>
                        <input class="form-control me-2" type="search" name="search" placeholder="Cari produk..." value="<?= htmlspecialchars($searchKeyword) ?>">
                        <button class="btn btn-outline-primary" type="submit"><i class="fas fa-search"></i></button>
                    </form>
                </div>

                <div class="row row-cols-1 row-cols-md-3 g-4">
                    <?php tampilkanProduk($queryProduk, $con); ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
