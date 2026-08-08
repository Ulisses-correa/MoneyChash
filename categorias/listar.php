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
    $sql = "SELECT id_categoria, nome, tipo FROM categorias ORDER BY tipo, nome";
    $stmt = $pdo->query($sql);
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensagem_erro = "Erro ao conectar: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Categorias - FinControl</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
        <h1>FinControl - Gestão Financeira</h1>
        <a href="../index.php" class="btn-navegacao">Voltar para o Início</a>
    </header>
    <main class="container-formulario">
        <div class="form-box">
            <h2>Categorias Cadastradas</h2>
            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>
            <?php if (!empty($categorias)): ?>
                <table class="tabela">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Tipo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categorias as $c): ?>
                            <tr>
                                <td><?= $c['id_categoria'] ?></td>
                                <td><?= htmlspecialchars($c['nome']) ?></td>
                                <td><?= htmlspecialchars($c['tipo']) ?></td>
                                <td>
                                    <a href="editar_categoria.php?id_categoria=<?= $c['id_categoria'] ?>" class="btn-navegacao">Editar</a>
                                    <a href="excluir_categoria.php?id_categoria=<?= $c['id_categoria'] ?>" class="btn-navegacao">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Nenhuma categoria cadastrada.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>