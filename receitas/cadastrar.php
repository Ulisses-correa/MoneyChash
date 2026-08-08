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
    $usuarios = $pdo->query("SELECT id_usuario, nome FROM usuarios ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
    $categorias = $pdo->query("SELECT id_categoria, nome FROM categorias WHERE tipo = 'Receita' ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensagem_erro = "Erro de conexão: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = trim($_POST['id_usuario'] ?? '');
    $id_categoria = trim($_POST['id_categoria'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $valor = trim($_POST['valor'] ?? '');
    $data_receita = trim($_POST['data_receita'] ?? '');

    if (empty($id_usuario) || empty($id_categoria) || empty($valor) || empty($data_receita)) {
        $mensagem_erro = "Preencha todos os campos obrigatórios.";
    } else {
        try {
            $pdo->beginTransaction();
            $sql = "INSERT INTO receitas (id_usuario, id_categoria, descricao, valor, data_receita) 
                    VALUES (:id_usuario, :id_categoria, :descricao, :valor, :data_receita)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id_usuario' => (int)$id_usuario,
                ':id_categoria' => (int)$id_categoria,
                ':descricao' => $descricao,
                ':valor' => (float)$valor,
                ':data_receita' => $data_receita
            ]);
            // Atualizar saldo da conta? Como não há conta vinculada diretamente, ignoramos.
            // No modelo, a receita é independente, mas poderia atualizar uma conta se tivesse relação.
            $pdo->commit();
            $mensagem_sucesso = "Receita cadastrada com sucesso!";
        } catch (PDOException $e) {
            $pdo->rollBack();
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
    <title>Cadastrar Receita - FinControl</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
        <h1>FinControl - Gestão Financeira</h1>
        <a href="../index.php" class="btn-navegacao">Voltar para o Início</a>
    </header>
    <main class="container-formulario">
        <div class="form-box">
            <h2>Cadastrar Receita</h2>
            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>
            <form action="cadastrar_receita.php" method="POST">
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
                    <label for="id_categoria">Categoria (Receita)</label>
                    <select id="id_categoria" name="id_categoria" required>
                        <option value="">Selecione</option>
                        <?php foreach ($categorias as $c): ?>
                            <option value="<?= $c['id_categoria'] ?>"><?= htmlspecialchars($c['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-grupo">
                    <label for="descricao">Descrição</label>
                    <input type="text" id="descricao" name="descricao">
                </div>
                <div class="form-grupo">
                    <label for="valor">Valor (R$)</label>
                    <input type="number" step="0.01" id="valor" name="valor" required>
                </div>
                <div class="form-grupo">
                    <label for="data_receita">Data</label>
                    <input type="date" id="data_receita" name="data_receita" required>
                </div>
                <button type="submit" class="btn-enviar">Cadastrar</button>
            </form>
        </div>
    </main>
</body>
</html>