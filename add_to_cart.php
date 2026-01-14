<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require __DIR__ . "/config/db.php";

$productId = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
$qty = isset($_POST['qty']) ? (int)$_POST['qty'] : 1;
if ($qty < 1) $qty = 1;

$stmt = $pdo->prepare("SELECT id, stock FROM products WHERE id = ?");
$stmt->execute([$productId]);
$p = $stmt->fetch();

if (!$p || (int)$p['stock'] <= 0) {
  header("Location: produtos.php");
  exit;
}

$max = (int)$p['stock'];
if ($qty > $max) $qty = $max;

if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
$_SESSION['cart'][$productId] = min($max, (int)($_SESSION['cart'][$productId] ?? 0) + $qty);

header("Location: carrinho.php");
exit;
    