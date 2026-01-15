<?php
require_once __DIR__ . '/config/app.php';
if (session_status() === PHP_SESSION_NONE) session_start();
require __DIR__ . "/config/db.php";

if (!empty($_SESSION['user'])) {
  header("Location: " . BASE_URL . "/produtos.php");
  exit;
}

$error = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name  = trim($_POST['name'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $pass  = $_POST['password'] ?? '';

  if ($name === '' || $email === '' || $pass === '') {
    $error = "Preenche todos os campos.";
  } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $error = "Email inválido.";
  } elseif (strlen($pass) < 6) {
    $error = "A password deve ter pelo menos 6 caracteres.";
  } else {
    // verificar se email já existe
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? LIMIT 1");
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
      $error = "Já existe uma conta com este email.";
    } else {
      // criar conta
      $hash = password_hash($pass, PASSWORD_DEFAULT);

      $stmt = $pdo->prepare("
        INSERT INTO users (name, email, password, role)
        VALUES (?, ?, ?, 'user')
      ");
      $stmt->execute([$name, $email, $hash]);

      $success = "Conta criada com sucesso. Já podes fazer login.";
    }
  }
}
?>
<!doctype html>
<html lang="pt">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width,initial-scale=1">
  <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
  <title>Criar Conta</title>
</head>
<body>

<main class="container" style="max-width:520px">

  <h1>Criar conta</h1>

  <?php if ($error): ?>
    <p class="notice"><?= htmlspecialchars($error) ?></p>
  <?php endif; ?>

  <?php if ($success): ?>
    <p class="notice"><?= htmlspecialchars($success) ?></p>
    <a class="btn" href="<?= BASE_URL ?>/login.php">Ir para login</a>
    <br><br>
  <?php endif; ?>

  <?php if (!$success): ?>
    <form method="post">
      <label>Nome</label>
      <input class="input" name="name" required>
      <br><br>

      <label>Email</label>
      <input class="input" type="email" name="email" required>
      <br><br>

      <label>Password</label>
      <input class="input" type="password" name="password" required>
      <br><br>

      <button class="btn" type="submit">Criar conta</button>
      <a class="btn secondary" href="<?= BASE_URL ?>/login.php">Já tenho conta</a>
    </form>
  <?php endif; ?>

</main>

</body>
</html>