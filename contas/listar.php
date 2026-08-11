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
    $sql = "SELECT c.id_conta, c.nome_conta, c.saldo, c.tipo, u.nome as usuario 
            FROM contas c 
            INNER JOIN usuarios u ON c.id_usuario = u.id_usuario 
            ORDER BY u.nome, c.nome_conta";
    $stmt = $pdo->query($sql);
    $contas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensagem_erro = "Erro ao conectar: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Contas - MoneyChash</title>
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
            <h2>🏦 Contas Cadastradas</h2>
            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso">✅ <?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro">❌ <?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>
            
            <?php if (empty($contas)): ?>
                <div class="no-results">
                    <div class="no-results-icon">📦</div>
                    <p>Nenhuma conta cadastrada.</p>
                    <a href="cadastrar.php" class="btn btn-primary" style="width: fit-content; margin-top: 20px;">Criar Primeira Conta</a>
                </div>
            <?php else: ?>
                <div style="overflow-x: auto;">
                    <table class="tabela">
                        <thead>
                            <tr>
                                <th>Usuário</th>
                                <th>Nome da Conta</th>
                                <th>Saldo</th>
                                <th>Tipo</th>
                                <th style="text-align: center;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($contas as $c): ?>
                                <tr>
                                    <td><?= htmlspecialchars($c['usuario']) ?></td>
                                    <td><?= htmlspecialchars($c['nome_conta']) ?></td>
                                    <td><strong>R$ <?= number_format($c['saldo'], 2, ',', '.') ?></strong></td>
                                    <td>
                                        <?php if ($c['tipo'] === 'Corrente'): ?>
                                            <span style="color: var(--primary); font-weight: 600;">🏦 Corrente</span>
                                        <?php elseif ($c['tipo'] === 'Poupança'): ?>
                                            <span style="color: var(--secondary); font-weight: 600;">💰 Poupança</span>
                                        <?php else: ?>
                                            <span style="color: var(--info); font-weight: 600;">💳 <?= htmlspecialchars($c['tipo']) ?></span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="tabela-acoes" style="justify-content: center;">
                                            <a href="editar.php?id=<?= $c['id_conta'] ?>" class="btn-editar">✏️ Editar</a>
                                            <a href="listar.php?excluir=<?= $c['id_conta'] ?>" class="btn-excluir" onclick="return confirm('Tem certeza que deseja excluir?')">🗑️ Excluir</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>