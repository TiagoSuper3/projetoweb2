<?php
require __DIR__ . "/../includes/_admin_guard.php";
require __DIR__ . "/../config/db.php";
require __DIR__ . "/../includes/csrf.php";

$id = (int)($_GET['id'] ?? 0);
if (!$id) {
  header("Location: dashboard.php");
  exit;
}

/* buscar encomenda */
$stmt = $pdo->prepare("
  SELECT id, customer_name, customer_address, total,
         status, tracking_number, created_at
  FROM orders
  WHERE id = ?
");
$stmt->execute([$id]);
$order = $stmt->fetch();

if (!$order) {
  header("Location: dashboard.php");
  exit;
}

/* update */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  csrf_validate();

  $status = $_POST['status'] ?? 'pendente';
  $tracking = trim($_POST['tracking_number'] ?? '');

  $stmt = $pdo->prepare("
    UPDATE orders
    SET status = ?, tracking_number = ?
    WHERE id = ?
  ");
  $stmt->execute([$status, $tracking ?: null, $id]);

  header("Location: order_edit.php?id=$id&saved=1");
  exit;
}
?>
<!doctype html>
<html lang="pt">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="../assets/css/style.css">
<title>Editar Encomenda</title>
</head>
<body>

<main class="container">

  <div class="row" style="margin-bottom:20px">
    <h1>Encomenda #<?= (int)$order['id'] ?></h1>
    <a class="btn secondary" href="dashboard.php">Voltar</a>
  </div>

  <?php if (isset($_GET['saved'])): ?>
    <p class="notice">Encomenda atualizada com sucesso.</p>
  <?php endif; ?>

  <div class="card" style="max-width:520px">

    <p><strong>Cliente:</strong> <?= htmlspecialchars($order['customer_name']) ?></p>
    <p><strong>Morada:</strong> <?= htmlspecialchars($order['customer_address']) ?></p>
    <p><strong>Total:</strong> <?= number_format($order['total'], 2, ',', '.') ?> €</p>
    <p><strong>Data:</strong> <?= htmlspecialchars($order['created_at']) ?></p>

    <form method="post" style="margin-top:20px">
      <?php csrf_field(); ?>

      <label>Estado</label>
      <select class="input" name="status">
        <?php
          $states = ['pendente', 'pago', 'enviado', 'cancelado'];
          foreach ($states as $s):
        ?>
          <option value="<?= $s ?>" <?= $order['status'] === $s ? 'selected' : '' ?>>
            <?= ucfirst($s) ?>
          </option>
        <?php endforeach; ?>
      </select>

      <br><br>

      <label>Tracking number</label>
      <input
        class="input"
        name="tracking_number"
        placeholder="Ex: CTT123456789PT"
        value="<?= htmlspecialchars($order['tracking_number'] ?? '') ?>"
      >

      <br><br>

      <button class="btn">Guardar alterações</button>
    </form>

  </div>

</main>
</body>
</html>
