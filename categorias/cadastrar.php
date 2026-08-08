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
} catch (PDOException $e) {
    $mensagem_erro = "Erro de conexão: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $tipo = trim($_POST['tipo'] ?? '');

    if (empty($nome) || empty($tipo)) {
        $mensagem_erro = "Preencha todos os campos.";
    } else {
        try {
            $sql = "INSERT INTO categorias (nome, tipo) VALUES (:nome, :tipo)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':nome' => $nome, ':tipo' => $tipo]);
            $mensagem_sucesso = "Categoria cadastrada com sucesso!";
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
    <title>Cadastrar Categoria - FinControl</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
        <h1>FinControl - Gestão Financeira</h1>
        <a href="../index.php" class="btn-navegacao">Voltar para o Início</a>
    </header>
    <main class="container-formulario">
        <div class="form-box">
            <h2>Cadastrar Categoria</h2>
            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>
            <form action="cadastrar_categoria.php" method="POST">
                <div class="form-grupo">
                    <label for="nome">Nome da Categoria</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                <div class="form-grupo">
                    <label for="tipo">Tipo</label>
                    <select id="tipo" name="tipo" required>
                        <option value="Receita">Receita</option>
                        <option value="Despesa">Despesa</option>
                    </select>
                </div>
                <button type="submit" class="btn-enviar">Cadastrar</button>
            </form>
        </div>
    </main>
</body>
</html>