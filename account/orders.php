<?php
require __DIR__ . "/../includes/_auth.php";
require __DIR__ . "/../config/db.php";
require __DIR__ . "/../includes/header.php";

$userId = $_SESSION['user']['id'];

/* buscar encomendas do utilizador */
$stmt = $pdo->prepare("
  SELECT id, customer_name, customer_address, total,
         status, tracking_number, created_at
  FROM orders
  WHERE user_id = ?
  ORDER BY created_at DESC
");
$stmt->execute([$userId]);
$orders = $stmt->fetchAll();
?>

<h1>As minhas encomendas</h1>

<?php if (!$orders): ?>
  <p class="notice">Ainda não tens encomendas.</p>
<?php else: ?>

<?php foreach ($orders as $order): ?>

  <div class="card" style="margin-bottom:26px">

    <!-- HEADER -->
    <div class="row" style="margin-bottom:10px">
      <strong>Encomenda #<?= (int)$order['id'] ?></strong>
      <span><?= date('d/m/Y', strtotime($order['created_at'])) ?></span>
    </div>

    <!-- CLIENTE -->
    <p><strong>Nome:</strong>
      <?= htmlspecialchars($order['customer_name']) ?>
    </p>

    <p><strong>Morada:</strong>
      <?= htmlspecialchars($order['customer_address']) ?>
    </p>

    <!-- INFO -->
    <p><strong>Estado:</strong>
      <?= htmlspecialchars($order['status']) ?>
    </p>

    <p><strong>Total:</strong>
      <?= number_format($order['total'], 2, ',', '.') ?> €
    </p>

    <p><strong>Tracking:</strong>
      <?= $order['tracking_number']
        ? htmlspecialchars($order['tracking_number'])
        : '<span style="opacity:.6">Aguardando envio</span>' ?>
    </p>

    <!-- PRODUTOS DA ENCOMENDA -->
    <?php
      $stmt = $pdo->prepare("
        SELECT product_name, quantity, price
        FROM order_items
        WHERE order_id = ?
      ");
      $stmt->execute([$order['id']]);
      $items = $stmt->fetchAll();
    ?>

    <table class="table" style="margin-top:14px">
      <thead>
        <tr>
          <th>Produto</th>
          <th>Qtd</th>
          <th>Preço</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($items as $it): ?>
          <tr>
            <td><?= htmlspecialchars($it['product_name']) ?></td>
            <td><?= (int)$it['quantity'] ?></td>
            <td><?= number_format($it['price'], 2, ',', '.') ?> €</td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

  </div>

<?php endforeach; ?>

<?php endif; ?>

<?php require __DIR__ . "/../includes/footer.php"; ?>
