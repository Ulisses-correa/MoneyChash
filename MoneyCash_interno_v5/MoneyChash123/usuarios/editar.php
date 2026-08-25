<?php
require __DIR__ . '/../includes/config.php';
exigir_login();

$mensagem_sucesso = '';
$mensagem_erro = '';

$id_usuario = $_GET['id_usuario'] ?? $_POST['id_usuario'] ?? '';
$nome = $email = $telefone = $data_nascimento = '';
$usuarios = [];

try {
    $usuarios = $pdo->query("SELECT id_usuario, nome FROM usuarios ORDER BY nome")->fetchAll();
} catch (PDOException $e) {
    $mensagem_erro = 'Erro ao conectar: ' . $e->getMessage();
}

if (!empty($id_usuario)) {
    try {
        $stmt = $pdo->prepare("SELECT nome, email, telefone, data_nascimento FROM usuarios WHERE id_usuario = :id");
        $stmt->execute([':id' => (int) $id_usuario]);
        $u = $stmt->fetch();
        if ($u) {
            $nome = $u['nome'];
            $email = $u['email'];
            $telefone = $u['telefone'];
            $data_nascimento = $u['data_nascimento'];
        }
    } catch (PDOException $e) {
        $mensagem_erro = 'Erro ao carregar dados: ' . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salvar'])) {
    $id_usuario      = trim($_POST['id_usuario'] ?? '');
    $nome            = trim($_POST['nome'] ?? '');
    $email           = trim($_POST['email'] ?? '');
    $telefone        = trim($_POST['telefone'] ?? '');
    $data_nascimento = trim($_POST['data_nascimento'] ?? '');
    $senha           = trim($_POST['senha'] ?? '');

    if (empty($id_usuario) || empty($nome) || empty($email)) {
        $mensagem_erro = 'Preencha os campos obrigatórios.';
    } else {
        try {
            if (!empty($senha)) {
                $sql = "UPDATE usuarios SET nome = :nome, email = :email, telefone = :telefone,
                        data_nascimento = :data_nascimento, senha = :senha WHERE id_usuario = :id";
                $params = [
                    ':nome' => $nome, ':email' => $email, ':telefone' => $telefone,
                    ':data_nascimento' => $data_nascimento ?: null,
                    ':senha' => password_hash($senha, PASSWORD_DEFAULT),
                    ':id' => (int) $id_usuario,
                ];
            } else {
                $sql = "UPDATE usuarios SET nome = :nome, email = :email, telefone = :telefone,
                        data_nascimento = :data_nascimento WHERE id_usuario = :id";
                $params = [
                    ':nome' => $nome, ':email' => $email, ':telefone' => $telefone,
                    ':data_nascimento' => $data_nascimento ?: null,
                    ':id' => (int) $id_usuario,
                ];
            }
            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);
            $mensagem_sucesso = 'Usuário atualizado com sucesso!';
        } catch (PDOException $e) {
            $mensagem_erro = 'Erro ao atualizar: ' . $e->getMessage();
        }
    }
}

$base = '../';
$page_title = 'Editar Usuário';
$active = 'usuarios';
require __DIR__ . '/../includes/header.php';
?>
        <div class="form-box">
            <a href="../painel.php" class="btn-voltar">Voltar para o início</a>
            <h2>Editar Usuário</h2>

            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>

            <form action="editar.php" method="GET">
                <div class="form-grupo">
                    <label for="id_usuario">Selecionar usuário</label>
                    <select id="id_usuario" name="id_usuario" onchange="this.form.submit()">
                        <option value="">Selecione</option>
                        <?php foreach ($usuarios as $u): ?>
                            <option value="<?= (int) $u['id_usuario'] ?>" <?= ($u['id_usuario'] == $id_usuario ? 'selected' : '') ?>>
                                <?= htmlspecialchars($u['nome']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>

            <?php if (!empty($id_usuario) && !empty($nome)): ?>
                <hr>
                <form action="editar.php" method="POST">
                    <input type="hidden" name="id_usuario" value="<?= htmlspecialchars((string) $id_usuario) ?>">
                    <div class="form-grupo">
                        <label for="nome">Nome completo</label>
                        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($nome) ?>" required>
                    </div>
                    <div class="form-linha">
                        <div class="form-grupo">
                            <label for="email">E-mail</label>
                            <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required>
                        </div>
                        <div class="form-grupo">
                            <label for="telefone">Telefone</label>
                            <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($telefone ?? '') ?>">
                        </div>
                    </div>
                    <div class="form-linha">
                        <div class="form-grupo">
                            <label for="data_nascimento">Data de nascimento</label>
                            <input type="date" id="data_nascimento" name="data_nascimento" value="<?= htmlspecialchars($data_nascimento ?? '') ?>">
                        </div>
                        <div class="form-grupo">
                            <label for="senha">Nova senha</label>
                            <input type="password" id="senha" name="senha" placeholder="Deixe em branco para manter">
                        </div>
                    </div>
                    <button type="submit" name="salvar" class="btn-enviar">Salvar Alterações</button>
                </form>
            <?php endif; ?>
        </div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
