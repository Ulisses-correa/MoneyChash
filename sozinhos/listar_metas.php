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
    $sql = "SELECT m.id_meta, m.descricao, m.valor_meta, m.valor_atual, m.data_inicio, m.data_limite, m.status,
                   u.nome as usuario
            FROM metas m
            INNER JOIN usuarios u ON m.id_usuario = u.id_usuario
            ORDER BY m.status, m.data_limite";
    $stmt = $pdo->query($sql);
    $metas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensagem_erro = "Erro ao conectar: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Metas - FinControl</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
        <h1>FinControl - Gestão Financeira</h1>
        <a href="../index.php" class="btn-navegacao">Voltar para o Início</a>
    </header>
    <main class="container-formulario">
        <div class="form-box">
            <h2>Metas Cadastradas</h2>
            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>
            <?php if (!empty($metas)): ?>
                <table class="tabela">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuário</th>
                            <th>Descrição</th>
                            <th>Valor Alvo</th>
                            <th>Valor Atual</th>
                            <th>Início</th>
                            <th>Limite</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($metas as $m): ?>
                            <tr>
                                <td><?= $m['id_meta'] ?></td>
                                <td><?= htmlspecialchars($m['usuario']) ?></td>
                                <td><?= htmlspecialchars($m['descricao']) ?></td>
                                <td>R$ <?= number_format($m['valor_meta'], 2, ',', '.') ?></td>
                                <td>R$ <?= number_format($m['valor_atual'], 2, ',', '.') ?></td>
                                <td><?= htmlspecialchars($m['data_inicio']) ?></td>
                                <td><?= htmlspecialchars($m['data_limite']) ?></td>
                                <td><?= htmlspecialchars($m['status']) ?></td>
                                <td>
                                    <a href="editar_meta.php?id_meta=<?= $m['id_meta'] ?>" class="btn-navegacao">Editar</a>
                                    <a href="excluir_meta.php?id_meta=<?= $m['id_meta'] ?>" class="btn-navegacao">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Nenhuma meta cadastrada.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>