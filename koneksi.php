<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "tokotsabiphp"; // GANTI dengan nama database kamu

$con = mysqli_connect($host, $user, $password, $database);

if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}
?>
