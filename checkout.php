<?php
session_start();
require 'config/db_connect.php';

if (!isset($_SESSION['id_user'])) {
    header('Location: login.php');
    exit;
}

$id_user = $_SESSION['id_user'];
// Ambil data keranjang
$sql = "SELECT p.name, p.price, c.quantity, (p.price * c.quantity) as subtotal
        FROM carts c
        JOIN products p ON c.id_product = p.id_product
        WHERE c.id_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

$grand_total = 0;
$cart_items = [];
while ($row = $result->fetch_assoc()) {
    $cart_items[] = $row;
    $grand_total += $row['subtotal'];
}

$success = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = trim($_POST['nama']);
    $alamat = trim($_POST['alamat']);
    $telepon = trim($_POST['telepon']);

    if ($nama === '' || $alamat === '' || $telepon === '') {
        $error = 'Semua field wajib diisi!';
    } elseif (empty($cart_items)) {
        $error = 'Keranjang belanja kosong!';
    } else {
        // Simpan order
        $stmt = $conn->prepare("INSERT INTO orders (id_user, total_price, order_date) VALUES (?, ?, NOW())");
        $stmt->bind_param("ii", $id_user, $grand_total);
        if ($stmt->execute()) {
            $id_order = $stmt->insert_id;
            // Simpan order_items
            $stmt_item = $conn->prepare("INSERT INTO order_items (id_order, id_product, quantity, price) VALUES (?, ?, ?, ?)");
            foreach ($cart_items as $item) {
                $stmt_item->bind_param("iiid", $id_order, $item['id_product'], $item['quantity'], $item['price']);
                $stmt_item->execute();
            }
            // Kosongkan keranjang
            $conn->query("DELETE FROM carts WHERE id_user = $id_user");
            $success = "Pembayaran berhasil! Terima kasih, $nama.<br>Alamat: $alamat<br>No. Telepon: $telepon<br>Total: Rp" . number_format($grand_total,2,',','.');
        } else {
            $error = 'Gagal menyimpan order.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout | UBYSHOP</title>
    <link rel="stylesheet" href="css/styles.css">
    <style>
    .checkout-container {
        max-width: 600px;
        margin: 40px auto;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        padding: 30px;
    }
    .checkout-container h2 {
        color: #75180c;
        margin-bottom: 20px;
    }
    .checkout-container table {
        width: 100%;
        margin-bottom: 20px;
        border-collapse: collapse;
    }
    .checkout-container th, .checkout-container td {
        padding: 8px;
        border-bottom: 1px solid #eee;
        text-align: left;
    }
    .checkout-container label {
        display: block;
        margin-top: 10px;
        font-weight: bold;
    }
    .checkout-container input, .checkout-container textarea {
        width: 100%;
        padding: 8px;
        margin-top: 4px;
        border: 1px solid #ccc;
        border-radius: 6px;
        margin-bottom: 10px;
    }
    .checkout-container button {
        background: #75180c;
        color: #fff;
        border: none;
        padding: 12px 30px;
        border-radius: 8px;
        font-weight: bold;
        cursor: pointer;
        margin-top: 10px;
    }
    .checkout-container .success {
        color: green;
        margin-bottom: 10px;
    }
    .checkout-container .error {
        color: red;
        margin-bottom: 10px;
    }
    </style>
</head>
<body>
<div class="checkout-container">
    <h2>Nota Pembayaran</h2>
    <?php if ($success): ?>
        <div class="success"><?= $success ?></div>
    <?php elseif ($error): ?>
        <div class="error"><?= $error ?></div>
    <?php endif; ?>

    <h3>Ringkasan Belanja</h3>
    <table>
        <thead>
            <tr>
                <th>Produk</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($cart_items)): ?>
            <?php foreach ($cart_items as $item): ?>
            <tr>
                <td><?= htmlspecialchars($item['name']) ?></td>
                <td>Rp<?= number_format($item['price'],2,',','.') ?></td>
                <td><?= $item['quantity'] ?></td>
                <td>Rp<?= number_format($item['subtotal'],2,',','.') ?></td>
            </tr>
            <?php endforeach; ?>
            <tr>
                <td colspan="3" style="text-align:right;font-weight:bold;">Total</td>
                <td style="font-weight:bold;">Rp<?= number_format($grand_total,2,',','.') ?></td>
            </tr>
        <?php else: ?>
            <tr>
                <td colspan="4" style="text-align:center;">Keranjang kosong.</td>
            </tr>
        <?php endif; ?>
        </tbody>
    </table>

    <form method="post">
        <label for="nama">Nama Lengkap</label>
        <input type="text" name="nama" id="nama" required>

        <label for="alamat">Alamat Lengkap</label>
        <textarea name="alamat" id="alamat" rows="3" required></textarea>

        <label for="telepon">Nomor Telepon</label>
        <input type="text" name="telepon" id="telepon" required>

        <button type="submit">Konfirmasi & Bayar</button>
             <button onclick="location.href='index.php'">kembali</button>
    </form>
</div>

</body>
</html>
