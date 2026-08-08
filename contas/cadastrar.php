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
    <title>Cadastrar Conta - FinControl</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
        <h1>FinControl - Gestão Financeira</h1>
        <a href="../index.php" class="btn-navegacao">Voltar para o Início</a>
    </header>
    <main class="container-formulario">
        <div class="form-box">
            <h2>Cadastrar Conta</h2>
            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>
            <form action="cadastrar_conta.php" method="POST">
                <div class="form-grupo">
                    <label for="id_usuario">Usuário</label>
                    <select id="id_usuario" name="id_usuario" required>
                        <option value="">Selecione</option>
                        <?php foreach ($usuarios as $u): ?>
                            <option value="<?= $u['id_usuario'] ?>"><?= htmlspecialchars($u['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-grupo">
                    <label for="nome_conta">Nome da Conta</label>
                    <input type="text" id="nome_conta" name="nome_conta" required>
                </div>
                <div class="form-grupo">
                    <label for="saldo">Saldo Inicial</label>
                    <input type="number" step="0.01" id="saldo" name="saldo" value="0.00" required>
                </div>
                <div class="form-grupo">
                    <label for="tipo">Tipo</label>
                    <select id="tipo" name="tipo" required>
                        <option value="Corrente">Corrente</option>
                        <option value="Poupança">Poupança</option>
                        <option value="Carteira">Carteira</option>
                    </select>
                </div>
                <button type="submit" class="btn-enviar">Cadastrar</button>
            </form>
        </div>
    </main>
</body>
</html> 