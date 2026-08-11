<?php
/**
 * MoneyChash - Gestão Financeira Pessoal
 * Configuração Global
 */

// Configurações de Banco de Dados
define('DB_HOST', 'localhost');
define('DB_NAME', 'gestao_financeira');
define('DB_USER', 'root');
define('DB_PASS', '');

// Configurações da Aplicação
define('APP_NAME', 'MoneyChash');
define('APP_VERSION', '2.0.0');
define('APP_TITLE', 'MoneyChash - Gestão Financeira Pessoal');

// Conexão com Banco de Dados
try {
    $pdo = new PDO(
        "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4",
        DB_USER,
        DB_PASS
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erro de Conexão: " . $e->getMessage());
}

// Funções Utilitárias
function formatarMoeda($valor) {
    return 'R$ ' . number_format($valor, 2, ',', '.');
}

function formatarData($data) {
    return date('d/m/Y', strtotime($data));
}

function safe($texto) {
    return htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
}
?>
