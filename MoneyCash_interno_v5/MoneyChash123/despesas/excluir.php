<?php
require __DIR__ . '/../includes/config.php';
exigir_login();

$mensagem_sucesso = '';
$mensagem_erro = '';
$id_selecionado = $_GET['id_despesa'] ?? '';

try {
    $despesas = $pdo->query("SELECT id_despesa, descricao, valor FROM despesas ORDER BY data_despesa DESC")->fetchAll();
} catch (PDOException $e) {
    $mensagem_erro = 'Erro ao carregar despesas: ' . $e->getMessage();
    $despesas = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_despesa = trim($_POST['id_despesa'] ?? '');
    if (empty($id_despesa)) {
        $mensagem_erro = 'Selecione uma despesa.';
    } else {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("DELETE FROM despesas WHERE id_despesa = :id");
            $stmt->execute([':id' => (int) $id_despesa]);
            $pdo->commit();
            $mensagem_sucesso = 'Despesa excluída com sucesso!';
            $despesas = $pdo->query("SELECT id_despesa, descricao, valor FROM despesas ORDER BY data_despesa DESC")->fetchAll();
            $id_selecionado = '';
        } catch (PDOException $e) {
            $pdo->rollBack();
            $mensagem_erro = 'Erro ao excluir: ' . $e->getMessage();
        }
    }
}

$base = '../';
$page_title = 'Excluir Despesa';
$active = 'despesas';
require __DIR__ . '/../includes/header.php';
?>
        <div class="form-box">
            <a href="../painel.php" class="btn-voltar">Voltar para o início</a>
            <h2>Excluir Despesa</h2>

            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>

            <?php if (!empty($despesas)): ?>
                <form action="excluir.php" method="POST">
                    <div class="form-grupo">
                        <label for="id_despesa">Despesa</label>
                        <select id="id_despesa" name="id_despesa" required>
                            <option value="">Selecione</option>
                            <?php foreach ($despesas as $d): ?>
                                <option value="<?= (int) $d['id_despesa'] ?>" <?= ($d['id_despesa'] == $id_selecionado ? 'selected' : '') ?>>
                                    <?= htmlspecialchars($d['descricao']) ?> — <?= formatar_moeda($d['valor']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="form-ajuda">Esta ação não pode ser desfeita.</span>
                    </div>
                    <button type="submit" class="btn-enviar btn-perigo">Excluir Despesa</button>
                </form>
            <?php else: ?>
                <p class="form-vazio">Nenhuma despesa cadastrada.</p>
            <?php endif; ?>
        </div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
