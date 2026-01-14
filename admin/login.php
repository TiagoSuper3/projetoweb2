<?php
if (session_status() === PHP_SESSION_NONE) session_start();
require __DIR__ . "/../config/db.php";

$error = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $email = trim($_POST['email'] ?? "");
  $pass = $_POST['password'] ?? "";

  $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? LIMIT 1");
  $stmt->execute([$email]);
  $u = $stmt->fetch();

  if ($u && $u['role'] === 'admin' && password_verify($pass, $u['password'])) {
    $_SESSION['admin_id'] = (int)$u['id'];
    header("Location: dashboard.php");
    exit;
  }
  $error = "Credenciais inválidas.";
}
?>
<!doctype html>
<html lang="pt"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<link rel="stylesheet" href="../assets/css/style.css">
<title>Admin Login</title>
</head><body>
<main class="container" style="max-width:520px">
  <h1>Admin</h1>
  <?php if ($error): ?><p class="notice"><?= htmlspecialchars($error) ?></p><?php endif; ?>
  <form method="post">
    <label>Email</label>
    <input class="input" name="email" />
    <br><br>
    <label>Password</label>
    <input class="input" type="password" name="password" />
    <br><br>
    <button class="btn" type="submit">Entrar</button>
    <a class="btn secondary" href="../index.php">Voltar</a>
  </form>
</main>
</body></html>
