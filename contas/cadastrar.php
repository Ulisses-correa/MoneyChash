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
    $stmt = $pdo->query("SELECT id_usuario, nome FROM usuarios ORDER BY nome");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensagem_erro = "Erro de conexão: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = trim($_POST['id_usuario'] ?? '');
    $nome_conta = trim($_POST['nome_conta'] ?? '');
    $saldo = trim($_POST['saldo'] ?? '');
    $tipo = trim($_POST['tipo'] ?? '');

    if (empty($id_usuario) || empty($nome_conta) || empty($saldo) || empty($tipo)) {
        $mensagem_erro = "Preencha todos os campos.";
    } else {
        try {
            $sql = "INSERT INTO contas (id_usuario, nome_conta, saldo, tipo) VALUES (:id_usuario, :nome_conta, :saldo, :tipo)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id_usuario' => (int)$id_usuario,
                ':nome_conta' => $nome_conta,
                ':saldo' => (float)$saldo,
                ':tipo' => $tipo
            ]);
            $mensagem_sucesso = "Conta cadastrada com sucesso!";
        } catch (PDOException $e) {
            $mensagem_erro = "Erro ao salvar: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Conta - MoneyChash</title>
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
            <h2>🏦 Criar Nova Conta</h2>
            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso">✅ <?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro">❌ <?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>
            <form action="cadastrar.php" method="POST">
                <div class="form-grupo">
                    <label for="nome_conta">Nome da Conta</label>
                    <input type="text" id="nome_conta" name="nome_conta" placeholder="Ex: Conta do Itaú, Cartão Crédito..." required>
                </div>
                <div class="form-grupo">
                    <label for="tipo">Tipo de Conta</label>
                    <select id="tipo" name="tipo" required>
                        <option value="">Selecione um tipo...</option>
                        <option value="Corrente">🏦 Conta Corrente</option>
                        <option value="Poupança">💰 Conta Poupança</option>
                        <option value="Cartão Crédito">💳 Cartão de Crédito</option>
                        <option value="Carteira">💼 Carteira</option>
                    </select>
                </div>
                <div class="form-grupo">
                    <label for="saldo">Saldo Inicial</label>
                    <input type="number" id="saldo" name="saldo" step="0.01" placeholder="0.00" value="0.00" required>
                </div>
                <button type="submit" class="btn-enviar">✅ Criar Conta</button>
            </form>
        </div>
    </main>
</body>
</html> 