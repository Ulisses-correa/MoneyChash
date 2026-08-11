<?php
$mensagem_sucesso = "";
$mensagem_erro = "";
$host = 'localhost';
$dbname = 'gestao_financeira';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT r.id_receita, r.descricao, r.valor, r.data_receita, 
                   u.nome as usuario, c.nome as categoria
            FROM receitas r
            INNER JOIN usuarios u ON r.id_usuario = u.id_usuario
            INNER JOIN categorias c ON r.id_categoria = c.id_categoria
            ORDER BY r.data_receita DESC";
    $stmt = $pdo->query($sql);
    $receitas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensagem_erro = "Erro ao conectar: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Receitas - MoneyChash</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
        <div class="header-content">
            <div>
                <h1>💰 MoneyChash</h1>
            </div>
            <div class="header-nav">
                <a href="../index.php" class="btn-navegacao">🏠 Início</a>
            </div>
        </div>
    </header>
    <main class="container-formulario">
        <a href="../index.php" class="btn-back">← Voltar</a>
        <div class="form-box">
            <h2>💸 Receitas Cadastradas</h2>
            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso">✅ <?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro">❌ <?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>
            
            <?php if (empty($receitas)): ?>
                <div class="no-results">
                    <div class="no-results-icon">📦</div>
                    <p>Nenhuma receita cadastrada.</p>
                    <a href="cadastrar.php" class="btn btn-secondary" style="width: fit-content; margin-top: 20px;">Registrar Primeira Receita</a>
                </div>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table class="tabela">
                        <thead>
                            <tr>
                                <th>Usuário</th>
                                <th>Categoria</th>
                                <th>Descrição</th>
                                <th>Valor</th>
                                <th>Data</th>
                                <th style="text-align: center;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($receitas as $r): ?>
                                <tr>
                                    <td><?= htmlspecialchars($r['usuario']) ?></td>
                                    <td><?= htmlspecialchars($r['categoria']) ?></td>
                                    <td><?= htmlspecialchars($r['descricao']) ?></td>
                                    <td><strong style="color: var(--success);">R$ <?= number_format($r['valor'], 2, ',', '.') ?></strong></td>
                                    <td><?= date('d/m/Y', strtotime($r['data_receita'])) ?></td>
                                    <td>
                                        <div class="tabela-acoes" style="justify-content: center;">
                                            <a href="editar.php?id=<?= $r['id_receita'] ?>" class="btn-editar">✏️ Editar</a>
                                            <a href="listar.php?excluir=<?= $r['id_receita'] ?>" class="btn-excluir" onclick="return confirm('Tem certeza que deseja excluir?')">🗑️ Excluir</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>