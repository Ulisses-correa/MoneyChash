<?php
$mensagem_sucesso = "";
$mensagem_erro = "";
$host = 'localhost';
$dbname = 'gestao_financeira';
$username = 'root';
$password = '';

$id_categoria = $_GET['id_categoria'] ?? $_POST['id_categoria'] ?? '';
$nome = '';
$tipo = '';
$categorias = [];

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->query("SELECT id_categoria, nome, tipo FROM categorias ORDER BY nome");
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $mensagem_erro = "Erro ao conectar: " . $e->getMessage();
}

if (!empty($id_categoria)) {
    try {
        $stmt = $pdo->prepare("SELECT nome, tipo FROM categorias WHERE id_categoria = :id");
        $stmt->execute([':id' => (int)$id_categoria]);
        $cat = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($cat) {
            $nome = $cat['nome'];
            $tipo = $cat['tipo'];
        }
    } catch (PDOException $e) {
        $mensagem_erro = "Erro ao carregar dados: " . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salvar'])) {
    $id_categoria = trim($_POST['id_categoria'] ?? '');
    $nome = trim($_POST['nome'] ?? '');
    $tipo = trim($_POST['tipo'] ?? '');

    if (empty($id_categoria) || empty($nome) || empty($tipo)) {
        $mensagem_erro = "Preencha todos os campos.";
    } else {
        try {
            $sql = "UPDATE categorias SET nome = :nome, tipo = :tipo WHERE id_categoria = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([':nome' => $nome, ':tipo' => $tipo, ':id' => (int)$id_categoria]);
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
    <title>Editar Categoria - FinControl</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
        <h1>FinControl - Gestão Financeira</h1>
        <a href="../index.php" class="btn-navegacao">Voltar para o Início</a>
    </header>
    <main class="container-formulario">
        <div class="form-box">
            <h2>Editar Categoria</h2>
            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>
            <form action="editar_categoria.php" method="GET">
                <div class="form-grupo">
                    <label for="id_categoria">Selecionar categoria</label>
                    <select id="id_categoria" name="id_categoria" onchange="this.form.submit()">
                        <option value="">Selecione</option>
                        <?php foreach ($categorias as $c): ?>
                            <option value="<?= $c['id_categoria'] ?>" <?= ($c['id_categoria'] == $id_categoria ? 'selected' : '') ?>>
                                <?= htmlspecialchars($c['nome']) ?> (<?= $c['tipo'] ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>
            <?php if (!empty($id_categoria) && !empty($nome)): ?>
                <form action="editar_categoria.php" method="POST">
                    <input type="hidden" name="id_categoria" value="<?= htmlspecialchars($id_categoria) ?>">
                    <div class="form-grupo">
                        <label for="nome">Nome</label>
                        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($nome) ?>" required>
                    </div>
                    <div class="form-grupo">
                        <label for="tipo">Tipo</label>
                        <select id="tipo" name="tipo" required>
                            <option value="Receita" <?= ($tipo == 'Receita' ? 'selected' : '') ?>>Receita</option>
                            <option value="Despesa" <?= ($tipo == 'Despesa' ? 'selected' : '') ?>>Despesa</option>
                        </select>
                    </div>
                    <button type="submit" name="salvar" class="btn-enviar">Salvar Alterações</button>
                </form>
            <?php endif; ?>
        </div>
    </main>
</body>
</html>