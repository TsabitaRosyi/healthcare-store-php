<?php
session_start();
require "../koneksi.php";

// Cek akses admin
if (!isset($_SESSION['login']) || $_SESSION['role'] !== 'admin') {
    echo "<script>alert('Akses ditolak'); window.location='../login.php';</script>";
    exit();
}

// Validasi input
if (!isset($_GET['id']) || !isset($_GET['aksi'])) {
    echo "<script>alert('Parameter tidak valid.'); window.location='shoprequest-admin.php';</script>";
    exit();
}

$id = intval($_GET['id']);
$aksi = $_GET['aksi'];

// Pastikan hanya menerima aksi tertentu
if (!in_array($aksi, ['approve', 'reject'])) {
    echo "<script>alert('Aksi tidak valid.'); window.location='shoprequest-admin.php';</script>";
    exit();
}

// Update status
$status = ($aksi === 'approve') ? 'approved' : 'rejected';
$update = mysqli_query($con, "UPDATE shop_request SET status = '$status' WHERE id = $id");

if ($update) {
    echo "<script>alert('Status permintaan diperbarui.'); window.location='shoprequest-admin.php';</script>";
} else {
    echo "<script>alert('Gagal memperbarui status.'); window.location='shoprequest-admin.php';</script>";
}
