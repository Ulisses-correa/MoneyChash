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
    $stmt = $pdo->query("SELECT id_conta, nome_conta FROM contas ORDER BY nome_conta");
    $contas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_conta = trim($_POST['id_conta'] ?? '');
    if (empty($id_conta)) {
        $mensagem_erro = "Selecione uma conta.";
    } else {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("DELETE FROM contas WHERE id_conta = :id");
            $stmt->execute([':id' => (int)$id_conta]);
            $pdo->commit();
            $mensagem_sucesso = "Conta excluída com sucesso!";
            $stmt = $pdo->query("SELECT id_conta, nome_conta FROM contas ORDER BY nome_conta");
            $contas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $pdo->rollBack();
            $mensagem_erro = "Erro ao excluir: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Conta - MoneyChash</title>
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
            <a href="listar.php">← Voltar</a>
        </div>
        <div class="form-box">
            <h2>🏦 Excluir Conta</h2>
            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>
            <form action="excluir.php" method="POST">
                <div class="form-grupo">
                    <label for="id_conta">Conta</label>
                    <select id="id_conta" name="id_conta" required>
                        <option value="">Selecione</option>
                        <?php foreach ($contas as $c): ?>
                            <option value="<?= $c['id_conta'] ?>"><?= htmlspecialchars($c['nome_conta']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-danger">Excluir Conta</button>
            </form>
        </div>
    </main>
</body>
</html>