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
    $categorias = $pdo->query("SELECT id_categoria, nome FROM categorias WHERE tipo = 'Despesa' ORDER BY nome")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensagem_erro = "Erro de conexão: " . $e->getMessage();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = trim($_POST['id_usuario'] ?? '');
    $id_categoria = trim($_POST['id_categoria'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $valor = trim($_POST['valor'] ?? '');
    $data_despesa = trim($_POST['data_despesa'] ?? '');
    $status_pagamento = trim($_POST['status_pagamento'] ?? '');

    if (empty($id_usuario) || empty($id_categoria) || empty($valor) || empty($data_despesa)) {
        $mensagem_erro = "Preencha todos os campos obrigatórios.";
    } else {
        try {
            $pdo->beginTransaction();
            $sql = "INSERT INTO despesas (id_usuario, id_categoria, descricao, valor, data_despesa, status_pagamento) 
                    VALUES (:id_usuario, :id_categoria, :descricao, :valor, :data_despesa, :status_pagamento)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id_usuario' => (int)$id_usuario,
                ':id_categoria' => (int)$id_categoria,
                ':descricao' => $descricao,
                ':valor' => (float)$valor,
                ':data_despesa' => $data_despesa,
                ':status_pagamento' => $status_pagamento
            ]);
            $pdo->commit();
            $mensagem_sucesso = "Despesa cadastrada com sucesso!";
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
    <title>Despesas - MoneyChash</title>
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
            <h2>💳 Registrar Nova Despesa</h2>
            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso">✅ <?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro">❌ <?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>
            <form action="despesas.php" method="POST">
                <div class="form-grupo">
                    <label for="id_usuario">Usuário</label>
                    <select id="id_usuario" name="id_usuario" required>
                        <option value="">Selecione um usuário...</option>
                        <?php foreach ($usuarios as $u): ?>
                            <option value="<?= $u['id_usuario'] ?>"><?= htmlspecialchars($u['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-grupo">
                    <label for="id_categoria">Categoria</label>
                    <select id="id_categoria" name="id_categoria" required>
                        <option value="">Selecione uma categoria...</option>
                        <?php foreach ($categorias as $c): ?>
                            <option value="<?= $c['id_categoria'] ?>"><?= htmlspecialchars($c['nome']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-grupo">
                    <label for="descricao">Descrição</label>
                    <input type="text" id="descricao" name="descricao" placeholder="Descrição da despesa (opcional)">
                </div>
                <div class="form-grupo">
                    <label for="valor">Valor (R$)</label>
                    <input type="number" id="valor" name="valor" step="0.01" placeholder="0.00" required>
                </div>
                <div class="form-grupo">
                    <label for="data_despesa">Data da Despesa</label>
                    <input type="date" id="data_despesa" name="data_despesa" required>
                </div>
                <div class="form-grupo">
                    <label for="status_pagamento">Status de Pagamento</label>
                    <select id="status_pagamento" name="status_pagamento">
                        <option value="Pendente">⏳ Pendente</option>
                        <option value="Pago">✅ Pago</option>
                    </select>
                </div>
                <button type="submit" class="btn-enviar">✅ Registrar Despesa</button>
            </form>
        </div>
    </main>
</body>
</html>