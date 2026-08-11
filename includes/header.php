<?php
// Header template para todas as páginas
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $page_title ?? 'MoneyChash' ?> - Gestão Financeira</title>
    <link rel="stylesheet" href="<?= $base_path ?? '' ?>style.css">
</head>
<body>
    <header>
        <div class="header-content">
            <div>
                <h1>💰 MoneyChash</h1>
            </div>
            <div class="header-nav">
                <a href="<?= $base_path ?? '' ?>index.php" class="btn-navegacao">🏠 Início</a>
            </div>
        </div>
    </header>
