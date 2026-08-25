<?php
require __DIR__ . '/../includes/config.php';
exigir_login();

$mensagem_sucesso = '';
$mensagem_erro = '';
$id_selecionado = $_GET['id_usuario'] ?? '';

try {
    $usuarios = $pdo->query("SELECT id_usuario, nome, email FROM usuarios ORDER BY nome")->fetchAll();
} catch (PDOException $e) {
    $mensagem_erro = 'Erro ao carregar usuários: ' . $e->getMessage();
    $usuarios = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = trim($_POST['id_usuario'] ?? '');
    if (empty($id_usuario)) {
        $mensagem_erro = 'Selecione um usuário.';
    } else {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id_usuario = :id");
            $stmt->execute([':id' => (int) $id_usuario]);
            $pdo->commit();
            $mensagem_sucesso = 'Usuário excluído com sucesso!';
            $usuarios = $pdo->query("SELECT id_usuario, nome, email FROM usuarios ORDER BY nome")->fetchAll();
            $id_selecionado = '';
        } catch (PDOException $e) {
            $pdo->rollBack();
            $mensagem_erro = 'Erro ao excluir: ' . $e->getMessage();
        }
    }
}

$base = '../';
$page_title = 'Excluir Usuário';
$active = 'usuarios';
require __DIR__ . '/../includes/header.php';
?>
        <div class="form-box">
            <a href="../painel.php" class="btn-voltar">Voltar para o início</a>
            <h2>Excluir Usuário</h2>

            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>

            <?php if (!empty($usuarios)): ?>
                <form action="excluir.php" method="POST">
                    <div class="form-grupo">
                        <label for="id_usuario">Usuário</label>
                        <select id="id_usuario" name="id_usuario" required>
                            <option value="">Selecione</option>
                            <?php foreach ($usuarios as $u): ?>
                                <option value="<?= (int) $u['id_usuario'] ?>" <?= ($u['id_usuario'] == $id_selecionado ? 'selected' : '') ?>>
                                    <?= htmlspecialchars($u['nome']) ?> — <?= htmlspecialchars($u['email']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="form-ajuda">Esta ação não pode ser desfeita.</span>
                    </div>
                    <button type="submit" class="btn-enviar btn-perigo">Excluir Usuário</button>
                </form>
            <?php else: ?>
                <p class="form-vazio">Nenhum usuário cadastrado.</p>
            <?php endif; ?>
        </div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
