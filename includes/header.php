<?php

require_once __DIR__ . '/../config/app.php';

if (session_status() === PHP_SESSION_NONE) session_start();

require_once __DIR__ . "/csrf.php";

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
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
</head>
<body>
<header class="topbar">
  <div class="container">
    <a class="brand" href="<?= BASE_URL ?>/index.php">🛒 Loja</a>
    <nav class="nav">

    <a href="<?= BASE_URL ?>/produtos.php">Produtos</a>
    <a href="<?= BASE_URL ?>/carrinho.php">Carrinho</a>

    <?php if (empty($_SESSION['user'])): ?>

      <a href="<?= BASE_URL ?>/login.php">Conta / Login</a>

    <?php else: ?>

      <div class="account-menu">
        <button class="account-btn">
          <?= htmlspecialchars($_SESSION['user']['name']) ?> ▾
        </button>

        <div class="account-dropdown">
          <a href="<?= BASE_URL ?>/account/profile.php">Perfil</a>
          <a href="<?= BASE_URL ?>/account/orders.php">Encomendas</a>

          <?php if ($_SESSION['user']['role'] === 'admin'): ?>
            <a href="<?= BASE_URL ?>/admin/dashboard.php">Painel Admin</a>
          <?php endif; ?>

          <form method="post" action="<?= BASE_URL ?>/logout.php">
            <?php csrf_field(); ?>
            <button type="submit">Logout</button>
          </form>
        </div>
      </div>

    <?php endif; ?>

    </nav>
  </div>
</header>

<main class="container">