<?php
$host = 'localhost';
$dbname = 'gestao_financeira';
$username = 'root';
$password = '';

$mensagem_erro = "";
$movimentacoes = [];
$contas = [];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $contas = $pdo->query("SELECT id_conta, nome_conta FROM contas ORDER BY nome_conta")->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensagem_erro = "Erro de conexão: " . $e->getMessage();
}

$filtro_conta = $_GET['id_conta'] ?? '';

try {
    $sql = "SELECT m.id_movimentacao, m.descricao, m.valor, m.data_movimentacao, m.tipo,
                   c.nome_conta, cat.nome as categoria
            FROM movimentacoes m
            INNER JOIN contas c ON m.id_conta = c.id_conta
            INNER JOIN categorias cat ON m.id_categoria = cat.id_categoria";
    if (!empty($filtro_conta)) {
        $sql .= " WHERE m.id_conta = :id_conta";
    }
    $sql .= " ORDER BY m.data_movimentacao DESC";
    $stmt = $pdo->prepare($sql);
    if (!empty($filtro_conta)) {
        $stmt->execute([':id_conta' => (int)$filtro_conta]);
    } else {
        $stmt->execute();
    }
    $movimentacoes = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensagem_erro = "Erro ao carregar extrato: " . $e->getMessage();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Extrato - MoneyChash</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
        <div class="header-content">
            <div>
                <h1>💰 MoneyChash</h1>
            </div>
            <div class="header-nav">
                <a href="../index.php" class="btn-navegacao">🏠 Início</a>
            </div>
        </div>
    </header>
    <main class="container-formulario">
        <div class="back-button">
            <a href="../index.php">← Voltar</a>
        </div>
        <div class="form-box">
            <h2>📊 Extrato de Movimentações</h2>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>
            <form action="extrato.php" method="GET">
                <div class="form-grupo">
                    <label for="id_conta">Filtrar por Conta</label>
                    <select id="id_conta" name="id_conta" onchange="this.form.submit()">
                        <option value="">Todas</option>
                        <?php foreach ($contas as $c): ?>
                            <option value="<?= $c['id_conta'] ?>" <?= ($c['id_conta'] == $filtro_conta ? 'selected' : '') ?>>
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
                                <td><?= htmlspecialchars($m['data_movimentacao']) ?></td>
                                <td><?= htmlspecialchars($m['nome_conta']) ?></td>
                                <td><?= htmlspecialchars($m['categoria']) ?></td>
                                <td><?= htmlspecialchars($m['descricao']) ?></td>
                                <td>R$ <?= number_format($m['valor'], 2, ',', '.') ?></td>
                                <td><?= htmlspecialchars($m['tipo']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Nenhuma movimentação encontrada.</p>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>