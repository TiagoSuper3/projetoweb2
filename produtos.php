<?php
require __DIR__ . "/config/db.php";
require __DIR__ . "/includes/header.php";

$stmt = $pdo->query("SELECT id, name, price, stock FROM products ORDER BY id DESC");
$products = $stmt->fetchAll();
?>
<h1>Produtos</h1>

<div class="grid">
  <?php foreach ($products as $p): ?>
    <div class="card">
      <h3><?= htmlspecialchars($p['name']) ?></h3>
      <p><strong><?= number_format((float)$p['price'], 2, ',', '.') ?> €</strong></p>
      <p>Stock: <?= (int)$p['stock'] ?></p>

      <div class="row">
        <a class="btn secondary" href="produto.php?id=<?= (int)$p['id'] ?>">Ver</a>

        <?php if ((int)$p['stock'] > 0): ?>
          <form action="add_to_cart.php" method="post">
            <input type="hidden" name="product_id" value="<?= (int)$p['id'] ?>">
            <button class="btn" type="submit">Adicionar</button>
          </form>
        <?php else: ?>
          <span>Sem stock</span>
        <?php endif; ?>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<?php require __DIR__ . "/includes/footer.php"; ?>
