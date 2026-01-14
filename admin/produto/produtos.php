<?php
require __DIR__ . "/../../includes/_admin_guard.php";
require __DIR__ . "/../../config/db.php";

$products = $pdo->query("SELECT * FROM products ORDER BY id DESC")->fetchAll();
?>
<!doctype html>
<html lang="pt">
<head>
<link rel="stylesheet" href="../../assets/css/style.css">
<title>Produtos</title>
</head>
<body>
<main class="container">
<h1>Produtos</h1>

<a class="btn" href="produto_form.php">+ Novo Produto</a>

<table class="table">
<tr>
  <th>ID</th><th>Nome</th><th>Preço</th><th>Stock</th><th></th>
</tr>
<?php foreach ($products as $p): ?>
<tr>
  <td><?= $p['id'] ?></td>
  <td><?= htmlspecialchars($p['name']) ?></td>
  <td><?= number_format($p['price'],2,',','.') ?> €</td>
  <td><?= $p['stock'] ?></td>
  <td>
    <a class="btn secondary" href="produto_form.php?id=<?= $p['id'] ?>">Editar</a>
    <a class="btn secondary" href="produto_delete.php?id=<?= $p['id'] ?>" onclick="return confirm('Apagar?')">Apagar</a>
  </td>
</tr>
<?php endforeach; ?>
</table>

<a class="btn secondary" href="../dashboard.php">Voltar</a>
</main>
</body>
</html>
