<?php
session_start();
require "../koneksi.php";

// Pastikan parameter 'aksi' dan 'id' ada
if (isset($_GET['aksi']) && isset($_GET['id'])) {
    $aksi = $_GET['aksi'];
    $id = intval($_GET['id']); // Amankan ID

    // Pastikan session keranjang sudah ada
    if (!isset($_SESSION['keranjang'])) {
        $_SESSION['keranjang'] = [];
    }

    // Ambil keranjang dari session
    $keranjang = $_SESSION['keranjang'];

    // Proses aksi tambah atau kurang
    if ($aksi === 'tambah') {
        if (isset($keranjang[$id])) {
            $keranjang[$id]++;
        } else {
            $keranjang[$id] = 1;
        }
    } elseif ($aksi === 'kurang') {
        if (isset($keranjang[$id])) {
            $keranjang[$id]--;
            if ($keranjang[$id] <= 0) {
                unset($keranjang[$id]);
            }
        }
    }

    // Simpan kembali ke session
    $_SESSION['keranjang'] = $keranjang;
}

// Jika parameter tidak lengkap, tetap redirect
header("Location: keranjang.php");
exit;
