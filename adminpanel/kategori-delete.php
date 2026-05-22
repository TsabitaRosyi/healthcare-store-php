<?php
    session_start();
    require "../koneksi.php";

    // Pastikan parameter id dikirim lewat URL
    if (isset($_GET['id'])) {
        $id = $_GET['id'];

        // Cek apakah data kategori dengan ID tersebut ada
        $cek = mysqli_query($con, "SELECT * FROM kategori WHERE id='$id'");
        if (mysqli_num_rows($cek) > 0) {
            // Hapus kategori
            $hapus = mysqli_query($con, "DELETE FROM kategori WHERE id='$id'");

            if ($hapus) {
                // Jika berhasil, kembali ke halaman kategori
                header("Location: kategori.php");
                exit();
            } else {
                // Jika gagal, tampilkan error MySQL
                echo "Gagal menghapus kategori: " . mysqli_error($con);
            }
        } else {
            echo "Kategori tidak ditemukan.";
        }
    } else {
        echo "ID kategori tidak diberikan.";
    }
?>
