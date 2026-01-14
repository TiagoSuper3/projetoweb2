<?php
require __DIR__ . "/config/db.php";
require __DIR__ . "/includes/header.php";

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$p = $stmt->fetch();

if (!$p) {
  echo "<p class='notice'>Produto não encontrado.</p>";
  require __DIR__ . "/includes/footer.php";
  exit;
}
?>
<h1><?= htmlspecialchars($p['name']) ?></h1>
<p><?= nl2br(htmlspecialchars($p['description'] ?? '')) ?></p>
<p><strong><?= number_format((float)$p['price'], 2, ',', '.') ?> €</strong></p>
<p>Stock: <?= (int)$p['stock'] ?></p>

<?php if ((int)$p['stock'] > 0): ?>
  <form action="add_to_cart.php" method="post" style="max-width:220px">
    <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">
    <label>Quantidade</label>
    <input class="input" type="number" name="qty" min="1" max="<?= (int)$p['stock'] ?>" value="1">
    <br><br>
    <button class="btn" type="submit">Adicionar ao carrinho</button>
  </form>
<?php else: ?>
  <p class="notice">Sem stock.</p>
<?php endif; ?>

<p><a class="btn secondary" href="produtos.php">Voltar</a></p>
<?php require __DIR__ . "/includes/footer.php"; ?>
