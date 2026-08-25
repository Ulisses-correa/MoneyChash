<?php
require __DIR__ . '/../includes/config.php';
exigir_login();

$mensagem_sucesso = '';
$mensagem_erro = '';

try {
    $usuarios = $pdo->query("SELECT id_usuario, nome FROM usuarios ORDER BY nome")->fetchAll();
} catch (PDOException $e) {
    $mensagem_erro = 'Erro de conexão: ' . $e->getMessage();
    $usuarios = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = trim($_POST['id_usuario'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $valor_meta = trim($_POST['valor_meta'] ?? '');
    $valor_atual = trim($_POST['valor_atual'] ?? '0');
    $data_inicio = trim($_POST['data_inicio'] ?? '');
    $data_limite = trim($_POST['data_limite'] ?? '');
    $status = trim($_POST['status'] ?? 'Em andamento');

    if (empty($id_usuario) || empty($descricao) || empty($valor_meta) || empty($data_limite)) {
        $mensagem_erro = 'Preencha todos os campos obrigatórios.';
    } else {
        try {
            $sql = "INSERT INTO metas (id_usuario, descricao, valor_meta, valor_atual, data_inicio, data_limite, status)
                    VALUES (:id_usuario, :descricao, :valor_meta, :valor_atual, :data_inicio, :data_limite, :status)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id_usuario' => (int) $id_usuario,
                ':descricao' => $descricao,
                ':valor_meta' => (float) $valor_meta,
                ':valor_atual' => (float) $valor_atual,
                ':data_inicio' => $data_inicio ?: date('Y-m-d'),
                ':data_limite' => $data_limite,
                ':status' => $status,
            ]);
            $mensagem_sucesso = 'Meta cadastrada com sucesso!';
        } catch (PDOException $e) {
            $mensagem_erro = 'Erro ao salvar: ' . $e->getMessage();
        }
    }
}

$base = '../';
$page_title = 'Cadastrar Meta';
$active = 'metas';
require __DIR__ . '/../includes/header.php';
?>
        <div class="form-box">
            <a href="../painel.php" class="btn-voltar">Voltar para o início</a>
            <h2>Cadastrar Meta Financeira</h2>

            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>

            <form action="cadastrar.php" method="POST">
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
                    <label for="descricao">Descrição da meta</label>
                    <input type="text" id="descricao" name="descricao" placeholder="Ex.: Reserva de emergência" required>
                </div>
                <div class="form-linha">
                    <div class="form-grupo">
                        <label for="valor_meta">Valor alvo (R$)</label>
                        <input type="number" step="0.01" id="valor_meta" name="valor_meta" required>
                    </div>
                    <div class="form-grupo">
                        <label for="valor_atual">Valor já acumulado (R$)</label>
                        <input type="number" step="0.01" id="valor_atual" name="valor_atual" value="0.00">
                    </div>
                </div>
                <div class="form-linha">
                    <div class="form-grupo">
                        <label for="data_inicio">Data de início</label>
                        <input type="date" id="data_inicio" name="data_inicio">
                    </div>
                    <div class="form-grupo">
                        <label for="data_limite">Data limite</label>
                        <input type="date" id="data_limite" name="data_limite" required>
                    </div>
                </div>
                <div class="form-grupo">
                    <label for="status">Status</label>
                    <select id="status" name="status">
                        <option value="Em andamento">Em andamento</option>
                        <option value="Concluída">Concluída</option>
                        <option value="Cancelada">Cancelada</option>
                    </select>
                </div>
                <button type="submit" class="btn-enviar">Cadastrar</button>
            </form>
        </div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
