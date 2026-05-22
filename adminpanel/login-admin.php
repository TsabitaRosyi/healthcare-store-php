<?php
session_start();
require "../koneksi.php";

// Redirect jika sudah login dan role admin
if (isset($_SESSION['user_id']) && $_SESSION['role'] === 'admin') {
    header("Location: ../adminpanel/proses-admin.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    // Cek user dengan username dan role admin
    $query = mysqli_query($con, "SELECT * FROM users WHERE username='$username' AND role='admin'");

    if (!$query || mysqli_num_rows($query) === 0) {
        $error = "Akun admin tidak ditemukan!";
    } else {
        $data = mysqli_fetch_assoc($query);

        if (!password_verify($password, $data['password'])) {
            $error = "Password salah!";
        } else {
            // Simpan ke session
            $_SESSION['user_id']    = $data['id'];
            $_SESSION['username']   = $data['username'];
            $_SESSION['email']      = $data['email'];
            $_SESSION['contact_no'] = $data['contact_no'];
            $_SESSION['paypal_id']  = $data['paypal_id'];
            $_SESSION['role']       = $data['role'];
            $_SESSION['login']      = true;

            header("Location: ../adminpanel/proses-admin.php");
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login Admin - tokotsabiphp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .main {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .login-box {
            width: 400px;
            padding: 40px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        .login-box img {
            width: 100px;
            margin-bottom: 20px;
        }
        .login-box h2 {
            margin-bottom: 10px;
        }
        .subtitle {
            font-size: 16px;
            color: #555;
            margin-bottom: 30px;
        }
        .textbox {
            margin-bottom: 20px;
        }
        .textbox input {
            width: 100%;
            padding: 10px;
            box-sizing: border-box;
        }
    </style>
</head>
<body>
    <div class="main">
        <div class="login-box">
            <img src="../assets/logo_login.png" alt="Logo Toko">
            <h2>Login Admin</h2>
            <div class="subtitle">Masuk sebagai administrator</div>

            <form action="" method="post">
                <div class="textbox">
                    <input type="text" class="form-control" placeholder="Username" name="username" required>
                </div>
                <div class="textbox">
                    <input type="password" class="form-control" placeholder="Password" name="password" required>
                </div>
                <button type="submit" class="btn btn-danger w-100">Login Admin</button>
            </form>

            <?php if (!empty($error)) : ?>
                <div class="alert alert-danger mt-3" role="alert">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <div class="mt-3">
                <a href="login.php">Login sebagai pengguna biasa</a>
            </div>
        </div>
    </div>
</body>
</html>
