<?php
require __DIR__ . '/../includes/config.php';
exigir_login();

$mensagem_sucesso = '';
$mensagem_erro = '';
$id_selecionado = $_GET['id_meta'] ?? '';

try {
    $metas = $pdo->query("SELECT id_meta, descricao FROM metas ORDER BY data_limite")->fetchAll();
} catch (PDOException $e) {
    $mensagem_erro = 'Erro ao carregar metas: ' . $e->getMessage();
    $metas = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_meta = trim($_POST['id_meta'] ?? '');
    if (empty($id_meta)) {
        $mensagem_erro = 'Selecione uma meta.';
    } else {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("DELETE FROM metas WHERE id_meta = :id");
            $stmt->execute([':id' => (int) $id_meta]);
            $pdo->commit();
            $mensagem_sucesso = 'Meta excluída com sucesso!';
            $metas = $pdo->query("SELECT id_meta, descricao FROM metas ORDER BY data_limite")->fetchAll();
            $id_selecionado = '';
        } catch (PDOException $e) {
            $pdo->rollBack();
            $mensagem_erro = 'Erro ao excluir: ' . $e->getMessage();
        }
    }
}

$base = '../';
$page_title = 'Excluir Meta';
$active = 'metas';
require __DIR__ . '/../includes/header.php';
?>
        <div class="form-box">
            <a href="../painel.php" class="btn-voltar">Voltar para o início</a>
            <h2>Excluir Meta</h2>

            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>

            <?php if (!empty($metas)): ?>
                <form action="excluir.php" method="POST">
                    <div class="form-grupo">
                        <label for="id_meta">Meta</label>
                        <select id="id_meta" name="id_meta" required>
                            <option value="">Selecione</option>
                            <?php foreach ($metas as $m): ?>
                                <option value="<?= (int) $m['id_meta'] ?>" <?= ($m['id_meta'] == $id_selecionado ? 'selected' : '') ?>>
                                    <?= htmlspecialchars($m['descricao']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="form-ajuda">Esta ação não pode ser desfeita.</span>
                    </div>
                    <button type="submit" class="btn-enviar btn-perigo">Excluir Meta</button>
                </form>
            <?php else: ?>
                <p class="form-vazio">Nenhuma meta cadastrada.</p>
            <?php endif; ?>
        </div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
