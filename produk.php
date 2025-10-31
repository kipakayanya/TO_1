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
    <script>
    function addToCart(id) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "add_to_cart.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    if (xhr.responseText.trim() === "success") {
                        alert("Produk berhasil ditambahkan ke keranjang!");
                    } else if (xhr.responseText.trim() === "not_logged_in") {
                        alert("Silakan login terlebih dahulu.");
                        window.location.href = "login.php";
                    } else {
                        alert("Gagal menambah ke keranjang.");
                    }
                }
            }
        };
        xhr.send("id=" + encodeURIComponent(id));
    }
    </script>
</head>
<body>
    <div class="container">
        <header>
            <div class="logo">
                 <img src="images/logoubyshop.jpg" alt="LOGO UBYSHOP" style="height: 60px; width: 90px;">
                <!-- <h1>UBYSHOP</h1> -->
            </div>
            <nav>
                <ul>
                    <li><a href="login.php">Home</a></li>
                    <li><a href="checkout.php">Produk</a></li>
                    <li><a href="cart.php">Cart</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <section class="hero">
                <h2>Welcome to UBYSHOP</h2>
                <p>Your one-stop shop for all your needs!</p>
            </section>
            <section class="products">
                <h2>Featured Products</h2>
                <div class="product-list">
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                    <div class="product-item">
                        <img src="images/<?= htmlspecialchars($row['image_url'] ?? 'benda.jpg',) ?>" alt="<?= htmlspecialchars($row['name']) ?>" style="height: 100px; width: 100px;">
                        <h3><?= htmlspecialchars($row['name']) ?></h3>
                        <p>Rp<?= number_format($row['price'],2,',','.') ?></p>
                        <button onclick="addToCart(<?= $row['id_product'] ?>)">Add to Cart</button>
                    </div>
                <?php endwhile; ?>
                </div>
            </section>
        </main>

        <footer>
            <p>Be your self at UBYSHOP</p>
        </footer>
    </div>
</body>
</html>