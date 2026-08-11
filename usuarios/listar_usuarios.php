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
    $sql = "SELECT id_usuario, nome, email, telefone, data_nascimento FROM usuarios ORDER BY nome";
    $stmt = $pdo->query($sql);
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensagem_erro = "Erro ao conectar: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Usuários - MoneyChash</title>
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
        <div class="back-button">
            <a href="../index.php">← Voltar</a>
        </div>
        <div class="form-box">
            <h2>👥 Usuários Cadastrados</h2>
            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>
            <?php if (!empty($usuarios)): ?>
                <table class="tabela">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Telefone</th>
                            <th>Data Nasc.</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $u): ?>
                            <tr>
                                <td><?= $u['id_usuario'] ?></td>
                                <td><?= htmlspecialchars($u['nome']) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td><?= htmlspecialchars($u['telefone']) ?></td>
                                <td><?= htmlspecialchars($u['data_nascimento']) ?></td>
                                <td>
                                    <a href="../index.php" class="btn btn-primary">Editar</a>
                                    <a href="excluir.php?id_usuario=<?= $u['id_usuario'] ?>" class="btn btn-danger">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Nenhum usuário cadastrado.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>