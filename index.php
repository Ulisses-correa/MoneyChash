<?php ?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MoneyChash - Gestão Financeira Pessoal</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <header>
        <div class="header-content">
            <div>
                <h1>💰 MoneyChash</h1>
                <p style="font-size: 14px; opacity: 0.9; margin-top: 4px;">Sua Gestão Financeira Simplificada</p>
            </div>
        </div>
    </header>

    <main class="container">
        <!-- Bem-vindo -->
        <div style="background: linear-gradient(135deg, rgba(37, 99, 235, 0.1) 0%, rgba(16, 185, 129, 0.1) 100%); padding: 24px; border-radius: 12px; margin-bottom: 40px; border-left: 4px solid #2563eb;">
            <h2 style="color: var(--primary); margin-bottom: 8px;">🎉 Bem-vindo ao MoneyChash</h2>
            <p style="color: var(--text-secondary); line-height: 1.6;">
                Organize suas finanças de forma simples e profissional. Controle suas receitas, despesas, contas e metas tudo em um único lugar.
            </p>
        </div>

        <!-- Seção de Usuários -->
        <div>
            <h2 class="section-title">👥 Usuários</h2>
            <p class="section-subtitle">Gerenciamento de contas de usuários</p>
            <div class="cards-grid">
                <div class="card">
                    <div class="card-header">
                        <h3>➕ Novo Usuário</h3>
                        <p>Cadastrar usuário</p>
                    </div>
                    <div class="card-body">
                        <p>Crie uma nova conta de usuário para começar a gerenciar suas finanças.</p>
                        <div class="card-actions">
                            <a href="usuarios/excluir.php" class="btn btn-primary">Cadastrar Usuário</a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>📋 Listar Usuários</h3>
                        <p>Ver todos os usuários</p>
                    </div>
                    <div class="card-body">
                        <p>Visualize uma lista completa de todos os usuários cadastrados no sistema.</p>
                        <div class="card-actions">
                            <a href="usuarios/listar_usuarios.php" class="btn btn-primary">Ver Lista</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção de Categorias -->
        <div class="mt-30">
            <h2 class="section-title">🏷️ Categorias</h2>
            <p class="section-subtitle">Organize suas receitas e despesas</p>
            <div class="cards-grid">
                <div class="card">
                    <div class="card-header">
                        <h3>➕ Nova Categoria</h3>
                        <p>Adicionar categoria</p>
                    </div>
                    <div class="card-body">
                        <p>Crie novas categorias para organizar melhor suas receitas e despesas.</p>
                        <div class="card-actions">
                            <a href="categorias/cadastrar.php" class="btn btn-primary">Criar Categoria</a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>📋 Ver Categorias</h3>
                        <p>Lista de categorias</p>
                    </div>
                    <div class="card-body">
                        <p>Veja todas as categorias já criadas e gerencie-as conforme necessário.</p>
                        <div class="card-actions">
                            <a href="categorias/listar.php" class="btn btn-primary">Ver Categorias</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção de Contas -->
        <div class="mt-30">
            <h2 class="section-title">🏦 Contas Bancárias</h2>
            <p class="section-subtitle">Gerencie suas contas bancárias</p>
            <div class="cards-grid">
                <div class="card">
                    <div class="card-header">
                        <h3>➕ Nova Conta</h3>
                        <p>Adicionar conta</p>
                    </div>
                    <div class="card-body">
                        <p>Registre uma nova conta bancária ou cartão de crédito.</p>
                        <div class="card-actions">
                            <a href="contas/cadastrar.php" class="btn btn-primary">Criar Conta</a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>📋 Minhas Contas</h3>
                        <p>Ver todas as contas</p>
                    </div>
                    <div class="card-body">
                        <p>Visualize todas as suas contas e seu saldo atual.</p>
                        <div class="card-actions">
                            <a href="contas/listar.php" class="btn btn-primary">Ver Contas</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção de Receitas -->
        <div class="mt-30">
            <h2 class="section-title">💸 Receitas</h2>
            <p class="section-subtitle">Controle suas entradas financeiras</p>
            <div class="cards-grid">
                <div class="card">
                    <div class="card-header">
                        <h3>➕ Nova Receita</h3>
                        <p>Registrar receita</p>
                    </div>
                    <div class="card-body">
                        <p>Adicione uma nova fonte de renda ou receita.</p>
                        <div class="card-actions">
                            <a href="receitas/cadastrar.php" class="btn btn-secondary">Registrar Receita</a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>📋 Minhas Receitas</h3>
                        <p>Ver todas as receitas</p>
                    </div>
                    <div class="card-body">
                        <p>Consulte o histórico de todas as suas receitas registradas.</p>
                        <div class="card-actions">
                            <a href="receitas/listar.php" class="btn btn-secondary">Ver Receitas</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção de Despesas -->
        <div class="mt-30">
            <h2 class="section-title">💳 Despesas</h2>
            <p class="section-subtitle">Monitore seus gastos</p>
            <div class="cards-grid">
                <div class="card">
                    <div class="card-header">
                        <h3>➕ Nova Despesa</h3>
                        <p>Registrar despesa</p>
                    </div>
                    <div class="card-body">
                        <p>Registre um novo gasto ou despesa.</p>
                        <div class="card-actions">
                            <a href="sozinhos/despesas.php" class="btn btn-danger">Registrar Despesa</a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>📊 Ver Despesas</h3>
                        <p>Histórico de despesas</p>
                    </div>
                    <div class="card-body">
                        <p>Veja todas as despesas registradas e analise seus padrões de gastos.</p>
                        <div class="card-actions">
                            <a href="sozinhos/despesas.php" class="btn btn-danger">Ver Despesas</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção de Metas Financeiras -->
        <div class="mt-30">
            <h2 class="section-title">🎯 Metas Financeiras</h2>
            <p class="section-subtitle">Estabeleça e acompanhe suas metas</p>
            <div class="cards-grid">
                <div class="card">
                    <div class="card-header">
                        <h3>➕ Nova Meta</h3>
                        <p>Criar meta financeira</p>
                    </div>
                    <div class="card-body">
                        <p>Estabeleça uma nova meta financeira e acompanhe seu progresso.</p>
                        <div class="card-actions">
                            <a href="sozinhos/metas.php" class="btn btn-primary">Criar Meta</a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>🎯 Minhas Metas</h3>
                        <p>Ver todas as metas</p>
                    </div>
                    <div class="card-body">
                        <p>Visualize todas as suas metas e o progresso de cada uma.</p>
                        <div class="card-actions">
                            <a href="sozinhos/listar_metas.php" class="btn btn-primary">Ver Metas</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Seção de Movimentações -->
        <div class="mt-30">
            <h2 class="section-title">📊 Movimentações & Relatórios</h2>
            <p class="section-subtitle">Acompanhe suas transações e analise dados</p>
            <div class="cards-grid">
                <div class="card">
                    <div class="card-header">
                        <h3>📋 Extrato</h3>
                        <p>Ver movimentações</p>
                    </div>
                    <div class="card-body">
                        <p>Visualize o extrato detalhado de todas as suas movimentações.</p>
                        <div class="card-actions">
                            <a href="sozinhos/extrato.php" class="btn btn-primary">Ver Extrato</a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>📊 Relatório Financeiro</h3>
                        <p>Análise detalhada</p>
                    </div>
                    <div class="card-body">
                        <p>Gere relatórios completos sobre sua situação financeira.</p>
                        <div class="card-actions">
                            <a href="sozinhos/relatorio_financeiro.php" class="btn btn-primary">Ver Relatório</a>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <h3>🔔 Notificações</h3>
                        <p>Seus alertas</p>
                    </div>
                    <div class="card-body">
                        <p>Verifique notificações importantes sobre sua situação financeira.</p>
                        <div class="card-actions">
                            <a href="sozinhos/notificações.php" class="btn btn-primary">Ver Alertas</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div style="text-align: center; margin-top: 60px; padding-top: 30px; border-top: 1px solid var(--border-color); color: var(--text-secondary); font-size: 14px;">
            <p>💰 <strong>MoneyChash v2.0</strong> - Sua Gestão Financeira Simplificada</p>
            <p style="margin-top: 8px;">Design Moderno & Profissional | © 2024</p>
        </div>
    </main>
</body>
</html>