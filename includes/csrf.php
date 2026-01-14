<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

/* gera token se não existir */
if (empty($_SESSION['csrf_token'])) {
  $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

/* função para imprimir input hidden */
function csrf_field() {
  echo '<input type="hidden" name="csrf_token" value="' .
       htmlspecialchars($_SESSION['csrf_token']) .
       '">';
}

/* função para validar token */
function csrf_validate() {
  if (
    empty($_POST['csrf_token']) ||
    empty($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])
  ) {
    http_response_code(403);
    die("Pedido inválido (CSRF).");
  }
}