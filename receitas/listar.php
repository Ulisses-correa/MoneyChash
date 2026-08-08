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
    <title>Listar Receitas - FinControl</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
        <h1>FinControl - Gestão Financeira</h1>
        <a href="../index.php" class="btn-navegacao">Voltar para o Início</a>
    </header>
    <main class="container-formulario">
        <div class="form-box">
            <h2>Receitas Cadastradas</h2>
            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>
            <?php if (!empty($receitas)): ?>
                <table class="tabela">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuário</th>
                            <th>Categoria</th>
                            <th>Descrição</th>
                            <th>Valor</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($receitas as $r): ?>
                            <tr>
                                <td><?= $r['id_receita'] ?></td>
                                <td><?= htmlspecialchars($r['usuario']) ?></td>
                                <td><?= htmlspecialchars($r['categoria']) ?></td>
                                <td><?= htmlspecialchars($r['descricao']) ?></td>
                                <td>R$ <?= number_format($r['valor'], 2, ',', '.') ?></td>
                                <td><?= htmlspecialchars($r['data_receita']) ?></td>
                                <td>
                                    <a href="editar_receita.php?id_receita=<?= $r['id_receita'] ?>" class="btn-navegacao">Editar</a>
                                    <a href="excluir_receita.php?id_receita=<?= $r['id_receita'] ?>" class="btn-navegacao">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Nenhuma receita cadastrada.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>