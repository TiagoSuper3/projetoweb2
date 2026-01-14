<?php
require __DIR__ . "/../../includes/_admin_guard.php";
require __DIR__ . "/../../config/db.php";

$id = $_GET['id'] ?? null;
$product = [
  'name' => '',
  'description' => '',
  'price' => '',
  'stock' => '',
  'image' => null
];

if ($id) {
  $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
  $stmt->execute([$id]);
  $product = $stmt->fetch();
  if (!$product) {
    header("Location: produtos.php");
    exit;
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

  $name  = trim($_POST['name']);
  $desc  = trim($_POST['description']);
  $price = (float) $_POST['price'];
  $stock = (int) $_POST['stock'];

  $imageName = $product['image']; // mantém a atual por defeito

  /* =========================
     UPLOAD DA IMAGEM
  ========================= */

  if (!empty($_FILES['image']['name'])) {

    $allowed = ['jpg','jpeg','png','webp'];
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));

    if (in_array($ext, $allowed)) {

      $imageName = uniqid('prod_') . '.' . $ext;
      $uploadPath = __DIR__ . "/../../assets/uploads/" . $imageName;

      move_uploaded_file($_FILES['image']['tmp_name'], $uploadPath);
    }
  }

  /* =========================
     INSERT / UPDATE
  ========================= */

  if ($id) {
    $stmt = $pdo->prepare("
      UPDATE products
      SET name = ?, description = ?, price = ?, stock = ?, image = ?
      WHERE id = ?
    ");
    $stmt->execute([$name, $desc, $price, $stock, $imageName, $id]);
  } else {
    $stmt = $pdo->prepare("
      INSERT INTO products (name, description, price, stock, image)
      VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute([$name, $desc, $price, $stock, $imageName]);
  }

  header("Location: produtos.php");
  exit;
}
?>
<!doctype html>
<html lang="pt">
<head>
  <meta charset="utf-8">
  <title><?= $id ? 'Editar' : 'Novo' ?> Produto</title>
  <link rel="stylesheet" href="/loja/assets/css/style.css">
</head>
<body>

<main class="container">

<h1><?= $id ? 'Editar' : 'Novo' ?> Produto</h1>

<form method="post" enctype="multipart/form-data">

  <?php if (!empty($product['image'])): ?>
    <p>
      <img src="/loja/assets/uploads/<?= htmlspecialchars($product['image']) ?>"
           style="max-width:180px;border-radius:12px">
    </p>
  <?php endif; ?>

  <label>Imagem</label>
  <input type="file" name="image" accept="image/*">
  <br><br>

  <label>Nome</label>
  <input class="input" name="name" required
         value="<?= htmlspecialchars($product['name']) ?>">
  <br><br>

  <label>Descrição</label>
  <textarea class="input" name="description" rows="4"><?= htmlspecialchars($product['description']) ?></textarea>
  <br><br>

  <label>Preço</label>
  <input class="input" type="number" step="0.01" name="price" required
         value="<?= htmlspecialchars($product['price']) ?>">
  <br><br>

  <label>Stock</label>
  <input class="input" type="number" name="stock" required
         value="<?= htmlspecialchars($product['stock']) ?>">
  <br><br>

  <button class="btn">Guardar</button>
  <a class="btn secondary" href="produtos.php">Cancelar</a>

</form>

</main>

</body>
</html>
