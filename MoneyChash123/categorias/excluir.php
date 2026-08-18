<?php
require __DIR__ . '/../includes/config.php';

$mensagem_sucesso = '';
$mensagem_erro = '';
$id_selecionado = $_GET['id_categoria'] ?? '';

try {
    $categorias = $pdo->query("SELECT id_categoria, nome, tipo FROM categorias ORDER BY nome")->fetchAll();
} catch (PDOException $e) {
    $mensagem_erro = 'Erro ao carregar categorias: ' . $e->getMessage();
    $categorias = [];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_categoria = trim($_POST['id_categoria'] ?? '');
    if (empty($id_categoria)) {
        $mensagem_erro = 'Selecione uma categoria.';
    } else {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("DELETE FROM categorias WHERE id_categoria = :id");
            $stmt->execute([':id' => (int) $id_categoria]);
            $pdo->commit();
            $mensagem_sucesso = 'Categoria excluída com sucesso!';
            $categorias = $pdo->query("SELECT id_categoria, nome, tipo FROM categorias ORDER BY nome")->fetchAll();
            $id_selecionado = '';
        } catch (PDOException $e) {
            $pdo->rollBack();
            $mensagem_erro = 'Erro ao excluir: ' . $e->getMessage();
        }
    }
}

$base = '../';
$page_title = 'Excluir Categoria';
$active = 'categorias';
require __DIR__ . '/../includes/header.php';
?>
        <div class="form-box">
            <a href="../index.php" class="btn-voltar">Voltar para o início</a>
            <h2>Excluir Categoria</h2>

            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>

            <?php if (!empty($categorias)): ?>
                <form action="excluir.php" method="POST">
                    <div class="form-grupo">
                        <label for="id_categoria">Categoria</label>
                        <select id="id_categoria" name="id_categoria" required>
                            <option value="">Selecione</option>
                            <?php foreach ($categorias as $c): ?>
                                <option value="<?= (int) $c['id_categoria'] ?>" <?= ($c['id_categoria'] == $id_selecionado ? 'selected' : '') ?>>
                                    <?= htmlspecialchars($c['nome']) ?> (<?= htmlspecialchars($c['tipo']) ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <span class="form-ajuda">Excluir uma categoria em uso pode afetar receitas/despesas vinculadas.</span>
                    </div>
                    <button type="submit" class="btn-enviar btn-perigo">Excluir Categoria</button>
                </form>
            <?php else: ?>
                <p class="form-vazio">Nenhuma categoria cadastrada.</p>
            <?php endif; ?>
        </div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
