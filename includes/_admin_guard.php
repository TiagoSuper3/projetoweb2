<?php
require __DIR__ . "/_auth.php";

if ($_SESSION['user']['role'] !== 'admin') {
  header("Location: " . BASE_URL . "/produtos.php");
  exit;
}
