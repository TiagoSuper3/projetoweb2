<?php
require __DIR__ . "/config/app.php";
require __DIR__ . "/includes/csrf.php";

csrf_validate();

session_destroy();

header("Location: " . BASE_URL . "/produtos.php");
exit;
