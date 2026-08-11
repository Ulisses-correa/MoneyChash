<?php
$host = 'localhost';
$dbname = 'gestao_financeira';
$username = 'root';
$password = '';

$mensagem_sucesso = "";
$mensagem_erro = "";

// Marcar como lida
if (isset($_GET['marcar_lida']) && is_numeric($_GET['marcar_lida'])) {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $stmt = $pdo->prepare("UPDATE notificacoes SET lida = 1 WHERE id_notificacao = :id");
        $stmt->execute([':id' => (int)$_GET['marcar_lida']]);
        $mensagem_sucesso = "Notificação marcada como lida.";
    } catch (PDOException $e) {
        $mensagem_erro = "Erro ao atualizar: " . $e->getMessage();
    }
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT n.id_notificacao, n.mensagem, n.data_envio, n.tipo, n.lida, u.nome as usuario
            FROM notificacoes n
            INNER JOIN usuarios u ON n.id_usuario = u.id_usuario
            ORDER BY n.data_envio DESC";
    $stmt = $pdo->query($sql);
    $notificacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensagem_erro = "Erro ao carregar notificações: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notificações - MoneyChash</title>
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
            <h2>🔔 Notificações</h2>
            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>
            <?php if (!empty($notificacoes)): ?>
                <table class="tabela">
                    <thead>
                        <tr>
                            <th>Usuário</th>
                            <th>Mensagem</th>
                            <th>Tipo</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($notificacoes as $n): ?>
                            <tr>
                                <td><?= htmlspecialchars($n['usuario']) ?></td>
                                <td><?= htmlspecialchars($n['mensagem']) ?></td>
                                <td><?= htmlspecialchars($n['tipo']) ?></td>
                                <td><?= htmlspecialchars($n['data_envio']) ?></td>
                                <td><?= $n['lida'] ? 'Lida' : 'Não lida' ?></td>
                                <td>
                                    <?php if (!$n['lida']): ?>
                                        <a href="notificações.php?marcar_lida=<?= $n['id_notificacao'] ?>" class="btn btn-primary">Marcar como lida</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Nenhuma notificação encontrada.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>