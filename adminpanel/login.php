<?php
session_start();
require "../koneksi.php";

// Redirect jika sudah login
if (isset($_SESSION['user_id'])) {
    header("Location: home.php");
    exit();
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];

    // Ambil data user berdasarkan username
    $query = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");

    if (!$query || mysqli_num_rows($query) === 0) {
        $error = "Username tidak ditemukan!";
    } else {
        $data = mysqli_fetch_assoc($query);

        if (!password_verify($password, $data['password'])) {
            $error = "Password salah!";
        } else {
            // Simpan data ke session
            $_SESSION['user_id']    = $data['id'];
            $_SESSION['username']   = $data['username'];
            $_SESSION['email']      = $data['email'];
            $_SESSION['contact_no'] = $data['contact_no'];
            $_SESSION['paypal_id']  = $data['paypal_id'];
            $_SESSION['role']       = $data['role'];
            $_SESSION['login']      = true;

            // Redirect berdasarkan role
            $redirectPage = ($data['role'] === 'admin') ? "../adminpanel/proses-admin.php" : "home.php";
            header("Location: $redirectPage");
            exit();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">  
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - tokotsabiphp</title>

    <!-- Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

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
    <div class="main d-flex flex-column justify-content-center align-items-center">
        <div class="login-box">
            <!-- Logo -->
            <img src="../assets/logo_login.png" alt="Logo Toko">

            <!-- Judul -->
            <h2>Login</h2>
            <div class="subtitle">Selamat datang di Toko Alat Kesehatan</div>

            <!-- Form -->
            <form action="" method="post">
                <div class="textbox">
                    <input type="text" class="form-control" placeholder="Username" name="username" id="username" required>
                </div>
                <div class="textbox">
                    <input type="password" class="form-control" placeholder="Password" name="password" id="password" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Login</button>
            

            </form>

            <!-- Pesan error -->
            <?php if (!empty($error)) : ?>
                <div class="alert alert-danger mt-3" role="alert">
                    <?= $error ?>
                </div>
            <?php endif; ?>

            <!-- Link ke halaman register -->
            <div class="mt-3">
                Belum punya akun? <a href="register.php">Register Here</a>
            </div>
        </div>
    </div>
</body>
</html>