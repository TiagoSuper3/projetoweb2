<?php

require __DIR__ . "/../includes/_auth.php";
require __DIR__ . "/../config/db.php";
require_once __DIR__ . '/../config/app.php';

$userId = $_SESSION['user']['id'];

/* =========================
   UPDATE PERFIL (POST)
========================= */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $name = trim($_POST['name'] ?? '');
  $password = $_POST['password'] ?? '';

  if ($name !== '') {
    if ($password !== '') {
      $hash = password_hash($password, PASSWORD_DEFAULT);
      $stmt = $pdo->prepare("UPDATE users SET name=?, password=? WHERE id=?");
      $stmt->execute([$name, $hash, $userId]);
    } else {
      $stmt = $pdo->prepare("UPDATE users SET name=? WHERE id=?");
      $stmt->execute([$name, $userId]);
    }

    /* atualizar sessão */
    $_SESSION['user']['name'] = $name;

    /* 🔁 REDIRECT PARA NOVO REQUEST */
    header("Location: " . BASE_URL . "/account/profile.php?updated=1");
    exit;
  }

  /* nome vazio */
  header("Location: " . BASE_URL . "/account/profile.php?error=1");
  exit;
}

/* =========================
   GET (RENDER)
========================= */
require __DIR__ . "/../includes/header.php";

/* obter dados atuais */
$stmt = $pdo->prepare("SELECT name, email FROM users WHERE id = ?");
$stmt->execute([$userId]);
$user = $stmt->fetch();

?>


<h1>O meu perfil</h1>

<div class="card" style="max-width:520px">

  <?php if (isset($_GET['updated'])): ?>
    <p class="notice">Perfil atualizado com sucesso.</p>
  <?php endif; ?>

  <?php if (isset($_GET['error'])): ?>
    <p class="notice">O nome é obrigatório.</p>
  <?php endif; ?>

    <form method="post">

    <label>Nome</label>
    <input
        class="input"
        name="name"
        value="<?= htmlspecialchars($user['name']) ?>"
        required
    >
    <br><br>

    <label>Email</label>
    <input
        class="input"
        value="<?= htmlspecialchars($user['email']) ?>"
        disabled
    >
    <br><br>

    <label>Nova password <small>(opcional)</small></label>
    <input class="input" type="password" name="password">
    <br><br>

    <button type="submit" class="btn">
        Guardar alterações
    </button>

    </form>
</div>

<?php require __DIR__ . "/../includes/footer.php"; ?>
