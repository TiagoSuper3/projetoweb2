<?php
require __DIR__ . "/config/db.php";
require __DIR__ . "/includes/header.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$id]);
$order = $stmt->fetch();

if (!$order) {
  echo "<p class='notice'>Encomenda não encontrada.</p>";
  require __DIR__ . "/includes/footer.php";
  exit;
}

$stmt = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ? ORDER BY id ASC");
$stmt->execute([$id]);
$items = $stmt->fetchAll();
?>

<h1>Encomenda criada ✅</h1>
<p class="notice">Número: <strong>#<?= (int)$order['id'] ?></strong> — Estado: <?= htmlspecialchars($order['status']) ?></p>
<p>Total: <strong><?= number_format((float)$order['total'], 2, ',', '.') ?> €</strong></p>

<h3>Itens</h3>
<ul>
  <?php foreach ($items as $it): ?>
    <li>
      <?= htmlspecialchars($it['product_name']) ?> — <?= (int)$it['quantity'] ?> x
      <?= number_format((float)$it['price'], 2, ',', '.') ?> €
    </li>
  <?php endforeach; ?>
</ul>

<p><a class="btn" href="produtos.php">Continuar a comprar</a></p>

<?php require __DIR__ . "/includes/footer.php"; ?>
