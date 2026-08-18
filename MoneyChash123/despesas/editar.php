<?php
require __DIR__ . '/../includes/config.php';

$mensagem_sucesso = '';
$mensagem_erro = '';

$id_despesa = $_GET['id_despesa'] ?? $_POST['id_despesa'] ?? '';
$descricao = $valor = $data_despesa = $id_usuario = $id_categoria = $status_pagamento = '';
$despesas = $usuarios = $categorias = [];

try {
    $despesas = $pdo->query("SELECT id_despesa, descricao, valor FROM despesas ORDER BY data_despesa DESC")->fetchAll();
    $usuarios = $pdo->query("SELECT id_usuario, nome FROM usuarios ORDER BY nome")->fetchAll();
    $categorias = $pdo->query("SELECT id_categoria, nome FROM categorias WHERE tipo = 'Despesa' ORDER BY nome")->fetchAll();
} catch (PDOException $e) {
    $mensagem_erro = 'Erro ao conectar: ' . $e->getMessage();
}

if (!empty($id_despesa)) {
    try {
        $stmt = $pdo->prepare("SELECT descricao, valor, data_despesa, id_usuario, id_categoria, status_pagamento FROM despesas WHERE id_despesa = :id");
        $stmt->execute([':id' => (int) $id_despesa]);
        $despesa = $stmt->fetch();
        if ($despesa) {
            $descricao = $despesa['descricao'];
            $valor = $despesa['valor'];
            $data_despesa = $despesa['data_despesa'];
            $id_usuario = $despesa['id_usuario'];
            $id_categoria = $despesa['id_categoria'];
            $status_pagamento = $despesa['status_pagamento'];
        }
    } catch (PDOException $e) {
        $mensagem_erro = 'Erro ao carregar dados: ' . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salvar'])) {
    $id_despesa = trim($_POST['id_despesa'] ?? '');
    $id_usuario = trim($_POST['id_usuario'] ?? '');
    $id_categoria = trim($_POST['id_categoria'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $valor = trim($_POST['valor'] ?? '');
    $data_despesa = trim($_POST['data_despesa'] ?? '');
    $status_pagamento = trim($_POST['status_pagamento'] ?? 'Pendente');

    if (empty($id_despesa) || empty($id_usuario) || empty($id_categoria) || empty($valor) || empty($data_despesa)) {
        $mensagem_erro = 'Preencha todos os campos obrigatórios.';
    } else {
        try {
            $sql = "UPDATE despesas SET id_usuario = :id_usuario, id_categoria = :id_categoria, descricao = :descricao,
                    valor = :valor, data_despesa = :data_despesa, status_pagamento = :status_pagamento WHERE id_despesa = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id_usuario' => (int) $id_usuario,
                ':id_categoria' => (int) $id_categoria,
                ':descricao' => $descricao,
                ':valor' => (float) $valor,
                ':data_despesa' => $data_despesa,
                ':status_pagamento' => $status_pagamento,
                ':id' => (int) $id_despesa,
            ]);
            $mensagem_sucesso = 'Despesa atualizada com sucesso!';
        } catch (PDOException $e) {
            $mensagem_erro = 'Erro ao atualizar: ' . $e->getMessage();
        }
    }
}

$base = '../';
$page_title = 'Editar Despesa';
$active = 'despesas';
require __DIR__ . '/../includes/header.php';
?>
        <div class="form-box">
            <a href="../index.php" class="btn-voltar">Voltar para o início</a>
            <h2>Editar Despesa</h2>

            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>

            <form action="editar.php" method="GET">
                <div class="form-grupo">
                    <label for="id_despesa">Selecionar despesa</label>
                    <select id="id_despesa" name="id_despesa" onchange="this.form.submit()">
                        <option value="">Selecione</option>
                        <?php foreach ($despesas as $d): ?>
                            <option value="<?= (int) $d['id_despesa'] ?>" <?= ($d['id_despesa'] == $id_despesa ? 'selected' : '') ?>>
                                <?= htmlspecialchars($d['descricao']) ?> — <?= formatar_moeda($d['valor']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>

            <?php if (!empty($id_despesa) && !empty($descricao)): ?>
                <hr>
                <form action="editar.php" method="POST">
                    <input type="hidden" name="id_despesa" value="<?= htmlspecialchars((string) $id_despesa) ?>">
                    <div class="form-linha">
                        <div class="form-grupo">
                            <label for="id_usuario">Usuário</label>
                            <select id="id_usuario" name="id_usuario" required>
                                <?php foreach ($usuarios as $u): ?>
                                    <option value="<?= (int) $u['id_usuario'] ?>" <?= ($u['id_usuario'] == $id_usuario ? 'selected' : '') ?>><?= htmlspecialchars($u['nome']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-grupo">
                            <label for="id_categoria">Categoria</label>
                            <select id="id_categoria" name="id_categoria" required>
                                <?php foreach ($categorias as $c): ?>
                                    <option value="<?= (int) $c['id_categoria'] ?>" <?= ($c['id_categoria'] == $id_categoria ? 'selected' : '') ?>><?= htmlspecialchars($c['nome']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-grupo">
                        <label for="descricao">Descrição</label>
                        <input type="text" id="descricao" name="descricao" value="<?= htmlspecialchars($descricao) ?>">
                    </div>
                    <div class="form-linha">
                        <div class="form-grupo">
                            <label for="valor">Valor (R$)</label>
                            <input type="number" step="0.01" id="valor" name="valor" value="<?= htmlspecialchars((string) $valor) ?>" required>
                        </div>
                        <div class="form-grupo">
                            <label for="data_despesa">Data</label>
                            <input type="date" id="data_despesa" name="data_despesa" value="<?= htmlspecialchars($data_despesa) ?>" required>
                        </div>
                    </div>
                    <div class="form-grupo">
                        <label for="status_pagamento">Status</label>
                        <select id="status_pagamento" name="status_pagamento">
                            <option value="Pendente" <?= ($status_pagamento === 'Pendente' ? 'selected' : '') ?>>Pendente</option>
                            <option value="Pago" <?= ($status_pagamento === 'Pago' ? 'selected' : '') ?>>Pago</option>
                        </select>
                    </div>
                    <button type="submit" name="salvar" class="btn-enviar">Salvar Alterações</button>
                </form>
            <?php endif; ?>
        </div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
