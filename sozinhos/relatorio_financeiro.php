<?php
$host = 'localhost';
$dbname = 'gestao_financeira';
$username = 'root';
$password = '';

$mensagem_erro = "";
$total_receitas = 0;
$total_despesas = 0;
$dados_grafico = [];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Total de receitas (tabela receitas)
    $sql = "SELECT SUM(valor) as total FROM receitas";
    $total_receitas = (float)$pdo->query($sql)->fetchColumn();

    // Total de despesas (tabela despesas)
    $sql = "SELECT SUM(valor) as total FROM despesas";
    $total_despesas = (float)$pdo->query($sql)->fetchColumn();

    // Despesas por categoria
    $sql = "SELECT c.nome, SUM(d.valor) as total 
            FROM despesas d
            INNER JOIN categorias c ON d.id_categoria = c.id_categoria
            GROUP BY c.id_categoria";
    $stmt = $pdo->query($sql);
    $dados_grafico = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    $mensagem_erro = "Erro ao gerar relatório: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Relatório Financeiro - FinControl</title>
    <link rel="stylesheet" href="../style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <header>
        <h1>FinControl - Gestão Financeira</h1>
        <a href="../index.php" class="btn-navegacao">Voltar para o Início</a>
    </header>
    <main class="container-formulario">
        <div class="form-box">
            <h2>Relatório Financeiro</h2>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>

            <div style="display: flex; gap: 30px; flex-wrap: wrap;">
                <div><strong>Total Receitas:</strong> R$ <?= number_format($total_receitas, 2, ',', '.') ?></div>
                <div><strong>Total Despesas:</strong> R$ <?= number_format($total_despesas, 2, ',', '.') ?></div>
                <div><strong>Saldo:</strong> R$ <?= number_format($total_receitas - $total_despesas, 2, ',', '.') ?></div>
            </div>

            <?php if (!empty($dados_grafico)): ?>
                <hr>
                <h3>Despesas por Categoria</h3>
                <canvas id="graficoDespesas" width="400" height="200"></canvas>
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
                                backgroundColor: 'rgba(22, 99, 214, 0.6)',
                                borderColor: '#1663d6',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            scales: {
                                y: { beginAtZero: true }
                            }
                        }
                    });
                </script>
            <?php else: ?>
                <p>Nenhuma despesa cadastrada para exibir gráfico.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>