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
    $stmt = $pdo->query("SELECT id_usuario, nome, email FROM usuarios ORDER BY nome");
    $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro: " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_usuario = trim($_POST['id_usuario'] ?? '');
    if (empty($id_usuario)) {
        $mensagem_erro = "Selecione um usuário.";
    } else {
        try {
            $pdo->beginTransaction();
            $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id_usuario = :id");
            $stmt->execute([':id' => (int)$id_usuario]);
            $pdo->commit();
            $mensagem_sucesso = "Usuário excluído com sucesso!";
            // Recarregar lista
            $stmt = $pdo->query("SELECT id_usuario, nome, email FROM usuarios ORDER BY nome");
            $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Excluir Usuário - FinControl</title>
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <header>
        <h1>FinControl - Gestão Financeira</h1>
        <a href="../index.php" class="btn-navegacao">Voltar para o Início</a>
    </header>
    <main class="container-formulario">
        <div class="form-box">
            <h2>Excluir Usuário</h2>
            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>
            <form action="excluir_usuario.php" method="POST">
                <div class="form-grupo">
                    <label for="id_usuario">Usuário</label>
                    <select id="id_usuario" name="id_usuario" required>
                        <option value="">Selecione</option>
                        <?php foreach ($usuarios as $u): ?>
                            <option value="<?= $u['id_usuario'] ?>"><?= htmlspecialchars($u['nome']) ?> - <?= htmlspecialchars($u['email']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn-enviar">Excluir Usuário</button>
            </form>
        </div>
    </main>
</body>
</html>