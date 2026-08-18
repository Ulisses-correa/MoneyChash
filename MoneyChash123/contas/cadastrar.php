<?php
require __DIR__ . '/../includes/config.php';

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
    $nome_conta = trim($_POST['nome_conta'] ?? '');
    $saldo = trim($_POST['saldo'] ?? '');
    $tipo = trim($_POST['tipo'] ?? '');

    if (empty($id_usuario) || empty($nome_conta) || $saldo === '' || empty($tipo)) {
        $mensagem_erro = 'Preencha todos os campos.';
    } else {
        try {
            $sql = "INSERT INTO contas (id_usuario, nome_conta, saldo, tipo) VALUES (:id_usuario, :nome_conta, :saldo, :tipo)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id_usuario' => (int) $id_usuario,
                ':nome_conta' => $nome_conta,
                ':saldo' => (float) $saldo,
                ':tipo' => $tipo,
            ]);
            $mensagem_sucesso = 'Conta cadastrada com sucesso!';
        } catch (PDOException $e) {
            $mensagem_erro = 'Erro ao salvar: ' . $e->getMessage();
        }
    }
}

$base = '../';
$page_title = 'Cadastrar Conta';
$active = 'contas';
require __DIR__ . '/../includes/header.php';
?>
        <div class="form-box">
            <a href="../index.php" class="btn-voltar">Voltar para o início</a>
            <h2>Cadastrar Conta</h2>

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
                <div class="form-linha">
                    <div class="form-grupo">
                        <label for="nome_conta">Nome da conta</label>
                        <input type="text" id="nome_conta" name="nome_conta" placeholder="Ex.: Conta principal" required>
                    </div>
                    <div class="form-grupo">
                        <label for="saldo">Saldo inicial</label>
                        <input type="number" step="0.01" id="saldo" name="saldo" value="0.00" required>
                    </div>
                </div>
                <div class="form-grupo">
                    <label for="tipo">Tipo</label>
                    <select id="tipo" name="tipo" required>
                        <option value="Corrente">Corrente</option>
                        <option value="Poupança">Poupança</option>
                        <option value="Carteira">Carteira</option>
                    </select>
                </div>
                <button type="submit" class="btn-enviar">Cadastrar</button>
            </form>
        </div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
