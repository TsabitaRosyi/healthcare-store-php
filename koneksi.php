<!-- // Parameter koneksi ke XAMPP MySQL -->

<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "tokotsabiphp"; // GANTI dengan nama database kamu

// Membuat koneksi
$con = mysqli_connect($host, $user, $password, $database);

// Cek apakah koneksi berhasil
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}
?>
