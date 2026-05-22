<?php
session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Pilih Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .login-box {
      height: 200px;
      display: flex;
      justify-content: center;
      align-items: center;
      gap: 40px;
    }
    .login-option {
      width: 180px;
      height: 100px;
      display: flex;
      justify-content: center;
      align-items: center;
      font-size: 20px;
      font-weight: bold;
      border-radius: 10px;
      text-decoration: none;
      transition: 0.3s;
    }
    .admin {
      background-color: #0d6efd;
      color: white;
    }
    .customer {
      background-color: #198754;
      color: white;
    }
    .login-option:hover {
      transform: scale(1.05);
      box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    }
  </style>
</head>
<body>

  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="login-box">
      <a href="login-admin.php" class="login-option admin">Admin</a>
      <a href="login.php" class="login-option customer">Customer</a>
    </div>
  </div>

</body>
</html>
