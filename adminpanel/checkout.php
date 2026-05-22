<?php
session_start();
require "../koneksi.php";

if (!isset($_SESSION['user_id'])) {
    echo "<script>alert('Silakan login terlebih dahulu.'); window.location='login.php';</script>";
    exit;
}

$user_id = $_SESSION['user_id'];
$query_user = mysqli_query($con, "SELECT * FROM users WHERE id = '$user_id'");
$data_user = mysqli_fetch_assoc($query_user);

$keranjang = isset($_SESSION['keranjang']) ? $_SESSION['keranjang'] : [];
if (empty($keranjang)) {
    echo "<script>alert('Keranjang belanja kosong.'); window.location='produk.php';</script>";
    exit;
}

$tanggal = date("d-m-Y");
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f4f6f9;
        }
        .card {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        }
        .form-label {
            font-weight: 500;
        }
        .section-title {
            border-bottom: 2px solid #dee2e6;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<?php require "navbar.php"; ?>

<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card p-4">
                <h3 class="section-title text-center">
                    Laporan Belanja <?= isset($_SESSION['username']) ? htmlspecialchars($_SESSION['username']) : 'Pengguna'; ?>
                </h3>

                <form id="checkoutForm" action="generate-pdf-checkout.php" method="POST">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">User ID:</label>
                            <input type="text" name="user_id" class="form-control" value="<?= $data_user['id'] ?>" readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal:</label>
                            <input type="text" class="form-control" value="<?= $tanggal ?>" readonly>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Nama:</label>
                            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($data_user['username']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">ID Paypal:</label>
                            <input type="text" name="paypal_id" class="form-control" value="<?= htmlspecialchars($data_user['paypal_id']) ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Alamat:</label>
                            <input type="text" name="alamat" class="form-control" value="<?= htmlspecialchars($data_user['address']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nama Bank:</label>
                            <select name="bank" class="form-select" id="bank">
                                <option value="">-- Pilih Nama Bank --</option>
                                <option value="BCA">BCA</option>
                                <option value="BNI">BNI</option>
                                <option value="BRI">BRI</option>
                                <option value="Mandiri">Mandiri</option>
                                <option value="CIMB Niaga">CIMB Niaga</option>
                                <option value="Permata Bank">Permata Bank</option>
                                <option value="Danamon">Danamon</option>
                                <option value="OCBC NISP">OCBC NISP</option>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Nomor Kontak:</label>
                            <input type="text" name="contact_no" class="form-control" value="<?= htmlspecialchars($data_user['contact_no']) ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Cara Bayar:</label>
                            <select name="metode" class="form-select" id="metode">
                                <option value="">-- Pilih Metode --</option>
                                <option value="Prepaid">Prepaid</option>
                                <option value="Postpaid">Postpaid</option>
                                <option value="COD">COD (Cash on Delivery)</option>
                            </select>
                        </div>
                    </div>

                    <h5 class="section-title">Detail Belanja</h5>
                    <table class="table table-bordered">
                        <thead class="table-secondary text-center">
                            <tr>
                                <th>No</th>
                                <th>Produk (ID)</th>
                                <th>Jumlah</th>
                                <th>Harga Satuan</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total = 0;
                            $no = 1;
                            foreach ($keranjang as $id => $jumlah) {
                                $produkQuery = mysqli_query($con, "SELECT * FROM produk WHERE id = '$id'");
                                $produk = mysqli_fetch_assoc($produkQuery);

                                if (!$produk) {
                                    echo "<tr><td colspan='5' class='text-danger text-center'>Produk dengan ID $id tidak ditemukan.</td></tr>";
                                    continue;
                                }

                                $subtotal = $produk['harga'] * $jumlah;
                                $total += $subtotal;
                            ?>
                            <tr>
                                <td class="text-center"><?= $no++ ?></td>
                                <td><?= htmlspecialchars($produk['nama']) ?> (<?= $produk['id'] ?>)</td>
                                <td class="text-center"><?= $jumlah ?></td>
                                <td>Rp <?= number_format($produk['harga'], 0, ',', '.') ?></td>
                                <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                            </tr>
                            <?php } ?>
                            <tr class="table-light">
                                <td colspan="4" class="text-end"><strong>Total (termasuk pajak):</strong></td>
                                <td><strong>Rp <?= number_format($total, 0, ',', '.') ?></strong></td>
                            </tr>
                        </tbody>
                    </table>

                    <input type="hidden" name="total" value="<?= $total ?>">

                    <div class="mt-5 text-end">
                        <p><strong>TANDA TANGAN TOKO</strong></p>
                    </div>

                    <div class="mt-3 text-end">
                        <p><strong>HEALTH CARE FROM TSABI</strong></p>
                    </div>

                    <div class="text-end mt-4">
                        <button form="checkoutForm" type="submit" class="btn btn-success">KONFIRMASI PESANAN</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<script>
    const metode = document.getElementById("metode");
    const bank = document.getElementById("bank");

    metode.addEventListener("change", function () {
        if (metode.value === "Prepaid" || metode.value === "Postpaid") {
            bank.required = true;
        } else {
            bank.required = false;
        }
    });

    // Panggil saat halaman dimuat
    window.addEventListener('DOMContentLoaded', function () {
        metode.dispatchEvent(new Event('change'));
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
