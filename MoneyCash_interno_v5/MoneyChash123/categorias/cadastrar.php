<?php
require __DIR__ . '/../includes/config.php';
exigir_login();

$mensagem_sucesso = '';
$mensagem_erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $tipo = trim($_POST['tipo'] ?? '');

    if (empty($nome) || empty($tipo)) {
        $mensagem_erro = 'Preencha todos os campos.';
    } else {
        try {
            $stmt = $pdo->prepare("INSERT INTO categorias (nome, tipo) VALUES (:nome, :tipo)");
            $stmt->execute([':nome' => $nome, ':tipo' => $tipo]);
            $mensagem_sucesso = 'Categoria cadastrada com sucesso!';
        } catch (PDOException $e) {
            $mensagem_erro = 'Erro ao salvar: ' . $e->getMessage();
        }
    }
}

$base = '../';
$page_title = 'Cadastrar Categoria';
$active = 'categorias';
require __DIR__ . '/../includes/header.php';
?>
        <div class="form-box">
            <a href="../painel.php" class="btn-voltar">Voltar para o início</a>
            <h2>Cadastrar Categoria</h2>

            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>

            <form action="cadastrar.php" method="POST">
                <div class="form-grupo">
                    <label for="nome">Nome da categoria</label>
                    <input type="text" id="nome" name="nome" placeholder="Ex.: Alimentação" required>
                </div>
                <div class="form-grupo">
                    <label for="tipo">Tipo</label>
                    <select id="tipo" name="tipo" required>
                        <option value="Receita">Receita</option>
                        <option value="Despesa">Despesa</option>
                    </select>
                </div>
                <button type="submit" class="btn-enviar">Cadastrar</button>
            </form>
        </div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
