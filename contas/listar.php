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
    <title>Listar Contas - FinControl</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
        <h1>FinControl - Gestão Financeira</h1>
        <a href="../index.php" class="btn-navegacao">Voltar para o Início</a>
    </header>
    <main class="container-formulario">
        <div class="form-box">
            <h2>Contas Cadastradas</h2>
            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>
            <?php if (!empty($contas)): ?>
                <table class="tabela">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuário</th>
                            <th>Nome</th>
                            <th>Saldo</th>
                            <th>Tipo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contas as $c): ?>
                            <tr>
                                <td><?= $c['id_conta'] ?></td>
                                <td><?= htmlspecialchars($c['usuario']) ?></td>
                                <td><?= htmlspecialchars($c['nome_conta']) ?></td>
                                <td>R$ <?= number_format($c['saldo'], 2, ',', '.') ?></td>
                                <td><?= htmlspecialchars($c['tipo']) ?></td>
                                <td>
                                    <a href="editar_conta.php?id_conta=<?= $c['id_conta'] ?>" class="btn-navegacao">Editar</a>
                                    <a href="excluir_conta.php?id_conta=<?= $c['id_conta'] ?>" class="btn-navegacao">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Nenhuma conta cadastrada.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>