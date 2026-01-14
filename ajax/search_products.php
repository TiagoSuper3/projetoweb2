<?php
require __DIR__ . "/../config/db.php";

$q   = trim($_GET['q'] ?? '');
$min = $_GET['min'] ?? '';
$max = $_GET['max'] ?? '';

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

$sql .= " ORDER BY id DESC";

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
echo json_encode($stmt->fetchAll());
