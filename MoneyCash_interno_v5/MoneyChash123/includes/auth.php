<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function usuario_logado(): bool
{
    return !empty($_SESSION['usuario_id']);
}

function exigir_login(): void
{
    if (!usuario_logado()) {
        $destino = $_SERVER['REQUEST_URI'] ?? 'painel.php';
        $script = $_SERVER['SCRIPT_NAME'] ?? '';
        $segmentos = explode('/', trim($script, '/'));
        $base = !empty($segmentos[0]) ? '/' . $segmentos[0] . '/' : '/';
        header('Location: ' . $base . 'login.php?redirect=' . urlencode($destino));
        exit;
    }
}

function usuario_atual(): ?array
{
    return $_SESSION['usuario'] ?? null;
}

function iniciar_sessao_usuario(array $usuario): void
{
    session_regenerate_id(true);

    $_SESSION['usuario_id'] = (int) $usuario['id_usuario'];
    $_SESSION['usuario'] = [
        'id_usuario' => (int) $usuario['id_usuario'],
        'nome' => $usuario['nome'],
        'email' => $usuario['email'],
    ];
}

function encerrar_sessao(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();
}
