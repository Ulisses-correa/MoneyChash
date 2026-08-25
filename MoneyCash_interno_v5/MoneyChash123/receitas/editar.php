<?php
require __DIR__ . '/../includes/config.php';
exigir_login();

$mensagem_sucesso = '';
$mensagem_erro = '';

$id_receita = $_GET['id_receita'] ?? $_POST['id_receita'] ?? '';
$descricao = $valor = $data_receita = $id_usuario = $id_categoria = '';
$receitas = $usuarios = $categorias = [];

try {
    $receitas = $pdo->query("SELECT id_receita, descricao, valor FROM receitas ORDER BY data_receita DESC")->fetchAll();
    $usuarios = $pdo->query("SELECT id_usuario, nome FROM usuarios ORDER BY nome")->fetchAll();
    $categorias = $pdo->query("SELECT id_categoria, nome FROM categorias WHERE tipo = 'Receita' ORDER BY nome")->fetchAll();
} catch (PDOException $e) {
    $mensagem_erro = 'Erro ao conectar: ' . $e->getMessage();
}

if (!empty($id_receita)) {
    try {
        $stmt = $pdo->prepare("SELECT descricao, valor, data_receita, id_usuario, id_categoria FROM receitas WHERE id_receita = :id");
        $stmt->execute([':id' => (int) $id_receita]);
        $receita = $stmt->fetch();
        if ($receita) {
            $descricao = $receita['descricao'];
            $valor = $receita['valor'];
            $data_receita = $receita['data_receita'];
            $id_usuario = $receita['id_usuario'];
            $id_categoria = $receita['id_categoria'];
        }
    } catch (PDOException $e) {
        $mensagem_erro = 'Erro ao carregar dados: ' . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salvar'])) {
    $id_receita = trim($_POST['id_receita'] ?? '');
    $id_usuario = trim($_POST['id_usuario'] ?? '');
    $id_categoria = trim($_POST['id_categoria'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $valor = trim($_POST['valor'] ?? '');
    $data_receita = trim($_POST['data_receita'] ?? '');

    if (empty($id_receita) || empty($id_usuario) || empty($id_categoria) || empty($valor) || empty($data_receita)) {
        $mensagem_erro = 'Preencha todos os campos obrigatórios.';
    } else {
        try {
            $sql = "UPDATE receitas SET id_usuario = :id_usuario, id_categoria = :id_categoria,
                    descricao = :descricao, valor = :valor, data_receita = :data_receita WHERE id_receita = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ':id_usuario' => (int) $id_usuario,
                ':id_categoria' => (int) $id_categoria,
                ':descricao' => $descricao,
                ':valor' => (float) $valor,
                ':data_receita' => $data_receita,
                ':id' => (int) $id_receita,
            ]);
            $mensagem_sucesso = 'Receita atualizada com sucesso!';
        } catch (PDOException $e) {
            $mensagem_erro = 'Erro ao atualizar: ' . $e->getMessage();
        }
    }
}

$base = '../';
$page_title = 'Editar Receita';
$active = 'receitas';
require __DIR__ . '/../includes/header.php';
?>
        <div class="form-box">
            <a href="../painel.php" class="btn-voltar">Voltar para o início</a>
            <h2>Editar Receita</h2>

            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>

            <form action="editar.php" method="GET">
                <div class="form-grupo">
                    <label for="id_receita">Selecionar receita</label>
                    <select id="id_receita" name="id_receita" onchange="this.form.submit()">
                        <option value="">Selecione</option>
                        <?php foreach ($receitas as $r): ?>
                            <option value="<?= (int) $r['id_receita'] ?>" <?= ($r['id_receita'] == $id_receita ? 'selected' : '') ?>>
                                <?= htmlspecialchars($r['descricao']) ?> — <?= formatar_moeda($r['valor']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </form>

            <?php if (!empty($id_receita) && !empty($descricao)): ?>
                <hr>
                <form action="editar.php" method="POST">
                    <input type="hidden" name="id_receita" value="<?= htmlspecialchars((string) $id_receita) ?>">
                    <div class="form-linha">
                        <div class="form-grupo">
                            <label for="id_usuario">Usuário</label>
                            <select id="id_usuario" name="id_usuario" required>
                                <?php foreach ($usuarios as $u): ?>
                                    <option value="<?= (int) $u['id_usuario'] ?>" <?= ($u['id_usuario'] == $id_usuario ? 'selected' : '') ?>><?= htmlspecialchars($u['nome']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-grupo">
                            <label for="id_categoria">Categoria</label>
                            <select id="id_categoria" name="id_categoria" required>
                                <?php foreach ($categorias as $c): ?>
                                    <option value="<?= (int) $c['id_categoria'] ?>" <?= ($c['id_categoria'] == $id_categoria ? 'selected' : '') ?>><?= htmlspecialchars($c['nome']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-grupo">
                        <label for="descricao">Descrição</label>
                        <input type="text" id="descricao" name="descricao" value="<?= htmlspecialchars($descricao) ?>">
                    </div>
                    <div class="form-linha">
                        <div class="form-grupo">
                            <label for="valor">Valor (R$)</label>
                            <input type="number" step="0.01" id="valor" name="valor" value="<?= htmlspecialchars((string) $valor) ?>" required>
                        </div>
                        <div class="form-grupo">
                            <label for="data_receita">Data</label>
                            <input type="date" id="data_receita" name="data_receita" value="<?= htmlspecialchars($data_receita) ?>" required>
                        </div>
                    </div>
                    <button type="submit" name="salvar" class="btn-enviar">Salvar Alterações</button>
                </form>
            <?php endif; ?>
        </div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
