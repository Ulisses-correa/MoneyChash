<?php
require __DIR__ . '/includes/config.php';

$total_usuarios = (int) $pdo->query("SELECT COUNT(*) FROM usuarios")->fetchColumn();
$total_receitas = (float) $pdo->query("SELECT COALESCE(SUM(valor), 0) FROM receitas")->fetchColumn();
$total_despesas = (float) $pdo->query("SELECT COALESCE(SUM(valor), 0) FROM despesas")->fetchColumn();
$saldo_geral    = $total_receitas - $total_despesas;

$base = '';
$page_title = 'Painel Inicial';
$active = 'inicio';
$main_class = 'container';
require __DIR__ . '/includes/header.php';
?>
        <p class="dashboard-intro">
            Bem-vindo ao MoneyCash. Acompanhe seu saldo, gerencie contas, receitas,
            despesas e metas, tudo em um só lugar.
        </p>

        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-label">Usuários cadastrados</div>
                <div class="stat-value"><?= $total_usuarios ?></div>
            </div>
            <div class="stat-card stat-success">
                <div class="stat-label">Total de receitas</div>
                <div class="stat-value"><?= formatar_moeda($total_receitas) ?></div>
            </div>
            <div class="stat-card stat-danger">
                <div class="stat-label">Total de despesas</div>
                <div class="stat-value"><?= formatar_moeda($total_despesas) ?></div>
            </div>
            <div class="stat-card <?= $saldo_geral >= 0 ? 'stat-success' : 'stat-danger' ?>">
                <div class="stat-label">Saldo geral</div>
                <div class="stat-value"><?= formatar_moeda($saldo_geral) ?></div>
            </div>
        </div>

        <h2 class="section-title">Módulos do sistema</h2>
        <div class="dashboard-grid">

            <div class="dashboard-card">
                <div class="card-icon">👤</div>
                <h3>Usuários</h3>
                <p>Cadastre e gerencie as pessoas que utilizam o sistema.</p>
                <div class="card-links">
                    <a href="usuarios/cadastrar.php" class="btn-navegacao">+ Cadastrar</a>
                    <a href="usuarios/listar.php" class="btn-navegacao">Listar</a>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="card-icon">🏷️</div>
                <h3>Categorias</h3>
                <p>Organize receitas e despesas por categoria.</p>
                <div class="card-links">
                    <a href="categorias/cadastrar.php" class="btn-navegacao">+ Cadastrar</a>
                    <a href="categorias/listar.php" class="btn-navegacao">Listar</a>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="card-icon">🏦</div>
                <h3>Contas</h3>
                <p>Gerencie contas correntes, poupança e carteiras.</p>
                <div class="card-links">
                    <a href="contas/cadastrar.php" class="btn-navegacao">+ Cadastrar</a>
                    <a href="contas/listar.php" class="btn-navegacao">Listar</a>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="card-icon">💵</div>
                <h3>Receitas</h3>
                <p>Registre salários, vendas e outras entradas de dinheiro.</p>
                <div class="card-links">
                    <a href="receitas/cadastrar.php" class="btn-navegacao">+ Cadastrar</a>
                    <a href="receitas/listar.php" class="btn-navegacao">Listar</a>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="card-icon">🧾</div>
                <h3>Despesas</h3>
                <p>Controle contas, compras e gastos do dia a dia.</p>
                <div class="card-links">
                    <a href="despesas/cadastrar.php" class="btn-navegacao">+ Cadastrar</a>
                    <a href="despesas/listar.php" class="btn-navegacao">Listar</a>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="card-icon">📊</div>
                <h3>Extrato</h3>
                <p>Veja o histórico completo de movimentações por conta.</p>
                <div class="card-links">
                    <a href="movimentacoes/extrato.php" class="btn-navegacao">Ver extrato</a>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="card-icon">🎯</div>
                <h3>Metas financeiras</h3>
                <p>Defina objetivos e acompanhe o progresso da economia.</p>
                <div class="card-links">
                    <a href="metas/cadastrar.php" class="btn-navegacao">+ Cadastrar</a>
                    <a href="metas/listar.php" class="btn-navegacao">Listar</a>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="card-icon">🔔</div>
                <h3>Notificações</h3>
                <p>Acompanhe alertas de vencimentos e metas.</p>
                <div class="card-links">
                    <a href="notificacoes/notificacoes.php" class="btn-navegacao">Ver notificações</a>
                </div>
            </div>

            <div class="dashboard-card">
                <div class="card-icon">📈</div>
                <h3>Relatórios</h3>
                <p>Visualize o resumo financeiro com gráficos.</p>
                <div class="card-links">
                    <a href="relatorios/relatorio_financeiro.php" class="btn-navegacao">Ver relatório</a>
                </div>
            </div>

        </div>
<?php require __DIR__ . '/includes/footer.php'; ?>
