<?php
session_start();
require "../koneksi.php";

// Cek login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$query = mysqli_query($con, "SELECT * FROM users WHERE id = $user_id");

if (!$query || mysqli_num_rows($query) === 0) {
    echo "User tidak ditemukan.";
    exit;
}

$userdata = mysqli_fetch_assoc($query);

// Tangani proses update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $username       = mysqli_real_escape_string($con, $_POST['username']);
    $email          = mysqli_real_escape_string($con, $_POST['email']);
    $date_of_birth  = mysqli_real_escape_string($con, $_POST['date_of_birth']);
    $gender         = mysqli_real_escape_string($con, $_POST['gender']);
    $address        = mysqli_real_escape_string($con, $_POST['address']);
    $city           = mysqli_real_escape_string($con, $_POST['city']);
    $contact_no     = mysqli_real_escape_string($con, $_POST['contact_no']);
    $paypal_id      = mysqli_real_escape_string($con, $_POST['paypal_id']);

    $update_query = "
        UPDATE users SET
            username = '$username',
            email = '$email',
            date_of_birth = '$date_of_birth',
            gender = '$gender',
            address = '$address',
            city = '$city',
            contact_no = '$contact_no',
            paypal_id = '$paypal_id'
        WHERE id = $user_id
    ";

    if (mysqli_query($con, $update_query)) {
        echo "<script>alert('Profil berhasil diperbarui.'); window.location.href = 'profil.php';</script>";
        exit;
    } else {
        echo "<div class='alert alert-danger text-center'>Gagal memperbarui data: " . mysqli_error($con) . "</div>";
    }
}

// Gambar profil default
$gender = strtolower(trim($userdata['gender']));
$profile_img = ($gender === 'male')
    ? 'https://cdn-icons-png.flaticon.com/512/4140/4140048.png'
    : 'https://cdn-icons-png.flaticon.com/512/4140/4140047.png';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Pengguna</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #eef6fb;
        }
        .profile-container {
            max-width: 700px;
            margin: 50px auto;
            padding: 30px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        .profile-image {
            display: block;
            margin: 0 auto 20px;
            width: 120px;
            height: 120px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>
</head>
<body>

<?php include 'navbar.php'; ?>

<div class="container">
    <div class="profile-container">
        <img src="<?= $profile_img ?>" alt="Profile Image" class="profile-image">
        <h3 class="text-center mb-4">Profil Pengguna</h3>

        <form method="post">
            <table class="table table-borderless">
                <tr>
                    <th>Username</th>
                    <td><input type="text" name="username" class="form-control" value="<?= htmlspecialchars($userdata['username']) ?>" required></td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td><input type="email" name="email" class="form-control" value="<?= htmlspecialchars($userdata['email']) ?>" required></td>
                </tr>
                <tr>
                    <th>Tanggal Lahir</th>
                    <td><input type="date" name="date_of_birth" class="form-control" value="<?= htmlspecialchars($userdata['date_of_birth']) ?>"></td>
                </tr>
                <tr>
                    <th>Jenis Kelamin</th>
                    <td>
                        <select name="gender" class="form-control">
                            <option value="male" <?= ($userdata['gender'] === 'male') ? 'selected' : '' ?>>Laki-laki</option>
                            <option value="female" <?= ($userdata['gender'] === 'female') ? 'selected' : '' ?>>Perempuan</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td><input type="text" name="address" class="form-control" value="<?= htmlspecialchars($userdata['address']) ?>"></td>
                </tr>
                <tr>
                    <th>Kota</th>
                    <td><input type="text" name="city" class="form-control" value="<?= htmlspecialchars($userdata['city']) ?>"></td>
                </tr>
                <tr>
                    <th>Kontak</th>
                    <td><input type="text" name="contact_no" class="form-control" value="<?= htmlspecialchars($userdata['contact_no']) ?>"></td>
                </tr>
                <tr>
                    <th>PayPal ID</th>
                    <td><input type="text" name="paypal_id" class="form-control" value="<?= htmlspecialchars($userdata['paypal_id']) ?>"></td>
                </tr>
                <tr>
                    <th>Role</th>
                    <td><input type="text" class="form-control" value="<?= htmlspecialchars($userdata['role']) ?>" disabled></td>
                </tr>
            </table>

            <div class="text-center mt-4">
                <button type="submit" name="update" class="btn btn-primary">Update</button>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
