<?php
require __DIR__ . '/../includes/config.php';
exigir_login();

$mensagem_sucesso = '';
$mensagem_erro = '';

$id_meta = $_GET['id_meta'] ?? $_POST['id_meta'] ?? '';
$descricao = $valor_meta = $valor_atual = $data_inicio = $data_limite = $status = $id_usuario = '';
$metas = $usuarios = [];

try {
    $metas = $pdo->query("SELECT id_meta, descricao FROM metas ORDER BY data_limite")->fetchAll();
    $usuarios = $pdo->query("SELECT id_usuario, nome FROM usuarios ORDER BY nome")->fetchAll();
} catch (PDOException $e) {
    $mensagem_erro = 'Erro ao conectar: ' . $e->getMessage();
}

if (!empty($id_meta)) {
    try {
        $stmt = $pdo->prepare("SELECT id_usuario, descricao, valor_meta, valor_atual, data_inicio, data_limite, status FROM metas WHERE id_meta = :id");
        $stmt->execute([':id' => (int) $id_meta]);
        $meta = $stmt->fetch();
        if ($meta) {
            $id_usuario = $meta['id_usuario'];
            $descricao = $meta['descricao'];
            $valor_meta = $meta['valor_meta'];
            $valor_atual = $meta['valor_atual'];
            $data_inicio = $meta['data_inicio'];
            $data_limite = $meta['data_limite'];
            $status = $meta['status'];
        }
    } catch (PDOException $e) {
        $mensagem_erro = 'Erro ao carregar dados: ' . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salvar'])) {
    $id_meta = trim($_POST['id_meta'] ?? '');
    $id_usuario = trim($_POST['id_usuario'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $valor_meta = trim($_POST['valor_meta'] ?? '');
    $valor_atual = trim($_POST['valor_atual'] ?? '0');
    $data_inicio = trim($_POST['data_inicio'] ?? '');
    $data_limite = trim($_POST['data_limite'] ?? '');
    $status = trim($_POST['status'] ?? 'Em andamento');

    if (empty($id_meta) || empty($id_usuario) || empty($descricao) || empty($valor_meta) || empty($data_limite)) {
        $mensagem_erro = 'Preencha todos os campos obrigatórios.';
    } else {
        try {
            $sql = "UPDATE metas SET id_usuario = :id_usuario, descricao = :descricao, valor_meta = :valor_meta,
                    valor_atual = :valor_atual, data_inicio = :data_inicio, data_limite = :data_limite, status = :status
                    WHERE id_meta = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id_usuario' => (int) $id_usuario,
                ':descricao' => $descricao,
                ':valor_meta' => (float) $valor_meta,
                ':valor_atual' => (float) $valor_atual,
                ':data_inicio' => $data_inicio ?: null,
                ':data_limite' => $data_limite,
                ':status' => $status,
                ':id' => (int) $id_meta,
            ]);
            $mensagem_sucesso = 'Meta atualizada com sucesso!';
        } catch (PDOException $e) {
            $mensagem_erro = 'Erro ao atualizar: ' . $e->getMessage();
        }
    }
}

$base = '../';
$page_title = 'Editar Meta';
$active = 'metas';
require __DIR__ . '/../includes/header.php';
?>
        <div class="form-box">
            <a href="../painel.php" class="btn-voltar">Voltar para o início</a>
            <h2>Editar Meta</h2>

            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>

            <form action="editar.php" method="GET">
                <div class="form-grupo">
                    <label for="id_meta">Selecionar meta</label>
                    <select id="id_meta" name="id_meta" onchange="this.form.submit()">
                        <option value="">Selecione</option>
                        <?php foreach ($metas as $m): ?>
                            <option value="<?= (int) $m['id_meta'] ?>" <?= ($m['id_meta'] == $id_meta ? 'selected' : '') ?>>
                                <?= htmlspecialchars($m['descricao']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>

            <?php if (!empty($id_meta) && !empty($descricao)): ?>
                <hr>
                <form action="editar.php" method="POST">
                    <input type="hidden" name="id_meta" value="<?= htmlspecialchars((string) $id_meta) ?>">
                    <div class="form-grupo">
                        <label for="id_usuario">Usuário</label>
                        <select id="id_usuario" name="id_usuario" required>
                            <?php foreach ($usuarios as $u): ?>
                                <option value="<?= (int) $u['id_usuario'] ?>" <?= ($u['id_usuario'] == $id_usuario ? 'selected' : '') ?>><?= htmlspecialchars($u['nome']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-grupo">
                        <label for="descricao">Descrição</label>
                        <input type="text" id="descricao" name="descricao" value="<?= htmlspecialchars($descricao) ?>" required>
                    </div>
                    <div class="form-linha">
                        <div class="form-grupo">
                            <label for="valor_meta">Valor alvo (R$)</label>
                            <input type="number" step="0.01" id="valor_meta" name="valor_meta" value="<?= htmlspecialchars((string) $valor_meta) ?>" required>
                        </div>
                        <div class="form-grupo">
                            <label for="valor_atual">Valor acumulado (R$)</label>
                            <input type="number" step="0.01" id="valor_atual" name="valor_atual" value="<?= htmlspecialchars((string) $valor_atual) ?>">
                        </div>
                    </div>
                    <div class="form-linha">
                        <div class="form-grupo">
                            <label for="data_inicio">Data de início</label>
                            <input type="date" id="data_inicio" name="data_inicio" value="<?= htmlspecialchars($data_inicio ?? '') ?>">
                        </div>
                        <div class="form-grupo">
                            <label for="data_limite">Data limite</label>
                            <input type="date" id="data_limite" name="data_limite" value="<?= htmlspecialchars($data_limite) ?>" required>
                        </div>
                    </div>
                    <div class="form-grupo">
                        <label for="status">Status</label>
                        <select id="status" name="status">
                            <option value="Em andamento" <?= ($status === 'Em andamento' ? 'selected' : '') ?>>Em andamento</option>
                            <option value="Concluída" <?= ($status === 'Concluída' ? 'selected' : '') ?>>Concluída</option>
                            <option value="Cancelada" <?= ($status === 'Cancelada' ? 'selected' : '') ?>>Cancelada</option>
                        </select>
                    </div>
                    <button type="submit" name="salvar" class="btn-enviar">Salvar Alterações</button>
                </form>
            <?php endif; ?>
        </div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
