<?php
require __DIR__ . "/config/db.php";
require __DIR__ . "/includes/header.php";

$id = (int)($_GET['id'] ?? 0);

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();

if (!$p) {
  echo "<p class='notice'>Produto não encontrado.</p>";
  require __DIR__ . "/includes/footer.php";
  exit;
}

include __DIR__ . "/includes/product_detail.php";

require __DIR__ . "/includes/footer.php";
