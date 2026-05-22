<?php
    session_start();
    require "../koneksi.php";

    $id = $_GET['id'];
    $query = mysqli_query($con, "SELECT * FROM kategori WHERE id = '$id'");
    $data = mysqli_fetch_array($query);

    if (!$data) {
        echo "Data tidak ditemukan.";
        exit();
    }

    if (isset($_POST['update_kategori'])) {
        $kategori = htmlspecialchars($_POST['nama_kategori']);

        // Cek apakah nama tidak berubah
        if ($data['nama_kategori'] == $kategori) {
            header("Location: kategori.php");
            exit();
        } else {
            // Cek apakah nama kategori sudah ada di kategori lain
            $cekDuplikat = mysqli_query($con, "SELECT * FROM kategori WHERE nama_kategori = '$kategori' AND id != '$id'");
            if (mysqli_num_rows($cekDuplikat) > 0) {
                $error_message = "Kategori dengan nama tersebut sudah ada.";
            } else {
                // Lanjut update
                $queryupdate = mysqli_query($con, "UPDATE kategori SET nama_kategori='$kategori' WHERE id='$id'");

                if ($queryupdate) {
                    header("Location: kategori.php");
                    exit();
                } else {
                    $error_message = "Gagal update kategori: " . mysqli_error($con);
                }
            }
        }
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
</head>
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
                    Kategori
                </li>
            </ol>
        </nav>

        <div class="container mt-5">
        <h2>Detail Kategori</h2>

        <div class="col-12 col-md-6">
            <form action="" method="post">
                <div>
                    <label for="kategori">Kategori</label>
                    <input type="text" name="nama_kategori" id="kategori" class="form-control" value="<?php echo htmlspecialchars($data['nama_kategori']); ?>" required>
                </div>

                <div class="mt-5">
                    <button type="submit" name="update_kategori" class="btn btn-success">Update</button>
                    <a href="produk-admin.php" class="btn btn-primary ms-2">Batal</a>
                </div>
            </form>

            <!-- Tampilkan pesan error -->
            <?php if (isset($error_message)) { ?>
                <div class="alert alert-danger mt-3">
                    <?php echo $error_message; ?>
                </div>
            <?php } ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
</body>
</html>
