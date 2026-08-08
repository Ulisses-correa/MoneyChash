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
    $stmt = $pdo->query("SELECT id_categoria, nome, tipo FROM categorias ORDER BY nome");
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_categoria = trim($_POST['id_categoria'] ?? '');
    if (empty($id_categoria)) {
        $mensagem_erro = "Selecione uma categoria.";
    } else {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("DELETE FROM categorias WHERE id_categoria = :id");
            $stmt->execute([':id' => (int)$id_categoria]);
            $pdo->commit();
            $mensagem_sucesso = "Categoria excluída com sucesso!";
            $stmt = $pdo->query("SELECT id_categoria, nome, tipo FROM categorias ORDER BY nome");
            $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $pdo->rollBack();
            $mensagem_erro = "Erro ao excluir: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Excluir Categoria - FinControl</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
        <h1>FinControl - Gestão Financeira</h1>
        <a href="../index.php" class="btn-navegacao">Voltar para o Início</a>
    </header>
    <main class="container-formulario">
        <div class="form-box">
            <h2>Excluir Categoria</h2>
            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>
            <form action="excluir_categoria.php" method="POST">
                <div class="form-grupo">
                    <label for="id_categoria">Categoria</label>
                    <select id="id_categoria" name="id_categoria" required>
                        <option value="">Selecione</option>
                        <?php foreach ($categorias as $c): ?>
                            <option value="<?= $c['id_categoria'] ?>"><?= htmlspecialchars($c['nome']) ?> (<?= $c['tipo'] ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn-enviar">Excluir Categoria</button>
            </form>
        </div>
    </main>
</body>
</html>