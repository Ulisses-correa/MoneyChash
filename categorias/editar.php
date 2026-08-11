<?php
$mensagem_sucesso = "";
$mensagem_erro = "";
$host = 'localhost';
$dbname = 'gestao_financeira';
$username = 'root';
$password = '';

$id = $_GET['id'] ?? $_POST['id'] ?? '';
$nome = '';
$tipo = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    $mensagem_erro = "Erro ao conectar: " . $e->getMessage();
}

if (!empty($id)) {
    try {
        $stmt = $pdo->prepare("SELECT nome, tipo FROM categorias WHERE id = :id");
        $stmt->execute([':id' => (int)$id]);
        $cat = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($cat) {
            $nome = $cat['nome'];
            $tipo = $cat['tipo'];
        }
    } catch (PDOException $e) {
        $mensagem_erro = "Erro ao carregar dados: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = trim($_POST['id'] ?? '');
    $nome = trim($_POST['nome'] ?? '');
    $tipo = trim($_POST['tipo'] ?? '');

    if (empty($id) || empty($nome) || empty($tipo)) {
        $mensagem_erro = "Preencha todos os campos.";
    } else {
        try {
            $sql = "UPDATE categorias SET nome = :nome, tipo = :tipo WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':nome' => $nome, ':tipo' => $tipo, ':id' => (int)$id]);
            $mensagem_sucesso = "Categoria atualizada com sucesso!";
        } catch (PDOException $e) {
            $mensagem_erro = "Erro ao atualizar: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Categoria - MoneyChash</title>
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
        <a href="listar.php" class="btn-back">← Voltar</a>
        <div class="form-box">
            <h2>✏️ Editar Categoria</h2>
            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso">✅ <?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro">❌ <?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>
            <form action="editar.php" method="POST">
                <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                <div class="form-grupo">
                    <label for="nome">Nome da Categoria</label>
                    <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($nome) ?>" placeholder="Ex: Alimentação, Transporte..." required>
                </div>
                <div class="form-grupo">
                    <label for="tipo">Tipo de Categoria</label>
                    <select id="tipo" name="tipo" required>
                        <option value="">Selecione um tipo...</option>
                        <option value="Receita" <?= ($tipo == 'Receita' ? 'selected' : '') ?>>💸 Receita</option>
                        <option value="Despesa" <?= ($tipo == 'Despesa' ? 'selected' : '') ?>>💳 Despesa</option>
                    </select>
                </div>
                <button type="submit" class="btn-enviar">✅ Salvar Alterações</button>
            </form>
        </div>
    </main>
</body>
</html>