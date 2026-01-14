<?php
require __DIR__ . "/../config/db.php";

/* =========================
   PAGINAÇÃO
========================= */
$perPage = 8;
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
   COUNT
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

/* =========================
   HTML DOS PRODUTOS
========================= */
ob_start();
foreach ($products as $p) {
  include __DIR__ . "/../includes/product_card.php";
}
$productsHtml = ob_get_clean();

/* =========================
   HTML DA PAGINAÇÃO
========================= */
ob_start();
if ($totalPages > 1):
?>
<nav class="pagination">
<?php for ($i = 1; $i <= $totalPages; $i++): ?>
  <a href="#" class="page-btn <?= $i === $page ? 'active' : '' ?>"
     data-page="<?= $i ?>">
     <?= $i ?>
  </a>
<?php endfor; ?>
</nav>
<?php
endif;
$paginationHtml = ob_get_clean();

/* =========================
   RESPONSE
========================= */
echo json_encode([
  'products'   => $productsHtml,
  'pagination' => $paginationHtml
]);
