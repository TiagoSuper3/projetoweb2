<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$cartCount = 0;
if (!empty($_SESSION['cart'])) {
  foreach ($_SESSION['cart'] as $qty) $cartCount += (int)$qty;
}
?>
<!doctype html>
<html lang="pt">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Loja</title>
  <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="topbar">
  <div class="container">
    <a class="brand" href="index.php">🛒 Loja</a>
    <nav class="nav">
      <a href="produtos.php">Produtos</a>
      <a href="carrinho.php">Carrinho (<?= $cartCount ?>)</a>
      <a href="admin/login.php">Admin</a>
    </nav>
  </div>
</header>

<main class="container">