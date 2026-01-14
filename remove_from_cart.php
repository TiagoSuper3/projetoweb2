<?php
if (session_status() === PHP_SESSION_NONE) session_start();
$pid = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;

if (!empty($_SESSION['cart'][$pid])) {
  unset($_SESSION['cart'][$pid]);
}
header("Location: carrinho.php");
exit;
