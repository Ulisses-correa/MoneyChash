<?php
require __DIR__ . '/../includes/config.php';

$mensagem_erro = '';
$movimentacoes = [];
$contas = [];

try {
    $contas = $pdo->query("SELECT id_conta, nome_conta FROM contas ORDER BY nome_conta")->fetchAll();
} catch (PDOException $e) {
    $mensagem_erro = 'Erro de conexão: ' . $e->getMessage();
}

$filtro_conta = $_GET['id_conta'] ?? '';

try {
    $sql = "SELECT m.id_movimentacao, m.descricao, m.valor, m.data_movimentacao, m.tipo,
                   c.nome_conta, cat.nome AS categoria
            FROM movimentacoes m
            INNER JOIN contas c ON m.id_conta = c.id_conta
            INNER JOIN categorias cat ON m.id_categoria = cat.id_categoria";
    if (!empty($filtro_conta)) {
        $sql .= " WHERE m.id_conta = :id_conta";
    }
    $sql .= " ORDER BY m.data_movimentacao DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(!empty($filtro_conta) ? [':id_conta' => (int) $filtro_conta] : []);
    $movimentacoes = $stmt->fetchAll();
} catch (PDOException $e) {
    $mensagem_erro = 'Erro ao carregar extrato: ' . $e->getMessage();
}

$base = '../';
$page_title = 'Extrato de Movimentações';
$active = 'movimentacoes';
require __DIR__ . '/../includes/header.php';
?>
        <div class="form-box">
            <a href="../index.php" class="btn-voltar">Voltar para o início</a>
            <h2>Extrato de Movimentações</h2>

            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>

            <form action="extrato.php" method="GET" class="form-filtro">
                <div class="form-grupo">
                    <label for="id_conta">Filtrar por conta</label>
                    <select id="id_conta" name="id_conta" onchange="this.form.submit()">
                        <option value="">Todas as contas</option>
                        <?php foreach ($contas as $c): ?>
                            <option value="<?= (int) $c['id_conta'] ?>" <?= ($c['id_conta'] == $filtro_conta ? 'selected' : '') ?>>
                                <?= htmlspecialchars($c['nome_conta']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>

            <?php if (!empty($movimentacoes)): ?>
                <table class="tabela">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Conta</th>
                            <th>Categoria</th>
                            <th>Descrição</th>
                            <th>Valor</th>
                            <th>Tipo</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($movimentacoes as $m): ?>
                            <tr>
                                <td><?= formatar_data($m['data_movimentacao']) ?></td>
                                <td><?= htmlspecialchars($m['nome_conta']) ?></td>
                                <td><?= htmlspecialchars($m['categoria']) ?></td>
                                <td><?= htmlspecialchars($m['descricao'] ?: '—') ?></td>
                                <td><?= formatar_moeda($m['valor']) ?></td>
                                <td>
                                    <span class="selo <?= $m['tipo'] === 'Entrada' ? 'selo-receita' : 'selo-despesa' ?>">
                                        <?= htmlspecialchars($m['tipo']) ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="form-vazio">Nenhuma movimentação encontrada.</p>
            <?php endif; ?>
        </div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
