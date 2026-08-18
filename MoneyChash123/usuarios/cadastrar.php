<?php
require __DIR__ . '/../includes/config.php';

$mensagem_sucesso = '';
$mensagem_erro = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome            = trim($_POST['nome'] ?? '');
    $email           = trim($_POST['email'] ?? '');
    $telefone        = trim($_POST['telefone'] ?? '');
    $data_nascimento = trim($_POST['data_nascimento'] ?? '');
    $senha           = trim($_POST['senha'] ?? '');

    if (empty($nome) || empty($email) || empty($senha)) {
        $mensagem_erro = 'Preencha nome, e-mail e senha.';
    } else {
        try {
            $sql = "INSERT INTO usuarios (nome, email, telefone, data_nascimento, senha)
                    VALUES (:nome, :email, :telefone, :data_nascimento, :senha)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':nome'            => $nome,
                ':email'           => $email,
                ':telefone'        => $telefone,
                ':data_nascimento' => $data_nascimento ?: null,
                ':senha'           => password_hash($senha, PASSWORD_DEFAULT),
            ]);
            $mensagem_sucesso = 'Usuário cadastrado com sucesso!';
        } catch (PDOException $e) {
            $mensagem_erro = 'Erro ao salvar: ' . $e->getMessage();
        }
    }
}

$base = '../';
$page_title = 'Cadastrar Usuário';
$active = 'usuarios';
require __DIR__ . '/../includes/header.php';
?>
        <div class="form-box">
            <a href="../index.php" class="btn-voltar">Voltar para o início</a>
            <h2>Cadastrar Usuário</h2>

            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>

            <form action="cadastrar.php" method="POST">
                <div class="form-grupo">
                    <label for="nome">Nome completo</label>
                    <input type="text" id="nome" name="nome" required>
                </div>
                <div class="form-linha">
                    <div class="form-grupo">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" required>
                    </div>
                    <div class="form-grupo">
                        <label for="telefone">Telefone</label>
                        <input type="text" id="telefone" name="telefone" placeholder="(00) 00000-0000">
                    </div>
                </div>
                <div class="form-linha">
                    <div class="form-grupo">
                        <label for="data_nascimento">Data de nascimento</label>
                        <input type="date" id="data_nascimento" name="data_nascimento">
                    </div>
                    <div class="form-grupo">
                        <label for="senha">Senha</label>
                        <input type="password" id="senha" name="senha" required>
                    </div>
                </div>
                <button type="submit" class="btn-enviar">Cadastrar</button>
            </form>
        </div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
