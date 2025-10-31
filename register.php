<?php
session_start();
require 'config/db_connect.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $email    = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirm  = trim($_POST['confirm']);

    if ($password !== $confirm) {
        $error = 'Password dan konfirmasi tidak sama!';
    } else {
        // cek username
        $check = mysqli_prepare($conn, "SELECT username FROM users WHERE username = ?");
        if (!$check) {
            die("Query prepare gagal: " . mysqli_error($conn));
        }
        mysqli_stmt_bind_param($check, 's', $username);
        mysqli_stmt_execute($check);
        $result = mysqli_stmt_get_result($check);

        if (mysqli_num_rows($result) > 0) {
            $error = 'Username sudah terpakai!';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($conn, "INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            if (!$stmt) {
                die("Query insert gagal: " . mysqli_error($conn));
            }
            mysqli_stmt_bind_param($stmt, 'sss', $username, $email, $hash);
            if (mysqli_stmt_execute($stmt)) {
                $success = 'Registrasi berhasil, silakan <a href="login.php">login</a>.';
            } else {
                $error = 'Terjadi kesalahan: ' . mysqli_error($conn);
            }
        }
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register | UBYSHOP</title>
    <style>
.register-container {
  max-width: 400px;
  margin: 80px auto;
  padding: 30px;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.register-container h1 {
  text-align: center;
  color: #75180c;
  margin-bottom: 25px;
}

.register-container label {
  display: block;
  margin-bottom: 5px;
  font-weight: bold;
}

.register-container input {
  width: 100%;
  padding: 10px;
  margin-bottom: 15px;
  border: 1px solid #ccc;
  border-radius: 8px;
}

.register-container button {
  width: 100%;
  background: #75180c;
  color: #fff;
  border: none;
  padding: 15px;
  border-radius: 9px;
  cursor: pointer;
  font-weight: bold;
   
}

.register-container button:hover {
  background: #75180c;
  
}
    </style>
    <link rel="stylesheet" href="css/styles.css">
</head>

<body>
<div class="register-container">
    <h1>Daftar Akun UBYSHOP</h1>
    <form action="" method="post">
        <label for="username">Username</label>
        <input type="text" name="username" id="username" required>

        <label for="email">Email</label>
        <input type="email" name="email" id="email" required>

        <label for="password">Password</label>asjdasdiuashdiusahiudhi
        <input type="password" name="password" id="password" required>

        <label for="confirm">Konfirmasi Password</label>
        <input type="password" name="confirm" id="confirm" required>

        <button onclick="location.href='login.php'">Register</button>
    </form>
    <p>Sudah punya akun? <a href="login.php" >Login</a></p>
</div>
</body>
</html>
