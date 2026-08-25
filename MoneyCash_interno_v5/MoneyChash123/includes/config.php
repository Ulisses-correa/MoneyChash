<?php
/**
 * Configuração central de conexão com o banco de dados.
 * Todas as páginas do sistema incluem este arquivo em vez de
 * repetir as credenciais — mais fácil de manter e mais seguro.
 */

declare(strict_types=1);

require_once __DIR__ . '/auth.php';

$host     = 'localhost';
$dbname   = 'gestao_financeira';
$dbuser   = 'root';
$dbpass   = '';

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbname};charset=utf8mb4",
        $dbuser,
        $dbpass
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    http_response_code(500);
    die('Não foi possível conectar ao banco de dados. Verifique as configurações em includes/config.php.');
}

/**
 * Formata um valor numérico como moeda brasileira.
 */
function formatar_moeda(float|int|string|null $valor): string
{
    return 'R$ ' . number_format((float) $valor, 2, ',', '.');
}

/**
 * Formata uma data (YYYY-MM-DD) para o padrão brasileiro.
 */
function formatar_data(?string $data): string
{
    if (empty($data)) {
        return '—';
    }
    $timestamp = strtotime($data);
    return $timestamp ? date('d/m/Y', $timestamp) : htmlspecialchars($data);
}
