<?php
require __DIR__ . '/../includes/config.php';
exigir_login();

$mensagem_erro = '';
$contas = [];

try {
    $sql = "SELECT c.id_conta, c.nome_conta, c.saldo, c.tipo, u.nome AS usuario
            FROM contas c
            INNER JOIN usuarios u ON c.id_usuario = u.id_usuario
            ORDER BY u.nome, c.nome_conta";
    $contas = $pdo->query($sql)->fetchAll();
} catch (PDOException $e) {
    $mensagem_erro = 'Erro ao conectar: ' . $e->getMessage();
}

$base = '../';
$page_title = 'Contas';
$active = 'contas';
require __DIR__ . '/../includes/header.php';
?>
        <div class="form-box">
            <a href="../painel.php" class="btn-voltar">Voltar para o início</a>
            <h2>Contas Cadastradas</h2>
            <a href="cadastrar.php" class="btn-navegacao">+ Nova Conta</a>

            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>

            <?php if (!empty($contas)): ?>
                <table class="tabela">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuário</th>
                            <th>Nome</th>
                            <th>Saldo</th>
                            <th>Tipo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contas as $c): ?>
                            <tr>
                                <td>#<?= (int) $c['id_conta'] ?></td>
                                <td><?= htmlspecialchars($c['usuario']) ?></td>
                                <td><?= htmlspecialchars($c['nome_conta']) ?></td>
                                <td><?= formatar_moeda($c['saldo']) ?></td>
                                <td><span class="selo selo-neutro"><?= htmlspecialchars($c['tipo']) ?></span></td>
                                <td>
                                    <a href="editar.php?id_conta=<?= (int) $c['id_conta'] ?>" class="btn-navegacao">Editar</a>
                                    <a href="excluir.php?id_conta=<?= (int) $c['id_conta'] ?>" class="btn-navegacao btn-perigo">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="form-vazio">Nenhuma conta cadastrada.</p>
            <?php endif; ?>
        </div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
