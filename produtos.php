<?php
require __DIR__ . "/config/db.php";
require __DIR__ . "/includes/header.php";

/* =========================
   PAGINAÇÃO
========================= */
$perPage = 8; // produtos por página
$page = max(1, (int)($_GET['page'] ?? 1));
$offset = ($page - 1) * $perPage;

/* =========================
   FILTROS
========================= */
$q     = trim($_GET['q'] ?? '');
$order = $_GET['order'] ?? '';
$min   = $_GET['min'] ?? '';
$max   = $_GET['max'] ?? '';

/* =========================
   COUNT (TOTAL DE PRODUTOS)
========================= */
$countSql = "SELECT COUNT(*) FROM products WHERE 1=1";
$countParams = [];

if ($q !== '') {
  $countSql .= " AND name LIKE ?";
  $countParams[] = "%$q%";
}
if ($min !== '') {
  $countSql .= " AND price >= ?";
  $countParams[] = $min;
}
if ($max !== '') {
  $countSql .= " AND price <= ?";
  $countParams[] = $max;
}

$stmt = $pdo->prepare($countSql);
$stmt->execute($countParams);
$totalProducts = (int)$stmt->fetchColumn();
$totalPages = (int)ceil($totalProducts / $perPage);

/* =========================
   QUERY PRINCIPAL
========================= */
$sql = "SELECT id, name, price, stock, image FROM products WHERE 1=1";
$params = [];

if ($q !== '') {
  $sql .= " AND name LIKE ?";
  $params[] = "%$q%";
}
if ($min !== '') {
  $sql .= " AND price >= ?";
  $params[] = $min;
}
if ($max !== '') {
  $sql .= " AND price <= ?";
  $params[] = $max;
}

if ($order === 'price_asc') {
  $sql .= " ORDER BY price ASC";
} elseif ($order === 'price_desc') {
  $sql .= " ORDER BY price DESC";
} else {
  $sql .= " ORDER BY id DESC";
}

$sql .= " LIMIT $perPage OFFSET $offset";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
?>

<h1>Produtos</h1>

<form method="get" class="filters">
  <input class="input" name="q" placeholder="Pesquisar produto"
         value="<?= htmlspecialchars($q) ?>">

  <select class="input" name="order">
    <option value="">Ordenar</option>
    <option value="price_asc" <?= $order === 'price_asc' ? 'selected' : '' ?>>Preço ↑</option>
    <option value="price_desc" <?= $order === 'price_desc' ? 'selected' : '' ?>>Preço ↓</option>
  </select>

  <input class="input" type="number" step="0.01" name="min"
         placeholder="Preço mínimo"
         value="<?= htmlspecialchars($min) ?>">

  <input class="input" type="number" step="0.01" name="max"
         placeholder="Preço máximo"
         value="<?= htmlspecialchars($max) ?>">

  <button class="btn secondary">Filtrar</button>
  <a class="btn secondary" href="produtos.php">Limpar</a>
</form>

<?php if ($products): ?>
  <div id="products-area">

    <!-- GRID DE PRODUTOS (fallback PHP) -->
    <div class="grid">
      <?php foreach ($products as $p): ?>
        <?php include __DIR__ . "/includes/product_card.php"; ?>
      <?php endforeach; ?>
    </div>

    <!-- PAGINAÇÃO (fallback PHP) -->
    <?php if ($totalPages > 1): ?>
      <nav class="pagination pagination-fallback">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <a href="#"
            class="page-btn <?= $i === $page ? 'active' : '' ?>"
            data-page="<?= $i ?>">
            <?= $i ?>
          </a>
        <?php endfor; ?>
      </nav>
    <?php endif; ?>

  </div>
<?php else: ?>
  <p class="notice">Nenhum produto encontrado.</p>
<?php endif; ?>

<?php require __DIR__ . "/includes/footer.php"; ?>
