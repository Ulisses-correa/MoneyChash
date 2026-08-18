<?php
require __DIR__ . '/../includes/config.php';

$mensagem_erro = '';
$receitas = [];

try {
    $sql = "SELECT r.id_receita, r.descricao, r.valor, r.data_receita,
                   u.nome AS usuario, c.nome AS categoria
            FROM receitas r
            INNER JOIN usuarios u ON r.id_usuario = u.id_usuario
            INNER JOIN categorias c ON r.id_categoria = c.id_categoria
            ORDER BY r.data_receita DESC";
    $receitas = $pdo->query($sql)->fetchAll();
} catch (PDOException $e) {
    $mensagem_erro = 'Erro ao conectar: ' . $e->getMessage();
}

$base = '../';
$page_title = 'Receitas';
$active = 'receitas';
require __DIR__ . '/../includes/header.php';
?>
        <div class="form-box">
            <a href="../index.php" class="btn-voltar">Voltar para o início</a>
            <h2>Receitas Cadastradas</h2>
            <a href="cadastrar.php" class="btn-navegacao">+ Nova Receita</a>

            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>

            <?php if (!empty($receitas)): ?>
                <table class="tabela">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuário</th>
                            <th>Categoria</th>
                            <th>Descrição</th>
                            <th>Valor</th>
                            <th>Data</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($receitas as $r): ?>
                            <tr>
                                <td>#<?= (int) $r['id_receita'] ?></td>
                                <td><?= htmlspecialchars($r['usuario']) ?></td>
                                <td><?= htmlspecialchars($r['categoria']) ?></td>
                                <td><?= htmlspecialchars($r['descricao'] ?: '—') ?></td>
                                <td><?= formatar_moeda($r['valor']) ?></td>
                                <td><?= formatar_data($r['data_receita']) ?></td>
                                <td>
                                    <a href="editar.php?id_receita=<?= (int) $r['id_receita'] ?>" class="btn-navegacao">Editar</a>
                                    <a href="excluir.php?id_receita=<?= (int) $r['id_receita'] ?>" class="btn-navegacao btn-perigo">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="form-vazio">Nenhuma receita cadastrada.</p>
            <?php endif; ?>
        </div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
