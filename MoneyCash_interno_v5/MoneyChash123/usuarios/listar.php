<?php
require __DIR__ . '/../includes/config.php';
exigir_login();

$mensagem_erro = '';
$usuarios = [];

try {
    $sql = "SELECT id_usuario, nome, email, telefone, data_nascimento FROM usuarios ORDER BY nome";
    $usuarios = $pdo->query($sql)->fetchAll();
} catch (PDOException $e) {
    $mensagem_erro = 'Erro ao carregar usuários: ' . $e->getMessage();
}

$base = '../';
$page_title = 'Usuários';
$active = 'usuarios';
require __DIR__ . '/../includes/header.php';
?>
        <div class="form-box">
            <a href="../painel.php" class="btn-voltar">Voltar para o início</a>
            <h2>Usuários Cadastrados</h2>
            <a href="cadastrar.php" class="btn-navegacao">+ Novo Usuário</a>

            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>

            <?php if (!empty($usuarios)): ?>
                <table class="tabela">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>E-mail</th>
                            <th>Telefone</th>
                            <th>Nascimento</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $u): ?>
                            <tr>
                                <td>#<?= (int) $u['id_usuario'] ?></td>
                                <td><?= htmlspecialchars($u['nome']) ?></td>
                                <td><?= htmlspecialchars($u['email']) ?></td>
                                <td><?= htmlspecialchars($u['telefone'] ?: '—') ?></td>
                                <td><?= formatar_data($u['data_nascimento']) ?></td>
                                <td>
                                    <a href="editar.php?id_usuario=<?= (int) $u['id_usuario'] ?>" class="btn-navegacao">Editar</a>
                                    <a href="excluir.php?id_usuario=<?= (int) $u['id_usuario'] ?>" class="btn-navegacao btn-perigo">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="form-vazio">Nenhum usuário cadastrado ainda.</p>
            <?php endif; ?>
        </div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
