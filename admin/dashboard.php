<?php
require __DIR__ . "/_guard.php";
require __DIR__ . "/../config/db.php";

$stmt = $pdo->query("SELECT id, customer_name, total, status, created_at FROM orders ORDER BY id DESC LIMIT 50");
$orders = $stmt->fetchAll();
?>
<!doctype html>
<html lang="pt"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="../assets/css/style.css">
<title>Dashboard</title>
</head><body>
<main class="container">
  <div class="row">
    <h1>Dashboard</h1>
    <a class="btn secondary" href="produto/produtos.php">Alterar/Adicionar Produto</a>
    <a class="btn secondary" href="logout.php">Sair</a>
  </div>

  <h3>Encomendas</h3>
  <table class="table">
    <thead><tr><th>#</th><th>Cliente</th><th>Total</th><th>Estado</th><th>Data</th></tr></thead>
    <tbody>
      <?php foreach ($orders as $o): ?>
        <tr>
          <td><?= (int)$o['id'] ?></td>
          <td><?= htmlspecialchars($o['customer_name']) ?></td>
          <td><?= number_format((float)$o['total'], 2, ',', '.') ?> €</td>
          <td><?= htmlspecialchars($o['status']) ?></td>
          <td><?= htmlspecialchars($o['created_at']) ?></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</main>
</body></html>
