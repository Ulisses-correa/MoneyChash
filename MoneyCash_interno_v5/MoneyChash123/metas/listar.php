<?php
require __DIR__ . '/../includes/config.php';
exigir_login();

$mensagem_erro = '';
$metas = [];

try {
    $sql = "SELECT m.id_meta, m.descricao, m.valor_meta, m.valor_atual, m.data_inicio, m.data_limite, m.status,
                   u.nome AS usuario
            FROM metas m
            INNER JOIN usuarios u ON m.id_usuario = u.id_usuario
            ORDER BY m.status, m.data_limite";
    $metas = $pdo->query($sql)->fetchAll();
} catch (PDOException $e) {
    $mensagem_erro = 'Erro ao conectar: ' . $e->getMessage();
}

function selo_status_meta(string $status): string
{
    return match ($status) {
        'Concluída' => 'selo-sucesso',
        'Cancelada' => 'selo-despesa',
        default => 'selo-pendente',
    };
}

$base = '../';
$page_title = 'Metas Financeiras';
$active = 'metas';
require __DIR__ . '/../includes/header.php';
?>
        <div class="form-box">
            <a href="../painel.php" class="btn-voltar">Voltar para o início</a>
            <h2>Metas Cadastradas</h2>
            <a href="cadastrar.php" class="btn-navegacao">+ Nova Meta</a>

            <?php if (!empty($mensagem_erro)): ?>
                <div class="alerta alerta-erro"><?= htmlspecialchars($mensagem_erro) ?></div>
            <?php endif; ?>

            <?php if (!empty($metas)): ?>
                <table class="tabela">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Usuário</th>
                            <th>Descrição</th>
                            <th>Alvo</th>
                            <th>Acumulado</th>
                            <th>Início</th>
                            <th>Limite</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($metas as $m): ?>
                            <tr>
                                <td>#<?= (int) $m['id_meta'] ?></td>
                                <td><?= htmlspecialchars($m['usuario']) ?></td>
                                <td><?= htmlspecialchars($m['descricao']) ?></td>
                                <td><?= formatar_moeda($m['valor_meta']) ?></td>
                                <td><?= formatar_moeda($m['valor_atual']) ?></td>
                                <td><?= formatar_data($m['data_inicio']) ?></td>
                                <td><?= formatar_data($m['data_limite']) ?></td>
                                <td><span class="selo <?= selo_status_meta($m['status']) ?>"><?= htmlspecialchars($m['status']) ?></span></td>
                                <td>
                                    <a href="editar.php?id_meta=<?= (int) $m['id_meta'] ?>" class="btn-navegacao">Editar</a>
                                    <a href="excluir.php?id_meta=<?= (int) $m['id_meta'] ?>" class="btn-navegacao btn-perigo">Excluir</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p class="form-vazio">Nenhuma meta cadastrada.</p>
            <?php endif; ?>
        </div>
<?php require __DIR__ . '/../includes/footer.php'; ?>
