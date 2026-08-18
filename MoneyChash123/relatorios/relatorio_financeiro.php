<?php
require __DIR__ . '/../includes/config.php';

$mensagem_erro = '';
$total_receitas = 0;
$total_despesas = 0;
$dados_grafico = [];

try {
    $total_receitas = (float) $pdo->query("SELECT COALESCE(SUM(valor), 0) FROM receitas")->fetchColumn();
    $total_despesas = (float) $pdo->query("SELECT COALESCE(SUM(valor), 0) FROM despesas")->fetchColumn();

    $sql = "SELECT c.nome, SUM(d.valor) AS total
            FROM despesas d
            INNER JOIN categorias c ON d.id_categoria = c.id_categoria
            GROUP BY c.id_categoria
            ORDER BY total DESC";
    $dados_grafico = $pdo->query($sql)->fetchAll();
} catch (PDOException $e) {
    $mensagem_erro = 'Erro ao gerar relatório: ' . $e->getMessage();
}

$saldo = $total_receitas - $total_despesas;

$base = '../';
$page_title = 'Relatório Financeiro';
$active = 'relatorios';
require __DIR__ . '/../includes/header.php';
?>
        <div class="form-box">
            <a href="../index.php" class="btn-voltar">Voltar para o início</a>
            <h2>Relatório Financeiro</h2>

            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>

            <div class="stats-row">
                <div class="stat-card stat-success">
                    <div class="stat-label">Total de receitas</div>
                    <div class="stat-value"><?= formatar_moeda($total_receitas) ?></div>
                </div>
                <div class="stat-card stat-danger">
                    <div class="stat-label">Total de despesas</div>
                    <div class="stat-value"><?= formatar_moeda($total_despesas) ?></div>
                </div>
                <div class="stat-card <?= $saldo >= 0 ? 'stat-success' : 'stat-danger' ?>">
                    <div class="stat-label">Saldo</div>
                    <div class="stat-value"><?= formatar_moeda($saldo) ?></div>
                </div>
            </div>

            <?php if (!empty($dados_grafico)): ?>
                <hr>
                <h3>Despesas por Categoria</h3>
                <canvas id="graficoDespesas" height="120"></canvas>
            <?php else: ?>
                <p class="form-vazio">Nenhuma despesa cadastrada para exibir o gráfico.</p>
            <?php endif; ?>
        </div>
<?php require __DIR__ . '/../includes/footer.php'; ?>

<?php if (!empty($dados_grafico)): ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('graficoDespesas').getContext('2d');
    const labels = <?= json_encode(array_column($dados_grafico, 'nome')) ?>;
    const valores = <?= json_encode(array_column($dados_grafico, 'total')) ?>;
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Total (R$)',
                data: valores,
                backgroundColor: 'rgba(31, 130, 87, 0.65)',
                borderColor: '#14543a',
                borderRadius: 6,
                borderWidth: 1
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: { y: { beginAtZero: true } }
        }
    });
</script>
<?php endif; ?>
