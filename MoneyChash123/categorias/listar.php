<?php
require __DIR__ . '/../includes/config.php';

$mensagem_erro = '';
$categorias = [];

try {
    $sql = "SELECT id_categoria, nome, tipo FROM categorias ORDER BY tipo, nome";
    $categorias = $pdo->query($sql)->fetchAll();
} catch (PDOException $e) {
    $mensagem_erro = 'Erro ao conectar: ' . $e->getMessage();
}

$base = '../';
$page_title = 'Categorias';
$active = 'categorias';
require __DIR__ . '/../includes/header.php';
?>
        <div class="form-box">
            <a href="../index.php" class="btn-voltar">Voltar para o início</a>
            <h2>Categorias Cadastradas</h2>
            <a href="cadastrar.php" class="btn-navegacao">+ Nova Categoria</a>

            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>

            <?php if (!empty($categorias)): ?>
                <table class="tabela">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nome</th>
                            <th>Tipo</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categorias as $c): ?>
                            <tr>
                                <td>#<?= (int) $c['id_categoria'] ?></td>
                                <td><?= htmlspecialchars($c['nome']) ?></td>
                                <td>
                                    <span class="selo <?= $c['tipo'] === 'Receita' ? 'selo-receita' : 'selo-despesa' ?>">
                                        <?= htmlspecialchars($c['tipo']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="editar.php?id_categoria=<?= (int) $c['id_categoria'] ?>" class="btn-navegacao">Editar</a>
                                    <a href="excluir.php?id_categoria=<?= (int) $c['id_categoria'] ?>" class="btn-navegacao btn-perigo">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="form-vazio">Nenhuma categoria cadastrada.</p>
            <?php endif; ?>
        </div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
