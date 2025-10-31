<?php
session_start();
require 'config/db_connect.php';

$id_user = $_SESSION['id_user'] ?? 0;

$sql = "SELECT p.name, p.price, c.quantity, (p.price * c.quantity) as subtotal
        FROM carts c
        JOIN products p ON c.id_product = p.id_product
        WHERE c.id_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>UBYSHOP – Cart</title>
<style>
  * {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
  }

  body {
    font-family: Arial, sans-serif;
    background-color: #f4f4f4;
    color: #333;
    line-height: 1.6;
  }

  header {
    background-color: #701b0f;
    color: #fff;
    padding: 15px 20px;
    display: flex;
    align-items: center;
    justify-content: space-between;
  }

  header img {
    height: 50px;
  }

  nav a {
    color: #fff;
    text-decoration: none;
    margin-left: 20px;
    font-weight: bold;
    transition: color 0.3s;
  }
  nav a:hover {
    color: #ffdddd;
  }

  .title {
    background: #fff;
    padding: 20px;
    text-align: center;
  }
  .title h1 {
    font-size: 2rem;
    color: #701b0f;
  }

  .cart-container {
    background: #fff;
    margin: 20px auto;
    padding: 20px;
    max-width: 900px;
    border-radius: 8px;
    box-shadow: 0 0 8px rgba(0,0,0,0.1);
  }

  .cart-container h2 {
    margin-bottom: 15px;
    color: #701b0f;
  }

  table {
    width: 100%;
    border-collapse: collapse;
    margin-bottom: 15px;
  }

  table th, table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
  }

  table th {
    background-color: #f7f7f7;
    color: #701b0f;
  }

  .checkout-link {
    display: inline-block;
    padding: 10px 18px;
    background-color: #701b0f;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    transition: background 0.3s;
  }
  .checkout-link:hover {
    background-color: #912e20;
  }

  footer {
    background-color: #701b0f;
    color: #fff;
    text-align: center;
    padding: 15px 10px;
    margin-top: 30px;
  }

  @media(max-width: 600px) {
    nav a { margin-left: 10px; }
    table th, table td { font-size: 0.9rem; }
  }
</style>
</head>
<body>

<header>
  <img src="images/logoubyshop.jpg " alt="UBYSHOP Logo" />
  <nav>
    <a href="index.php">Home</a>
    <a href="produk.php">Produk</a>
    <a href="cart.php">Cart</a>
  </nav>
</header>

<div class="title">
  <h1>Keranjang Belanja</h1>
</div>

<div class="cart-container">
  <h2>Produk di Keranjang</h2>
  <table>
    <thead>
      <tr>
        <th>Produk</th>
        <th>Harga</th>
        <th>Jumlah</th>
        <th>Total</th>
      </tr>
    </thead>
    <tbody>
      <?php
      $grand_total = 0;
      if ($result->num_rows > 0):
        while($row = $result->fetch_assoc()):
          $grand_total += $row['subtotal'];
      ?>
        <tr>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td>Rp<?= number_format($row['price'],2,',','.') ?></td>
          <td><?= $row['quantity'] ?></td>
          <td>Rp<?= number_format($row['subtotal'],2,',','.') ?></td>
        </tr>
      <?php endwhile; ?>
        <tr>
          <td colspan="3" style="text-align:right;font-weight:bold;">Grand Total</td>
          <td style="font-weight:bold;">Rp<?= number_format($grand_total,2,',','.') ?></td>
        </tr>
      <?php else: ?>
        <tr>
          <td colspan="4" style="text-align:center;">Keranjang masih kosong.</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
  <a class="checkout-link" href="checkout.php">Checkout</a>
</div>

<footer style="text-align: end;">
  Be yourself at UBYSHOP
</footer>

</body>
</html>
