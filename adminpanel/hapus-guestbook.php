<?php
session_start();
require "../koneksi.php";

if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses ditolak.'); window.location='../login.php';</script>";
    exit();
}

$id = intval($_GET['id']);
mysqli_query($con, "DELETE FROM guestbook WHERE id = $id");
header("Location: guestbook-admin.php");
exit();
