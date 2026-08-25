<?php
require __DIR__ . '/../includes/config.php';
exigir_login();

$mensagem_erro = '';
$despesas = [];

try {
    $sql = "SELECT d.id_despesa, d.descricao, d.valor, d.data_despesa, d.status_pagamento,
                   u.nome AS usuario, c.nome AS categoria
            FROM despesas d
            INNER JOIN usuarios u ON d.id_usuario = u.id_usuario
            INNER JOIN categorias c ON d.id_categoria = c.id_categoria
            ORDER BY d.data_despesa DESC";
    $despesas = $pdo->query($sql)->fetchAll();
} catch (PDOException $e) {
    $mensagem_erro = 'Erro ao conectar: ' . $e->getMessage();
}

$base = '../';
$page_title = 'Despesas';
$active = 'despesas';
require __DIR__ . '/../includes/header.php';
?>
        <div class="form-box">
            <a href="../painel.php" class="btn-voltar">Voltar para o início</a>
            <h2>Despesas Cadastradas</h2>
            <a href="cadastrar.php" class="btn-navegacao">+ Nova Despesa</a>

            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>

            <?php if (!empty($despesas)): ?>
                <table class="tabela">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuário</th>
                            <th>Categoria</th>
                            <th>Descrição</th>
                            <th>Valor</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($despesas as $d): ?>
                            <tr>
                                <td>#<?= (int) $d['id_despesa'] ?></td>
                                <td><?= htmlspecialchars($d['usuario']) ?></td>
                                <td><?= htmlspecialchars($d['categoria']) ?></td>
                                <td><?= htmlspecialchars($d['descricao'] ?: '—') ?></td>
                                <td><?= formatar_moeda($d['valor']) ?></td>
                                <td><?= formatar_data($d['data_despesa']) ?></td>
                                <td>
                                    <span class="selo <?= $d['status_pagamento'] === 'Pago' ? 'selo-sucesso' : 'selo-pendente' ?>">
                                        <?= htmlspecialchars($d['status_pagamento']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="editar.php?id_despesa=<?= (int) $d['id_despesa'] ?>" class="btn-navegacao">Editar</a>
                                    <a href="excluir.php?id_despesa=<?= (int) $d['id_despesa'] ?>" class="btn-navegacao btn-perigo">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="form-vazio">Nenhuma despesa cadastrada.</p>
            <?php endif; ?>
        </div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
