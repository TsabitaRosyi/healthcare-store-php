<?php
require "../koneksi.php"; // Ubah sesuai struktur file

$error = isset($_GET['error']) ? urldecode($_GET['error']) : "";
$success = isset($_GET['success']) ? urldecode($_GET['success']) : "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $password = $_POST['password'];
    $retype = $_POST['retype_password'];
    $email = htmlspecialchars($_POST['email']);
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $address = htmlspecialchars($_POST['address']);
    $city = htmlspecialchars($_POST['city']);
    $contact = htmlspecialchars($_POST['contact_no']);
    $norek = htmlspecialchars($_POST['paypal_id']);

    if ($password !== $retype) {
        header("Location: register.php?error=" . urlencode("Password dan retype-password tidak sama!"));
        exit;
    } else {
        $cek = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");
        if (mysqli_num_rows($cek) > 0) {
            header("Location: register.php?error=" . urlencode("Username sudah digunakan!"));
            exit;
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $role = $_POST['role'];

            
            // Pastikan role hanya bisa 'user' atau 'admin'
            $query = "INSERT INTO users (username, password, email, date_of_birth, gender, address, city, contact_no, paypal_id, role) 
                 VALUES ('$username', '$hashed_password', '$email', '$dob', '$gender', '$address', '$city', '$contact', '$norek', '$role')";

            // Eksekusi query untuk menyimpan data pengguna baru
            if (mysqli_query($con, $query)) {
                header("Location: register.php?success=" . urlencode("Registrasi berhasil! Silakan login."));
                exit;
            } else {
                header("Location: register.php?error=" . urlencode("Terjadi kesalahan saat menyimpan data."));
                exit;
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - tokotsabiphp</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="col-md-6 mx-auto">
        <h2 class="text-center mb-4">Register</h2>

        <?php if ($error): ?>
            <div class='alert alert-danger'><?= $error ?></div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class='alert alert-success'>
                <?= $success ?><br>
                <a href="login.php" class="btn btn-success mt-2">Lanjut ke Login</a>
            </div>
        <?php endif; ?>

        <form method="post" action="">
            <div class="mb-3">
                <label>Username</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Password</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Retype Password</label>
                <input type="password" name="retype_password" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Date of Birth</label>
                <input type="date" name="dob" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Gender</label><br>
                <input type="radio" name="gender" value="male" required> Male
                <input type="radio" name="gender" value="female" required> Female
            </div>
            <div class="mb-3">
                <label>Address</label>
                <textarea name="address" class="form-control" required></textarea>
            </div>
            <div class="mb-3">
                <label>City</label>
                <input type="text" name="city" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Contact Number</label>
                <input type="text" name="contact_no" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>PayPal ID</label>
                <input type="text" name="paypal_id" class="form-control" required>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">Submit</button>
                <button type="reset" class="btn btn-secondary">Clear</button>
            </div>
            <div class="mb-3">
                <label>Register Sebagai</label><br>
                <input type="radio" name="role" value="user" checked required> Customer
                <input type="radio" name="role" value="admin" required> Admin
            </div>

        </form>    
    </div>
</div>
</body>
</html>
