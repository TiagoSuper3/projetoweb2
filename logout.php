<?php
require __DIR__ . "/config/app.php";
session_start();
session_destroy();

header("Location: " . BASE_URL . "/produtos.php");
exit;
