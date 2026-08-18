<?php
require __DIR__ . '/../includes/config.php';

$mensagem_sucesso = '';
$mensagem_erro = '';
$id_selecionado = $_GET['id_receita'] ?? '';

try {
    $receitas = $pdo->query("SELECT id_receita, descricao, valor FROM receitas ORDER BY data_receita DESC")->fetchAll();
} catch (PDOException $e) {
    $mensagem_erro = 'Erro ao carregar receitas: ' . $e->getMessage();
    $receitas = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_receita = trim($_POST['id_receita'] ?? '');
    if (empty($id_receita)) {
        $mensagem_erro = 'Selecione uma receita.';
    } else {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("DELETE FROM receitas WHERE id_receita = :id");
            $stmt->execute([':id' => (int) $id_receita]);
            $pdo->commit();
            $mensagem_sucesso = 'Receita excluída com sucesso!';
            $receitas = $pdo->query("SELECT id_receita, descricao, valor FROM receitas ORDER BY data_receita DESC")->fetchAll();
            $id_selecionado = '';
        } catch (PDOException $e) {
            $pdo->rollBack();
            $mensagem_erro = 'Erro ao excluir: ' . $e->getMessage();
        }
    }
}

$base = '../';
$page_title = 'Excluir Receita';
$active = 'receitas';
require __DIR__ . '/../includes/header.php';
?>
        <div class="form-box">
            <a href="../index.php" class="btn-voltar">Voltar para o início</a>
            <h2>Excluir Receita</h2>

            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>

            <?php if (!empty($receitas)): ?>
                <form action="excluir.php" method="POST">
                    <div class="form-grupo">
                        <label for="id_receita">Receita</label>
                        <select id="id_receita" name="id_receita" required>
                            <option value="">Selecione</option>
                            <?php foreach ($receitas as $r): ?>
                                <option value="<?= (int) $r['id_receita'] ?>" <?= ($r['id_receita'] == $id_selecionado ? 'selected' : '') ?>>
                                    <?= htmlspecialchars($r['descricao']) ?> — <?= formatar_moeda($r['valor']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="form-ajuda">Esta ação não pode ser desfeita.</span>
                    </div>
                    <button type="submit" class="btn-enviar btn-perigo">Excluir Receita</button>
                </form>
            <?php else: ?>
                <p class="form-vazio">Nenhuma receita cadastrada.</p>
            <?php endif; ?>
        </div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
