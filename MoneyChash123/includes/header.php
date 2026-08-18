<?php
/**
 * Cabeçalho e navegação compartilhados.
 * Antes de incluir este arquivo, defina:
 *   $base        caminho relativo até a raiz ('' na home, '../' nas subpastas)
 *   $page_title  título da página atual
 *   $active      chave do item de menu ativo (ver $nav_items abaixo)
 *   $main_class  (opcional) classe do <main> — padrão 'container-formulario'
 */

$base       = $base ?? '';
$page_title = $page_title ?? 'MoneyCash';
$active     = $active ?? '';
$main_class = $main_class ?? 'container-formulario';

$nav_items = [
    'inicio'         => ['label' => 'Início',        'href' => $base . 'index.php'],
    'usuarios'       => ['label' => 'Usuários',       'href' => $base . 'usuarios/listar.php'],
    'categorias'     => ['label' => 'Categorias',     'href' => $base . 'categorias/listar.php'],
    'contas'         => ['label' => 'Contas',         'href' => $base . 'contas/listar.php'],
    'receitas'       => ['label' => 'Receitas',       'href' => $base . 'receitas/listar.php'],
    'despesas'       => ['label' => 'Despesas',       'href' => $base . 'despesas/listar.php'],
    'movimentacoes'  => ['label' => 'Extrato',        'href' => $base . 'movimentacoes/extrato.php'],
    'metas'          => ['label' => 'Metas',          'href' => $base . 'metas/listar.php'],
    'notificacoes'   => ['label' => 'Notificações',   'href' => $base . 'notificacoes/notificacoes.php'],
    'relatorios'     => ['label' => 'Relatórios',     'href' => $base . 'relatorios/relatorio_financeiro.php'],
];
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($page_title) ?> · MoneyCash</title>
    <link rel="stylesheet" href="<?= htmlspecialchars($base) ?>style.css">
</head>
<body>
    <header class="site-header">
        <div class="site-header-top">
            <a href="<?= htmlspecialchars($base) ?>index.php" class="brand">
                <span class="brand-mark">$</span> MoneyCash
            </a>
            <h1><?= htmlspecialchars($page_title) ?></h1>
        </div>
        <nav class="navbar">
            <?php foreach ($nav_items as $key => $item): ?>
                <a href="<?= htmlspecialchars($item['href']) ?>"<?= $active === $key ? ' class="active"' : '' ?>><?= htmlspecialchars($item['label']) ?></a>
            <?php endforeach; ?>
        </nav>
    </header>
    <main class="<?= htmlspecialchars($main_class) ?>">
