<?php
$mensagem_sucesso = "";
$mensagem_erro = "";
$host = 'localhost';
$dbname = 'gestao_financeira';
$username = 'root';
$password = '';

$id_conta = $_GET['id_conta'] ?? $_POST['id_conta'] ?? '';
$nome_conta = '';
$saldo = '';
$tipo = '';
$contas = [];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->query("SELECT id_conta, nome_conta FROM contas ORDER BY nome_conta");
    $contas = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensagem_erro = "Erro ao conectar: " . $e->getMessage();
}

if (!empty($id_conta)) {
    try {
        $stmt = $pdo->prepare("SELECT nome_conta, saldo, tipo FROM contas WHERE id_conta = :id");
        $stmt->execute([':id' => (int)$id_conta]);
        $conta = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($conta) {
            $nome_conta = $conta['nome_conta'];
            $saldo = $conta['saldo'];
            $tipo = $conta['tipo'];
        }
    } catch (PDOException $e) {
        $mensagem_erro = "Erro ao carregar dados: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salvar'])) {
    $id_conta = trim($_POST['id_conta'] ?? '');
    $nome_conta = trim($_POST['nome_conta'] ?? '');
    $saldo = trim($_POST['saldo'] ?? '');
    $tipo = trim($_POST['tipo'] ?? '');

    if (empty($id_conta) || empty($nome_conta) || empty($saldo) || empty($tipo)) {
        $mensagem_erro = "Preencha todos os campos.";
    } else {
        try {
            $sql = "UPDATE contas SET nome_conta = :nome_conta, saldo = :saldo, tipo = :tipo WHERE id_conta = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nome_conta' => $nome_conta,
                ':saldo' => (float)$saldo,
                ':tipo' => $tipo,
                ':id' => (int)$id_conta
            ]);
            $mensagem_sucesso = "Conta atualizada com sucesso!";
        } catch (PDOException $e) {
            $mensagem_erro = "Erro ao atualizar: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Conta - MoneyChash</title>
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
            <h2>🏦 Editar Conta</h2>
            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>
            <form action="editar.php" method="GET">
                <div class="form-grupo">
                    <label for="id_conta">Selecionar conta</label>
                    <select id="id_conta" name="id_conta" onchange="this.form.submit()">
                        <option value="">Selecione</option>
                        <?php foreach ($contas as $c): ?>
                            <option value="<?= $c['id_conta'] ?>" <?= ($c['id_conta'] == $id_conta ? 'selected' : '') ?>>
                                <?= htmlspecialchars($c['nome_conta']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
            <?php if (!empty($id_conta) && !empty($nome_conta)): ?>
                <form action="editar.php" method="POST">
                    <input type="hidden" name="id_conta" value="<?= htmlspecialchars($id_conta) ?>">
                    <div class="form-grupo">
                        <label for="nome_conta">Nome da Conta</label>
                        <input type="text" id="nome_conta" name="nome_conta" value="<?= htmlspecialchars($nome_conta) ?>" required>
                    </div>
                    <div class="form-grupo">
                        <label for="saldo">Saldo</label>
                        <input type="number" step="0.01" id="saldo" name="saldo" value="<?= htmlspecialchars($saldo) ?>" required>
                    </div>
                    <div class="form-grupo">
                        <label for="tipo">Tipo</label>
                        <select id="tipo" name="tipo" required>
                            <option value="Corrente" <?= ($tipo == 'Corrente' ? 'selected' : '') ?>>Corrente</option>
                            <option value="Poupança" <?= ($tipo == 'Poupança' ? 'selected' : '') ?>>Poupança</option>
                            <option value="Carteira" <?= ($tipo == 'Carteira' ? 'selected' : '') ?>>Carteira</option>
                        </select>
                    </div>
                    <button type="submit" name="salvar" class="btn btn-primary">Salvar Alterações</button>
                </form>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>