<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require __DIR__ . "/config/db.php";
require __DIR__ . "/includes/header.php";

$cart = $_SESSION['cart'] ?? [];
$items = [];
$total = 0.0;

if ($cart) {
  $ids = array_keys($cart);
  $placeholders = implode(",", array_fill(0, count($ids), "?"));
  $stmt = $pdo->prepare("SELECT id, name, price, stock FROM products WHERE id IN ($placeholders)");
  $stmt->execute($ids);
  $products = $stmt->fetchAll();

  // index por id
  $byId = [];
  foreach ($products as $p) $byId[(int)$p['id']] = $p;

  foreach ($cart as $pid => $qty) {
    $pid = (int)$pid; $qty = (int)$qty;
    if (!isset($byId[$pid])) continue;

    $p = $byId[$pid];
    $price = (float)$p['price'];
    $line = $price * $qty;
    $total += $line;

    $items[] = [
      'id' => $pid,
      'name' => $p['name'],
      'price' => $price,
      'stock' => (int)$p['stock'],
      'qty' => $qty,
      'line' => $line
    ];
  }
}
?>

<h1>Carrinho</h1>

<?php if (!$items): ?>
  <p class="notice">O carrinho está vazio.</p>
  <p><a class="btn" href="produtos.php">Ir às compras</a></p>
<?php else: ?>
  <table class="table">
    <thead>
      <tr>
        <th>Produto</th>
        <th>Preço</th>
        <th>Qtd</th>
        <th>Subtotal</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($items as $it): ?>
        <tr>
          <td><?= htmlspecialchars($it['name']) ?></td>
          <td><?= number_format($it['price'], 2, ',', '.') ?> €</td>
          <td style="max-width:180px">
            <form action="update_cart.php" method="post" class="row" style="justify-content:flex-start">
              <input type="hidden" name="product_id" value="<?= (int)$it['id'] ?>">
              <input class="input" style="max-width:90px" type="number" name="qty" min="1" max="<?= (int)$it['stock'] ?>" value="<?= (int)$it['qty'] ?>">
              <button class="btn secondary" type="submit">Atualizar</button>
            </form>
          </td>
          <td><?= number_format($it['line'], 2, ',', '.') ?> €</td>
          <td>
            <form action="remove_from_cart.php" method="post">
              <input type="hidden" name="product_id" value="<?= (int)$it['id'] ?>">
              <button class="btn secondary" type="submit">Remover</button>
            </form>
          </td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>

  <p><strong>Total: <?= number_format($total, 2, ',', '.') ?> €</strong></p>

  <div class="row" style="justify-content:flex-start">
    <a class="btn secondary" href="produtos.php">Continuar a comprar</a>
    <a class="btn" href="checkout.php">Checkout</a>
  </div>
<?php endif; ?>

<?php require __DIR__ . "/includes/footer.php"; ?>
