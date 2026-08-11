<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview - MoneyChash</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            background: #f3f4f6;
            padding: 20px;
            color: #1f2937;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        h1 {
            text-align: center;
            margin-bottom: 30px;
            color: #2563eb;
        }
        
        .preview-section {
            background: white;
            border-radius: 12px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .preview-section h2 {
            margin-bottom: 20px;
            color: #1f2937;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 10px;
            display: inline-block;
        }
        
        .colors {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .color-box {
            border-radius: 8px;
            padding: 20px;
            color: white;
            text-align: center;
            font-weight: 600;
        }
        
        .color-primary {
            background: #2563eb;
        }
        
        .color-success {
            background: #10b981;
        }
        
        .color-danger {
            background: #ef4444;
        }
        
        .color-warning {
            background: #f59e0b;
        }
        
        .feature-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-top: 20px;
        }
        
        .feature {
            padding: 20px;
            border-radius: 8px;
            background: linear-gradient(135deg, #f0f9ff 0%, #f0fdf4 100%);
            border-left: 4px solid #2563eb;
        }
        
        .feature h3 {
            margin-bottom: 10px;
            color: #2563eb;
        }
        
        .feature p {
            color: #6b7280;
            font-size: 14px;
        }
        
        code {
            background: #f3f4f6;
            padding: 10px 15px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            display: block;
            margin: 10px 0;
            overflow-x: auto;
        }
        
        .button-showcase {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
            margin-top: 20px;
        }
        
        button {
            padding: 10px 20px;
            border-radius: 6px;
            border: none;
            cursor: pointer;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .btn-primary {
            background: #2563eb;
            color: white;
        }
        
        .btn-primary:hover {
            background: #1e40af;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(37, 99, 235, 0.4);
        }
        
        .btn-secondary {
            background: #10b981;
            color: white;
        }
        
        .btn-secondary:hover {
            background: #059669;
        }
        
        .btn-danger {
            background: #ef4444;
            color: white;
        }
        
        .btn-danger:hover {
            background: #dc2626;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🎨 Preview do Novo Design - MoneyChash</h1>
        
        <!-- Cores -->
        <div class="preview-section">
            <h2>🎯 Paleta de Cores</h2>
            <div class="colors">
                <div class="color-box color-primary">
                    Primária<br>#2563eb
                </div>
                <div class="color-box" style="background: #1e40af;">
                    Primária Dark<br>#1e40af
                </div>
                <div class="color-box color-success">
                    Sucesso<br>#10b981
                </div>
                <div class="color-box color-danger">
                    Perigo<br>#ef4444
                </div>
                <div class="color-box color-warning">
                    Aviso<br>#f59e0b
                </div>
                <div class="color-box" style="background: #0ea5e9;">
                    Info<br>#0ea5e9
                </div>
            </div>
        </div>
        
        <!-- Recursos -->
        <div class="preview-section">
            <h2>✨ Recursos Principais</h2>
            <div class="feature-grid">
                <div class="feature">
                    <h3>📱 Design Responsivo</h3>
                    <p>Funciona perfeitamente em desktop, tablet e dispositivos móveis com layout adaptativo.</p>
                </div>
                <div class="feature">
                    <h3>🎨 Interface Moderna</h3>
                    <p>Cores modernas, cards com gradient, animações suaves e design profissional.</p>
                </div>
                <div class="feature">
                    <h3>⚡ Performance</h3>
                    <p>CSS otimizado, sem frameworks externos, carregamento rápido e eficiente.</p>
                </div>
                <div class="feature">
                    <h3>♿ Acessibilidade</h3>
                    <p>Semântica HTML correta, contraste adequado e navegação por teclado.</p>
                </div>
                <div class="feature">
                    <h3>🔔 Alertas Visuais</h3>
                    <p>Sistema de alertas com emojis, sucesso, erro, aviso e informação.</p>
                </div>
                <div class="feature">
                    <h3>📊 Tabelas Modernas</h3>
                    <p>Tabelas com hover effects, linhas alternadas e ações contextuais.</p>
                </div>
            </div>
        </div>
        
        <!-- Botões -->
        <div class="preview-section">
            <h2>🔘 Estilos de Botões</h2>
            <div class="button-showcase">
                <button class="btn-primary">✅ Primário</button>
                <button class="btn-secondary">✅ Secundário</button>
                <button class="btn-danger">❌ Perigo</button>
            </div>
        </div>
        
        <!-- Configuração -->
        <div class="preview-section">
            <h2>⚙️ Configuração</h2>
            <p>Para começar, atualize o banco de dados com as seguintes credenciais:</p>
            <code>
$host = 'localhost';<br>
$dbname = 'gestao_financeira';<br>
$username = 'root';<br>
$password = '';
            </code>
        </div>
        
        <!-- Estrutura -->
        <div class="preview-section">
            <h2>📁 Estrutura de Arquivos</h2>
            <code style="text-align: left;">
MoneyChash/<br>
├── index.php<br>
├── style.css<br>
├── README.md<br>
├── includes/header.php<br>
├── categorias/<br>
├── contas/<br>
├── receitas/<br>
├── usuarios/<br>
└── sozinhos/
            </code>
        </div>
        
        <!-- Links -->
        <div class="preview-section">
            <h2>🔗 Navegação Rápida</h2>
            <p><a href="index.php" style="color: #2563eb; text-decoration: none; font-weight: 600;">🏠 Voltar ao Dashboard</a></p>
        </div>
    </div>
</body>
</html>
