<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require __DIR__ . "/config/db.php";
require __DIR__ . "/includes/header.php";
require_once __DIR__ . "/includes/csrf.php";

$cart = $_SESSION['cart'] ?? [];
if (!$cart) {
  echo "<p class='notice'>Carrinho vazio. <a href='produtos.php'>Ver produtos</a></p>";
  require __DIR__ . "/includes/footer.php";
  exit;
}

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  csrf_validate();

  $name = trim($_POST['name'] ?? "");
  $address = trim($_POST['address'] ?? "");

  if ($name === "" || $address === "") {
    $error = "Preenche nome e morada.";
  } else {
    // buscar produtos do carrinho
    $ids = array_keys($cart);
    $ph = implode(",", array_fill(0, count($ids), "?"));
    $stmt = $pdo->prepare("SELECT id, name, price, stock FROM products WHERE id IN ($ph)");
    $stmt->execute($ids);
    $products = $stmt->fetchAll();

    $byId = [];
    foreach ($products as $p) $byId[(int)$p['id']] = $p;

    // validar stock + calcular total
    $total = 0.0;
    $items = [];
    foreach ($cart as $pid => $qty) {
      $pid = (int)$pid; $qty = (int)$qty;
      if (!isset($byId[$pid])) continue;

      $p = $byId[$pid];
      $stock = (int)$p['stock'];
      if ($qty > $stock) { $qty = $stock; } // ajusta se necessário
      if ($qty < 1) continue;

      $price = (float)$p['price'];
      $total += $price * $qty;

      $items[] = [
        'product_id' => $pid,
        'product_name' => $p['name'],
        'quantity' => $qty,
        'price' => $price
      ];
    }

    if (!$items) {
      $error = "Carrinho inválido.";
    } else {
      // transação: cria order + items + baixa stock
      $userId = $_SESSION['user']['id'] ?? null;
      $pdo->beginTransaction();
      try {
        $stmt = $pdo->prepare("
          INSERT INTO orders (user_id, customer_name, customer_address, total, status)
          VALUES (?, ?, ?, ?, 'pendente')
        ");

        $stmt->execute([
          $userId,
          $name,
          $address,
          $total
        ]);

        $orderId = (int)$pdo->lastInsertId();

        $stmtItem = $pdo->prepare("INSERT INTO order_items (order_id, product_id, product_name, quantity, price) VALUES (?, ?, ?, ?, ?)");
        $stmtStock = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?");

        foreach ($items as $it) {
          // baixa stock com segurança
          $stmtStock->execute([$it['quantity'], $it['product_id'], $it['quantity']]);
          if ($stmtStock->rowCount() !== 1) {
            throw new RuntimeException("Sem stock para um dos produtos.");
          }
          $stmtItem->execute([$orderId, $it['product_id'], $it['product_name'], $it['quantity'], $it['price']]);
        }

        $pdo->commit();
        $_SESSION['cart'] = []; // limpa carrinho
        header("Location: pedido_sucesso.php?id=" . $orderId);
        exit;
      } catch (Throwable $e) {
        $pdo->rollBack();
        $error = "Não foi possível finalizar a encomenda. Tenta novamente.";
      }
    }
  }
}
?>

<h1>Checkout</h1>
<?php if ($error): ?>
  <p class="notice"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<form method="post" style="max-width:520px">

  <?php csrf_field(); ?>

  <label>Nome</label>
  <input class="input" name="name" value="<?= htmlspecialchars($_POST['name'] ?? '') ?>" />
  <br><br>
  <label>Morada</label>
  <input class="input" name="address" value="<?= htmlspecialchars($_POST['address'] ?? '') ?>" />
  <br><br>
  <button class="btn" type="submit">Finalizar Encomenda</button>
  <a class="btn secondary" href="carrinho.php">Voltar ao carrinho</a>
</form>

<?php require __DIR__ . "/includes/footer.php"; ?>
