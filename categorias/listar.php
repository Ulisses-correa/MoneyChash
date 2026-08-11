<?php
$mensagem_sucesso = "";
$mensagem_erro = "";
$host = 'localhost';
$dbname = 'gestao_financeira';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "SELECT id, nome, tipo FROM categorias ORDER BY tipo, nome";
    $stmt = $pdo->query($sql);
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensagem_erro = "Erro ao conectar: " . $e->getMessage();
}

// Excluir categoria
if (isset($_GET['excluir'])) {
    $id = (int)$_GET['excluir'];
    try {
        $sql = "DELETE FROM categorias WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);
        $mensagem_sucesso = "Categoria excluída com sucesso!";
        header("Refresh: 1; url=listar.php");
    } catch (PDOException $e) {
        $mensagem_erro = "Erro ao excluir: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categorias - MoneyChash</title>
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
                <a href="cadastrar.php" class="btn-navegacao">➕ Nova Categoria</a>
            </div>
        </div>
    </header>

    <main class="container-formulario">
        <a href="../index.php" class="btn-back">← Voltar</a>
        <h2 class="section-title">🏷️ Categorias Cadastradas</h2>
        
        <?php if (!empty($mensagem_sucesso)): ?>
            <div class="alerta alerta-sucesso">✅ <?= htmlspecialchars($mensagem_sucesso) ?></div>
        <?php endif; ?>
        <?php if (!empty($mensagem_erro)): ?>
            <div class="alerta alerta-erro">❌ <?= htmlspecialchars($mensagem_erro) ?></div>
        <?php endif; ?>

        <?php if (empty($categorias)): ?>
            <div class="no-results">
                <div class="no-results-icon">📦</div>
                <p>Nenhuma categoria cadastrada.</p>
                <a href="cadastrar.php" class="btn btn-primary" style="width: fit-content; margin-top: 20px;">Criar Primeira Categoria</a>
            </div>
        <?php else: ?>
            <div style="overflow-x: auto;">
                <table class="tabela">
                    <thead>
                        <tr>
                            <th>Nome</th>
                            <th>Tipo</th>
                            <th style="text-align: center;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categorias as $categoria): ?>
                            <tr>
                                <td><?= htmlspecialchars($categoria['nome']) ?></td>
                                <td>
                                    <?php if ($categoria['tipo'] === 'Receita'): ?>
                                        <span style="color: var(--success); font-weight: 600;">💸 Receita</span>
                                    <?php else: ?>
                                        <span style="color: var(--danger); font-weight: 600;">💳 Despesa</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="tabela-acoes" style="justify-content: center;">
                                        <a href="editar.php?id=<?= $categoria['id'] ?>" class="btn-editar">✏️ Editar</a>
                                        <a href="listar.php?excluir=<?= $categoria['id'] ?>" class="btn-excluir" onclick="return confirm('Tem certeza que deseja excluir?')">🗑️ Excluir</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </main>
</body>
</html>