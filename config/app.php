<?php
/**
 * BASE_URL deve apontar para a raiz da aplicação
 * Ex:
 *  - localhost/projetoweb2 → /projetoweb2
 *  - meusite.com           → ''
 */

$scriptName = $_SERVER['SCRIPT_NAME']; // ex: /projetoweb2/admin/produto/index.php
$scriptDir  = dirname($scriptName);    // ex: /projetoweb2/admin/produto

// se estiver em localhost com subpasta, define manualmente a raiz do projeto
$baseUrl = '';

if (strpos($scriptName, '/projetoweb2/') === 0) {
  $baseUrl = '/projetoweb2';
}

define('BASE_URL', $baseUrl);
