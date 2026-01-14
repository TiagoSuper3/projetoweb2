<?php

require_once __DIR__ . '/config/app.php';
if (session_status() === PHP_SESSION_NONE) session_start();
require __DIR__ . "/config/db.php";

if (!empty($_SESSION['user'])) {
  header("Location: /produtos.php");
  exit;
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? "");
  $pass  = $_POST['password'] ?? "";

  $stmt = $pdo->prepare("SELECT id, name, email, role, password FROM users WHERE email = ? LIMIT 1");
  $stmt->execute([$email]);
  $u = $stmt->fetch();

  if ($u && password_verify($pass, $u['password'])) {
    $_SESSION['user'] = [
      'id'    => (int)$u['id'],
      'name'  => $u['name'] ?: $u['email'],
      'email' => $u['email'],
      'role'  => $u['role'] // 'user' ou 'admin'
    ];

    if ($u['role'] === 'admin') {
      header("Location:" . BASE_URL . "/admin/dashboard.php");
    } else {
      header("Location:" . BASE_URL . "/produtos.php");
    }
    exit;
  }

  $error = "Credenciais inválidas.";
}
?>
<!doctype html>
<html lang="pt">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
  <title>Login</title>
</head>
<body>
<main class="container" style="max-width:520px">
  <h1>Conta</h1>
  <?php if ($error): ?><p class="notice"><?= htmlspecialchars($error) ?></p><?php endif; ?>

  <form method="post">
    <label>Email</label>
    <input class="input" name="email" required>
    <br><br>

    <label>Password</label>
    <input class="input" type="password" name="password" required>
    <br><br>

    <button class="btn" type="submit">Entrar</button>
    <a class="btn secondary" href="<?= BASE_URL ?>/produtos.php">Voltar</a>
  </form>
</main>
</body>
</html>
