<?php
require_once __DIR__ . '/includes/config.php';
exigir_login();

$usuario = usuario_atual();
$usuario_id = (int) ($usuario['id_usuario'] ?? 0);
$nome_usuario = trim((string) ($usuario['nome'] ?? 'Usuário'));
$primeiro_nome = explode(' ', $nome_usuario)[0] ?: 'Usuário';

$saldo_geral = 0.0;
$total_receitas = 0.0;
$total_despesas = 0.0;
$total_contas = 0;
$total_metas = 0;
$notificacoes_pendentes = 0;
$movimentacoes = [];
$mensagem_erro = '';

try {
    $stmt = $pdo->prepare('SELECT COALESCE(SUM(valor),0) FROM receitas WHERE id_usuario = :id');
    $stmt->execute([':id' => $usuario_id]);
    $total_receitas = (float) $stmt->fetchColumn();

    $stmt = $pdo->prepare('SELECT COALESCE(SUM(valor),0) FROM despesas WHERE id_usuario = :id');
    $stmt->execute([':id' => $usuario_id]);
    $total_despesas = (float) $stmt->fetchColumn();

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM contas WHERE id_usuario = :id');
    $stmt->execute([':id' => $usuario_id]);
    $total_contas = (int) $stmt->fetchColumn();

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM metas WHERE id_usuario = :id AND status NOT IN (\'Concluída\', \'Cancelada\')');
    $stmt->execute([':id' => $usuario_id]);
    $total_metas = (int) $stmt->fetchColumn();

    $stmt = $pdo->prepare('SELECT COUNT(*) FROM notificacoes WHERE id_usuario = :id AND lida = 0');
    $stmt->execute([':id' => $usuario_id]);
    $notificacoes_pendentes = (int) $stmt->fetchColumn();

    $stmt = $pdo->prepare("\
        SELECT tipo, descricao, valor, data_movimentacao, categoria FROM (\
            SELECT 'Receita' AS tipo, r.descricao, r.valor, r.data_receita AS data_movimentacao, c.nome AS categoria\
            FROM receitas r\
            INNER JOIN categorias c ON c.id_categoria = r.id_categoria\
            WHERE r.id_usuario = :id1\
            UNION ALL\
            SELECT 'Despesa' AS tipo, d.descricao, d.valor, d.data_despesa AS data_movimentacao, c.nome AS categoria\
            FROM despesas d\
            INNER JOIN categorias c ON c.id_categoria = d.id_categoria\
            WHERE d.id_usuario = :id2\
        ) movimentos\
        ORDER BY data_movimentacao DESC\
        LIMIT 7\
    ");
    $stmt->execute([':id1' => $usuario_id, ':id2' => $usuario_id]);
    $movimentacoes = $stmt->fetchAll();

    $saldo_geral = $total_receitas - $total_despesas;
} catch (PDOException $e) {
    $mensagem_erro = 'Não foi possível carregar todos os indicadores do painel.';
}

$taxa_economia = $total_receitas > 0 ? (($saldo_geral / $total_receitas) * 100) : 0;
$taxa_economia = max(0, min(100, $taxa_economia));
$score = $taxa_economia >= 30 ? 'Saudável' : ($taxa_economia >= 10 ? 'Em equilíbrio' : 'Atenção');

$base = '';
$page_title = 'Visão geral';
$active = 'inicio';
$main_class = 'container';
require __DIR__ . '/includes/header.php';
?>

<div class="dashboard-hero">
    <section class="dashboard-welcome">
        <p class="eyebrow">Visão geral</p>
        <h2>Olá, <?= htmlspecialchars($primeiro_nome) ?>.</h2>
        <p>Tenha uma visão rápida do seu dinheiro, acompanhe suas movimentações e mantenha seus objetivos sob controle.</p>
    </section>

    <div class="dashboard-quick">
        <a href="receitas/cadastrar.php" class="quick-action">
            <span class="quick-action-icon"><?= moneycash_icon('trend-up') ?></span>
            <strong>Nova receita</strong>
            <span>Registrar entrada</span>
        </a>
        <a href="despesas/cadastrar.php" class="quick-action">
            <span class="quick-action-icon"><?= moneycash_icon('receipt') ?></span>
            <strong>Nova despesa</strong>
            <span>Registrar saída</span>
        </a>
        <a href="contas/cadastrar.php" class="quick-action">
            <span class="quick-action-icon"><?= moneycash_icon('bank') ?></span>
            <strong>Nova conta</strong>
            <span>Adicionar carteira</span>
        </a>
        <a href="metas/cadastrar.php" class="quick-action">
            <span class="quick-action-icon"><?= moneycash_icon('target') ?></span>
            <strong>Nova meta</strong>
            <span>Definir objetivo</span>
        </a>
    </div>
</div>

<?php if ($mensagem_erro): ?>
    <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
<?php endif; ?>

