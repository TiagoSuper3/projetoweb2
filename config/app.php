<?php
// Detectar ambiente
$isLocal = in_array($_SERVER['HTTP_HOST'], [
  'localhost',
  '127.0.0.1'
]);

if ($isLocal) {
  /* =========================
     LOCALHOST
  ========================= */
  define('BASE_URL', '/projetoweb2');

  define('DB_HOST', '127.0.0.1');
  define('DB_NAME', 'loja_db');
  define('DB_USER', 'root');
  define('DB_PASS', '');

} else {
  /* =========================
     PRODUÇÃO (HOSTINGER)
  ========================= */
  define('BASE_URL', '');

  define('DB_HOST', 'localhost');
  define('DB_NAME', 'u506280443_tiabraDB');
  define('DB_USER', 'u506280443_tiabradbUser');
  define('DB_PASS', 'GoVDXry0kH;');
}