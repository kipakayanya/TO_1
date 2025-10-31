<?php
session_start();
require 'config/db_connect.php';
$result = mysqli_query($conn, "SELECT * FROM products");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UBYSHOP</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                 <img src="images/logoubyshop.jpg" alt="LOGO UBYSHOP" style="height: 60px; width: 90px;">
            </div>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="produk.php">Produks</a></li>
                    <li><a href="cart.php">Cart</a></li>   
                    <?php if (isset($_SESSION['id_user'])): ?>
                        <li><a href="logout.php" class="logout-btn">Logout</a></li>
                    <?php else: ?>
                        <li><a href="login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </header>

        <main>
            <section class="hero">
                <h2>Welcome to UBYSHOP</h2>
                <p>Your one-stop shop for all your needs!</p>
                <button onclick="location.href='produk.php'">BELI SEKARANG</button>
            </section>
        </main>

        <footer>
            <p>Be your self at UBYSHOP</p>
        </footer>
    </div>
</body>
</html>