<div class="stats-row">
    <div class="stat-card stat-card-principal <?= $saldo_geral >= 0 ? 'stat-success' : 'stat-danger' ?>">
        <span class="stat-icon"><?= moneycash_icon('wallet') ?></span>
        <div><div class="stat-label">Saldo acumulado</div><div class="stat-value"><?= formatar_moeda($saldo_geral) ?></div></div>
    </div>
    <div class="stat-card">
        <span class="stat-icon"><?= moneycash_icon('trend-up') ?></span>
        <div><div class="stat-label">Receitas</div><div class="stat-value"><?= formatar_moeda($total_receitas) ?></div></div>
    </div>
    <div class="stat-card">
        <span class="stat-icon"><?= moneycash_icon('receipt') ?></span>
        <div><div class="stat-label">Despesas</div><div class="stat-value"><?= formatar_moeda($total_despesas) ?></div></div>
    </div>
    <div class="stat-card">
        <span class="stat-icon"><?= moneycash_icon('bank') ?></span>
        <div><div class="stat-label">Contas ativas</div><div class="stat-value"><?= $total_contas ?></div></div>
    </div>
</div>

<div class="dashboard-columns">
    <section class="dashboard-panel">
        <div class="dashboard-panel-head">
            <h3>Movimentações recentes</h3>
            <a href="movimentacoes/extrato.php">Ver extrato completo →</a>
        </div>
        <div class="movement-list">
            <?php if ($movimentacoes): ?>
                <?php foreach ($movimentacoes as $mov): ?>
                    <?php $tipo = $mov['tipo'] === 'Receita' ? 'receita' : 'despesa'; ?>
                    <div class="movement-item">
                        <span class="movement-icon <?= $tipo ?>"><?= moneycash_icon($tipo === 'receita' ? 'trend-up' : 'receipt') ?></span>
                        <div class="movement-info">
                            <strong><?= htmlspecialchars($mov['descricao'] ?: 'Movimentação sem descrição') ?></strong>
                            <span><?= htmlspecialchars($mov['categoria']) ?> · <?= formatar_data($mov['data_movimentacao']) ?></span>
                        </div>
                        <strong class="movement-value <?= $tipo ?>"><?= $tipo === 'receita' ? '+' : '-' ?> <?= formatar_moeda($mov['valor']) ?></strong>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p class="form-vazio">Ainda não há movimentações. Comece registrando uma receita ou despesa.</p>
            <?php endif; ?>
        </div>
    </section>

    <aside class="dashboard-panel">
        <div class="dashboard-panel-head">
            <h3>Saúde financeira</h3>
            <a href="relatorios/relatorio_financeiro.php">Relatório →</a>
        </div>
        <div class="health-card">
            <div class="health-label"><span>Taxa de economia</span><strong><?= number_format($taxa_economia, 0) ?>%</strong></div>
            <div class="health-score"><?= htmlspecialchars($score) ?></div>
            <div class="health-bar"><span style="width:<?= number_format($taxa_economia, 2, '.', '') ?>%"></span></div>
            <p class="health-hint">Quanto maior a parcela das receitas que permanece disponível, melhor o espaço para investir e alcançar suas metas.</p>
        </div>
        <div class="summary-list">
            <div class="summary-row"><span>Metas em andamento</span><strong><?= $total_metas ?></strong></div>
            <div class="summary-row"><span>Notificações pendentes</span><strong><?= $notificacoes_pendentes ?></strong></div>
            <div class="summary-row"><span>Resultado financeiro</span><strong><?= formatar_moeda($saldo_geral) ?></strong></div>
        </div>
    </aside>
</div>

<h2 class="section-title">Acesso rápido</h2>
<div class="dashboard-grid">
    <div class="dashboard-card"><span class="card-icon"><?= moneycash_icon('bank') ?></span><h3>Contas</h3><p>Gerencie bancos, carteiras e saldos disponíveis.</p><div class="card-links"><a href="contas/cadastrar.php" class="btn-navegacao">+ Cadastrar</a><a href="contas/listar.php" class="btn-navegacao">Ver contas</a></div></div>
    <div class="dashboard-card"><span class="card-icon"><?= moneycash_icon('tag') ?></span><h3>Categorias</h3><p>Deixe receitas e despesas organizadas por tipo.</p><div class="card-links"><a href="categorias/cadastrar.php" class="btn-navegacao">+ Cadastrar</a><a href="categorias/listar.php" class="btn-navegacao">Ver categorias</a></div></div>
    <div class="dashboard-card"><span class="card-icon"><?= moneycash_icon('target') ?></span><h3>Metas financeiras</h3><p>Acompanhe objetivos de economia e seus prazos.</p><div class="card-links"><a href="metas/cadastrar.php" class="btn-navegacao">+ Nova meta</a><a href="metas/listar.php" class="btn-navegacao">Ver metas</a></div></div>
    <div class="dashboard-card"><span class="card-icon"><?= moneycash_icon('pie-chart') ?></span><h3>Relatórios</h3><p>Analise o desempenho financeiro e encontre padrões.</p><div class="card-links"><a href="relatorios/relatorio_financeiro.php" class="btn-navegacao">Abrir relatório</a></div></div>
    <div class="dashboard-card"><span class="card-icon"><?= moneycash_icon('bell') ?></span><h3>Notificações</h3><p>Veja avisos e lembretes que precisam da sua atenção.</p><div class="card-links"><a href="notificacoes/notificacoes.php" class="btn-navegacao">Ver notificações</a></div></div>
    <div class="dashboard-card"><span class="card-icon"><?= moneycash_icon('users') ?></span><h3>Usuários</h3><p>Gerencie os usuários cadastrados no sistema.</p><div class="card-links"><a href="usuarios/listar.php" class="btn-navegacao">Gerenciar usuários</a></div></div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
