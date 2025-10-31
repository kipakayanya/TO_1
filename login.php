<?php
session_start();
require 'config/db_connect.php';

// jika sudah login, langsung ke index
if (isset($_SESSION['id_user'])) {
    header('Location: index.php');
    exit();
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    // cek username di database
    $sql  = "SELECT * FROM users WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, 's', $username);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $user   = mysqli_fetch_assoc($result);

    if ($user && password_verify($password, $user['password'])) {
        // simpan ke session
        $_SESSION['id_user'] = $user['id_user']; // gunakan id_user
        $_SESSION['username'] = $user['username'];
        header('Location: index.php');
        exit();
    } else {
        $error = 'Username atau password salah!';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | UBYSHOP</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
.login-container {
  max-width: 300px;
  margin: 70px auto;
  padding: 30px;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.login-container h1 {
  text-align: center;
  color: #75180c;
  margin-bottom: 21px;
}

.login-container label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

.login-container input {
  width: 100%;
  padding: 10px;
  margin-bottom: 15px;
  border: 1px solid #ccc;
  border-radius: 8px;
}

.login-container button {
  width: 100%;
  background: #75180c;
  color: #fff;
  border: none;
  padding: 11px;
  border-radius: 10px;
  cursor: pointer;
  font-weight: bold;
  margin-bottom: 10px;
}

.login-container button:hover {
  background: #75180c;
  transform: scale(1.05);
}

.home-btn {
  display: block;
  text-align: center;
  padding: 10px;
  border-radius: 10px;
  background: #a32214;
  color: #fff;
  text-decoration: none;
  font-weight: bold;
  transition: 0.3s;
}

.home-btn:hover {
  background: #75180c;
  transform: scale(1.05);
}
    </style>
</head>
<body>
<div class="login-container">
    <h1>Login to UBYSHOP</h1>
    <form action="" method="post">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" required>

        <label for="password">Password</label>
        <input type="password" name="password" id="password" required>

        <button type="submit">Login</button>
    </form>
    <p>Belum punya akun? <a href="register.php">Register</a></p>
    <a href="index.php" class="home-btn">🏠 Home</a>
</div>
</body>
</html>
