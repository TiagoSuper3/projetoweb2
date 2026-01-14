<?php
require_once __DIR__ . "/app.php";

try {
  $pdo = new PDO(
    "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
    DB_USER,
    DB_PASS,
    [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
  );
} catch (PDOException $e) {
  if ($isLocal ?? false) {
    die("Erro DB: " . $e->getMessage());
  } else {
    die("Erro interno.");
  }
}