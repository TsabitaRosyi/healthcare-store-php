<?php
    session_start(); // Wajib sebelum mengakses $_SESSION
    // require "session.php"; // Pastikan session.php sudah benar
    require "../koneksi.php"; // Pastikan koneksi database sudah benar

    $querykategori = mysqli_query($con, "SELECT * FROM kategori");
    $jumlahkategori = mysqli_num_rows($querykategori);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kategori</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
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
                    Kategori
                </li>
            </ol>
        </nav>
        
        <div class="my-5 col-12" col-md-6>
            <h3> Tambah Kategori</h3>

            <form action="" method="post">
                <div>
                    <label for="nama_kategori">Kategori</label>
                    <input type="text" id="kategori" name="nama_kategori" class="form-control" placeholder="Masukkan nama kategori" required>
                </div>

                <div class="mt-3">   
                    <button type="submit" name="simpan_kategori" class="btn btn-primary mt-3">Simpan</button>
                </div>
            </form>

            <?php
                if (isset($_POST['simpan_kategori'])) {
                    $nama_kategori = htmlspecialchars($_POST['nama_kategori']);

                    $querytambahkategori = mysqli_query($con, "SELECT nama_kategori FROM kategori WHERE nama_kategori='$nama_kategori'");
                    $jumlahdatakategoribaru = mysqli_num_rows($querytambahkategori);

                    if ($jumlahdatakategoribaru > 0) {
                        echo '<div class="alert alert-warning mt-3" role="alert">Kategori sudah ada!</div>';
                    } else {
                        $querysimpankategori = mysqli_query($con, "INSERT INTO kategori (nama_kategori) VALUES ('$nama_kategori')");

                        if ($querysimpankategori) {
                            ?>
                            <div class="alert alert-primary mt-3" role="alert">
                                Kategori berhasil ditambahkan!
                            </div>
                            <meta http-equiv="refresh" content="0; url=kategori.php" />
                            <?php
                            exit(); // agar tidak lanjut ke bawah
                        } else {
                            echo '<div class="alert alert-danger mt-3" role="alert">' . mysqli_error($con) . '</div>';
                        }
                    }
                }
                     
            ?>


        </div>

        <div class="mt-3">
            <h2> List Kategori</h2>

            <div class="table-responsive mt-3">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th colspan="3" class="text-center">No</th>
                            <th colspan="3" class="text-center">Nama Kategori</th>
                            <th colspan="3" class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            if($jumlahkategori == 0){
                        ?>
                            <tr>
                                <td colspan="3" class="text-center">Tidak ada kategori</td>
                            </tr>
                        <?php
                            } 
                            else{
                                $jumlah = 1;
                                while($data = mysqli_fetch_array($querykategori)){
                    ?>
                                <tr>
                                    <td colspan="3" class="text-center"><?php echo $jumlah; ?></td>
                                    <td colspan="3" class="text-center"><?php echo $data['nama_kategori'] ?></td>
                                    <td colspan="3" class="text-center">
                                        <a href="kategori-detail.php?id=<?php echo $data['id']; ?>" class="btn btn-info btn-sm">
                                           Edit
                                        </a>
                                        <a href="kategori-delete.php?id=<?php echo $data['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus kategori ini?');">
                                            <i class="fas fa-trash"></i> Hapus
                                    </td>
                                </tr>
                    <?php
                                $jumlah++;}
                                }
                    ?>
                    </tbody>
                </table>
            </div>
        
        </div>
    
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>
</body>
</html>