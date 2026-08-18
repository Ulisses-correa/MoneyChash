<?php
require __DIR__ . '/../includes/config.php';

$mensagem_sucesso = '';
$mensagem_erro = '';
$id_selecionado = $_GET['id_conta'] ?? '';

try {
    $contas = $pdo->query("SELECT id_conta, nome_conta FROM contas ORDER BY nome_conta")->fetchAll();
} catch (PDOException $e) {
    $mensagem_erro = 'Erro ao carregar contas: ' . $e->getMessage();
    $contas = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_conta = trim($_POST['id_conta'] ?? '');
    if (empty($id_conta)) {
        $mensagem_erro = 'Selecione uma conta.';
    } else {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("DELETE FROM contas WHERE id_conta = :id");
            $stmt->execute([':id' => (int) $id_conta]);
            $pdo->commit();
            $mensagem_sucesso = 'Conta excluída com sucesso!';
            $contas = $pdo->query("SELECT id_conta, nome_conta FROM contas ORDER BY nome_conta")->fetchAll();
            $id_selecionado = '';
        } catch (PDOException $e) {
            $pdo->rollBack();
            $mensagem_erro = 'Erro ao excluir: ' . $e->getMessage();
        }
    }
}

$base = '../';
$page_title = 'Excluir Conta';
$active = 'contas';
require __DIR__ . '/../includes/header.php';
?>
        <div class="form-box">
            <a href="../index.php" class="btn-voltar">Voltar para o início</a>
            <h2>Excluir Conta</h2>

            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>

            <?php if (!empty($contas)): ?>
                <form action="excluir.php" method="POST">
                    <div class="form-grupo">
                        <label for="id_conta">Conta</label>
                        <select id="id_conta" name="id_conta" required>
                            <option value="">Selecione</option>
                            <?php foreach ($contas as $c): ?>
                                <option value="<?= (int) $c['id_conta'] ?>" <?= ($c['id_conta'] == $id_selecionado ? 'selected' : '') ?>>
                                    <?= htmlspecialchars($c['nome_conta']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="form-ajuda">Esta ação não pode ser desfeita.</span>
                    </div>
                    <button type="submit" class="btn-enviar btn-perigo">Excluir Conta</button>
                </form>
            <?php else: ?>
                <p class="form-vazio">Nenhuma conta cadastrada.</p>
            <?php endif; ?>
        </div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
