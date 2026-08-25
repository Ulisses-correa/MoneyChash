<?php
/**
 * Cabeçalho, sidebar e navegação compartilhados.
 * Antes de incluir este arquivo, defina:
 *   $base        caminho relativo até a raiz ('' na home, '../' nas subpastas)
 *   $page_title  título da página atual
 *   $active      chave do item de menu ativo (ver $nav_items abaixo)
 *   $main_class  (opcional) classe do <main> — padrão 'container-formulario'
 */

require_once __DIR__ . '/icons.php';

$base       = $base ?? '';
$page_title = $page_title ?? 'MoneyCash';
$active     = $active ?? '';
$main_class = $main_class ?? 'container-formulario';

$nav_items = [
    'inicio'         => ['label' => 'Início',        'icon' => 'home',      'href' => $base . 'painel.php'],
    'contas'         => ['label' => 'Contas',         'icon' => 'bank',      'href' => $base . 'contas/listar.php'],
    'receitas'       => ['label' => 'Receitas',       'icon' => 'trend-up',  'href' => $base . 'receitas/listar.php'],
    'despesas'       => ['label' => 'Despesas',       'icon' => 'receipt',   'href' => $base . 'despesas/listar.php'],
    'movimentacoes'  => ['label' => 'Extrato',        'icon' => 'bar-chart', 'href' => $base . 'movimentacoes/extrato.php'],
    'categorias'     => ['label' => 'Categorias',     'icon' => 'tag',       'href' => $base . 'categorias/listar.php'],
    'metas'          => ['label' => 'Metas',          'icon' => 'target',    'href' => $base . 'metas/listar.php'],
    'relatorios'     => ['label' => 'Relatórios',     'icon' => 'pie-chart', 'href' => $base . 'relatorios/relatorio_financeiro.php'],
    'notificacoes'   => ['label' => 'Notificações',   'icon' => 'bell',      'href' => $base . 'notificacoes/notificacoes.php'],
    'usuarios'       => ['label' => 'Usuários',       'icon' => 'users',     'href' => $base . 'usuarios/listar.php'],
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?> · MoneyCash</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= htmlspecialchars($base) ?>style.css?v=20260821">
</head>
<body>
    <div class="app-shell">
        <aside class="sidebar">
            <a href="<?= htmlspecialchars($base) ?>painel.php" class="brand">
                <span class="brand-mark"><img src="<?= htmlspecialchars($base) ?>assets/logo.svg" alt="MoneyCash"></span>
                <span class="brand-copy"><span class="brand-name">MoneyCash</span><small>Gestão financeira</small></span>
            </a>

            <nav class="sidebar-nav">
                <?php foreach ($nav_items as $key => $item): ?>
                    <a href="<?= htmlspecialchars($item['href']) ?>"<?= $active === $key ? ' class="active"' : '' ?>>
                        <span class="nav-icon"><?= moneycash_icon($item['icon']) ?></span>
                        <?= htmlspecialchars($item['label']) ?>
                    </a>
                <?php endforeach; ?>
            </nav>

            <div class="sidebar-footer">MoneyCash &copy; <?= date('Y') ?></div>
        </aside>

        <div class="app-content">
            <header class="topbar">
                <div>
                    <p class="eyebrow">Gestão financeira</p>
                    <h1><?= htmlspecialchars($page_title) ?></h1>
                </div>
                <div class="topbar-user">
                    <div class="topbar-avatar">
                        <?= htmlspecialchars(strtoupper(substr(usuario_atual()['nome'] ?? 'U', 0, 1))) ?>
                    </div>
                    <div class="topbar-user-info">
                        <strong><?= htmlspecialchars(usuario_atual()['nome'] ?? 'Usuário') ?></strong>
                        <span><?= htmlspecialchars(usuario_atual()['email'] ?? '') ?></span>
                    </div>
                    <a class="btn-sair" href="<?= htmlspecialchars($base) ?>logout.php" title="Sair">Sair</a>
                </div>
            </header>
            <main class="<?= htmlspecialchars($main_class) ?>">
