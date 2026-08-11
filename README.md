# 💰 MoneyChash - Gestão Financeira Pessoal

## 🎨 Design Moderno & Profissional

Bem-vindo ao MoneyChash, um sistema completo de gestão financeira pessoal com interface moderna, responsiva e profissional.

### ✨ Características Principais

- **Dashboard Intuitivo**: Interface com cards organizados por módulo
- **Design Responsivo**: Funciona perfeitamente em desktop, tablet e mobile
- **Cores Profissionais**: Paleta de cores moderna e agradável aos olhos
- **Navegação Fluida**: Menus intuitivos e fáceis de usar
- **Gestão Completa**: Controle de categorias, contas, receitas, despesas, metas e muito mais

### 🎯 Módulos Disponíveis

#### 👥 Usuários
- Cadastrar, listar, editar e excluir usuários
- Gerenciamento de contas de acesso

#### 🏷️ Categorias
- Organizar receitas e despesas por categoria
- Categorias específicas para cada tipo de transação

#### 🏦 Contas Bancárias
- Gerenciar múltiplas contas
- Tipos: Conta Corrente, Poupança, Cartão de Crédito, Carteira
- Rastreamento de saldos

#### 💸 Receitas
- Registrar entradas financeiras
- Classificar por categoria
- Acompanhar histórico completo

#### 💳 Despesas
- Registrar gastos e saídas
- Status de pagamento (Pendente/Pago)
- Categorização automática

#### 🎯 Metas Financeiras
- Estabelecer metas de poupança
- Acompanhar progresso
- Visualizar quanto falta para atingir

#### 📊 Movimentações & Relatórios
- Extrato detalhado de todas as transações
- Relatórios financeiros completos
- Análise de despesas e receitas
- Notificações de eventos importantes

### 🎨 Paleta de Cores

```
Primária: #2563eb (Azul)
Primária Escura: #1e40af (Azul Escuro)
Sucesso: #10b981 (Verde)
Perigo: #ef4444 (Vermelho)
Aviso: #f59e0b (Amarelo)
Info: #0ea5e9 (Ciano)
```

### 📱 Componentes UI

#### Cards
- Cards com gradient header
- Hover effects suave com elevação
- Responsivos e flexíveis

#### Formulários
- Inputs com estilo moderno
- Labels claros
- Validação visual
- Alerts de sucesso/erro com emojis

#### Tabelas
- Design limpo e profissional
- Linhas alternadas para melhor legibilidade
- Ações (Editar, Excluir) com ícones
- Hover effects nas linhas

#### Botões
- Primário (Azul)
- Secundário (Verde)
- Perigo (Vermelho)
- Estados: Normal, Hover, Focus

### 🚀 Como Usar

1. **Acesse o Sistema**: Abra `index.php` no seu navegador
2. **Dashboard Principal**: Visualize todos os módulos organizados em cards
3. **Escolha uma Ação**: Clique em qualquer card para navegar ao módulo
4. **Preencha Formulários**: Use os formulários intuitivos para adicionar dados
5. **Visualize Dados**: Acesse as páginas de listagem para ver todos os registros
6. **Gerencie**: Edite ou exclua registros conforme necessário

### 💾 Banco de Dados

Sistema utiliza MySQL com as seguintes tabelas:
- `usuarios` - Usuários do sistema
- `categorias` - Categorias de transações
- `contas` - Contas bancárias
- `receitas` - Registros de receitas
- `despesas` - Registros de despesas
- `metas` - Metas financeiras
- `notificacoes` - Notificações do sistema

### 📝 Estrutura de Pastas

```
MoneyChash/
├── index.php                    # Dashboard principal
├── style.css                    # Estilos globais
├── includes/
│   └── header.php              # Template de header
├── categorias/
│   ├── cadastrar.php
│   ├── listar.php
│   ├── editar.php
│   └── ecluir.php
├── contas/
│   ├── cadastrar.php
│   ├── listar.php
│   ├── editar.php
│   └── excluir.php
├── receitas/
│   ├── cadastrar.php
│   ├── listar.php
│   ├── editar.php
│   └── excluir.php
├── usuarios/
│   ├── listar_usuarios.php
│   └── excluir.php
└── sozinhos/
    ├── despesas.php
    ├── metas.php
    ├── listar_metas.php
    ├── extrato.php
    ├── notificações.php
    └── relatorio_financeiro.php
```

### 🔧 Configuração

1. Configure as credenciais do banco de dados em cada arquivo PHP:
```php
$host = 'localhost';
$dbname = 'gestao_financeira';
$username = 'root';
$password = '';
```

2. Certifique-se de que o banco de dados está criado
3. Execute os scripts de criação das tabelas
4. Acesse o sistema através do seu servidor web

### 🎯 Melhorias Recentes

✅ Redesign completo da interface
✅ Novo sistema de cores moderno e profissional
✅ Cards responsivos com gradients
✅ Emojis em todas as ações e títulos
✅ Melhorado layout do dashboard
✅ Animações suaves e hover effects
✅ Design mobile-first
✅ Sistema de alertas melhorado
✅ Tabelas com novo layout
✅ Botões modernos com estados visuais

### 📧 Suporte

Para dúvidas ou sugestões, entre em contato com o administrador do sistema.

---

**MoneyChash** - Sua gestão financeira simplificada! 💰
