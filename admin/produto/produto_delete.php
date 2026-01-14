<?php
require __DIR__ . "/../../includes/_admin_guard.php";
require __DIR__ . "/../../config/db.php";

$id = (int)$_GET['id'];
$pdo->prepare("DELETE FROM products WHERE id=?")->execute([$id]);

header("Location: produtos.php");
exit;
