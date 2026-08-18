<?php
require __DIR__ . '/../includes/config.php';

$mensagem_sucesso = '';
$mensagem_erro = '';

try {
    $usuarios = $pdo->query("SELECT id_usuario, nome FROM usuarios ORDER BY nome")->fetchAll();
    $categorias = $pdo->query("SELECT id_categoria, nome FROM categorias WHERE tipo = 'Despesa' ORDER BY nome")->fetchAll();
} catch (PDOException $e) {
    $mensagem_erro = 'Erro de conexão: ' . $e->getMessage();
    $usuarios = $categorias = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = trim($_POST['id_usuario'] ?? '');
    $id_categoria = trim($_POST['id_categoria'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $valor = trim($_POST['valor'] ?? '');
    $data_despesa = trim($_POST['data_despesa'] ?? '');
    $status_pagamento = trim($_POST['status_pagamento'] ?? 'Pendente');

    if (empty($id_usuario) || empty($id_categoria) || empty($valor) || empty($data_despesa)) {
        $mensagem_erro = 'Preencha todos os campos obrigatórios.';
    } else {
        try {
            $sql = "INSERT INTO despesas (id_usuario, id_categoria, descricao, valor, data_despesa, status_pagamento)
                    VALUES (:id_usuario, :id_categoria, :descricao, :valor, :data_despesa, :status_pagamento)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id_usuario' => (int) $id_usuario,
                ':id_categoria' => (int) $id_categoria,
                ':descricao' => $descricao,
                ':valor' => (float) $valor,
                ':data_despesa' => $data_despesa,
                ':status_pagamento' => $status_pagamento,
            ]);
            $mensagem_sucesso = 'Despesa cadastrada com sucesso!';
        } catch (PDOException $e) {
            $mensagem_erro = 'Erro ao salvar: ' . $e->getMessage();
        }
    }
}

$base = '../';
$page_title = 'Cadastrar Despesa';
$active = 'despesas';
require __DIR__ . '/../includes/header.php';
?>
        <div class="form-box">
            <a href="../index.php" class="btn-voltar">Voltar para o início</a>
            <h2>Cadastrar Despesa</h2>

            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>

            <form action="cadastrar.php" method="POST">
                <div class="form-linha">
                    <div class="form-grupo">
                        <label for="id_usuario">Usuário</label>
                        <select id="id_usuario" name="id_usuario" required>
                            <option value="">Selecione</option>
                            <?php foreach ($usuarios as $u): ?>
                                <option value="<?= (int) $u['id_usuario'] ?>"><?= htmlspecialchars($u['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-grupo">
                        <label for="id_categoria">Categoria (Despesa)</label>
                        <select id="id_categoria" name="id_categoria" required>
                            <option value="">Selecione</option>
                            <?php foreach ($categorias as $c): ?>
                                <option value="<?= (int) $c['id_categoria'] ?>"><?= htmlspecialchars($c['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-grupo">
                    <label for="descricao">Descrição</label>
                    <input type="text" id="descricao" name="descricao" placeholder="Ex.: Conta de energia">
                </div>
                <div class="form-linha">
                    <div class="form-grupo">
                        <label for="valor">Valor (R$)</label>
                        <input type="number" step="0.01" id="valor" name="valor" required>
                    </div>
                    <div class="form-grupo">
                        <label for="data_despesa">Data</label>
                        <input type="date" id="data_despesa" name="data_despesa" required>
                    </div>
                </div>
                <div class="form-grupo">
                    <label for="status_pagamento">Status</label>
                    <select id="status_pagamento" name="status_pagamento">
                        <option value="Pendente">Pendente</option>
                        <option value="Pago">Pago</option>
                    </select>
                </div>
                <button type="submit" class="btn-enviar">Cadastrar</button>
            </form>
        </div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
