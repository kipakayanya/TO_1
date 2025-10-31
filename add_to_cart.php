<?php
session_start();
require 'config/db_connect.php';

if (!isset($_SESSION['id_user'])) {
    echo 'not_logged_in';
    exit;
}

$id_user    = $_SESSION['id_user'];
$id_product = $_POST['id'] ?? null;

if ($id_product) {
    $sql = "INSERT INTO carts (id_user, id_product, quantity)
            VALUES (?, ?, 1)
            ON DUPLICATE KEY UPDATE quantity = quantity + 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $id_user, $id_product);
    $stmt->execute();
    echo 'success';
} else {
    echo 'error';
}
