<?php
require __DIR__ . '/../includes/config.php';

$mensagem_sucesso = '';
$mensagem_erro = '';

if (isset($_GET['marcar_lida']) && is_numeric($_GET['marcar_lida'])) {
    try {
        $stmt = $pdo->prepare("UPDATE notificacoes SET lida = 1 WHERE id_notificacao = :id");
        $stmt->execute([':id' => (int) $_GET['marcar_lida']]);
        $mensagem_sucesso = 'Notificação marcada como lida.';
    } catch (PDOException $e) {
        $mensagem_erro = 'Erro ao atualizar: ' . $e->getMessage();
    }
}

try {
    $sql = "SELECT n.id_notificacao, n.mensagem, n.data_envio, n.tipo, n.lida, u.nome AS usuario
            FROM notificacoes n
            INNER JOIN usuarios u ON n.id_usuario = u.id_usuario
            ORDER BY n.lida ASC, n.data_envio DESC";
    $notificacoes = $pdo->query($sql)->fetchAll();
} catch (PDOException $e) {
    $mensagem_erro = 'Erro ao carregar notificações: ' . $e->getMessage();
    $notificacoes = [];
}

$base = '../';
$page_title = 'Notificações';
$active = 'notificacoes';
require __DIR__ . '/../includes/header.php';
?>
        <div class="form-box">
            <a href="../index.php" class="btn-voltar">Voltar para o início</a>
            <h2>Notificações</h2>

            <?php if (!empty($mensagem_sucesso)): ?>
                <div class="alerta alerta-sucesso"><?= htmlspecialchars($mensagem_sucesso) ?></div>
            <?php endif; ?>
            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>

            <?php if (!empty($notificacoes)): ?>
                <table class="tabela">
                    <thead>
                        <tr>
                            <th>Usuário</th>
                            <th>Mensagem</th>
                            <th>Tipo</th>
                            <th>Data</th>
                            <th>Status</th>
                            <th>Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($notificacoes as $n): ?>
                            <tr>
                                <td><?= htmlspecialchars($n['usuario']) ?></td>
                                <td><?= htmlspecialchars($n['mensagem']) ?></td>
                                <td><?= htmlspecialchars($n['tipo']) ?></td>
                                <td><?= formatar_data($n['data_envio']) ?></td>
                                <td>
                                    <span class="selo <?= $n['lida'] ? 'selo-neutro' : 'selo-pendente' ?>">
                                        <?= $n['lida'] ? 'Lida' : 'Não lida' ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if (!$n['lida']): ?>
                                        <a href="notificacoes.php?marcar_lida=<?= (int) $n['id_notificacao'] ?>" class="btn-navegacao">Marcar como lida</a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="form-vazio">Nenhuma notificação encontrada.</p>
            <?php endif; ?>
        </div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
