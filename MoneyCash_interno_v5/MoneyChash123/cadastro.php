<?php
require __DIR__ . '/includes/config.php';

if (usuario_logado()) {
    header('Location: painel.php');
    exit;
}

$erro = '';
$nome = '';
$email = '';
$telefone = '';
$data_nascimento = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $data_nascimento = trim($_POST['data_nascimento'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $confirmacao = $_POST['confirmacao'] ?? '';

    if ($nome === '' || $email === '' || $senha === '') {
        $erro = 'Preencha nome, e-mail e senha.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erro = 'Informe um e-mail válido.';
    } elseif (strlen($senha) < 6) {
        $erro = 'A senha precisa ter pelo menos 6 caracteres.';
    } elseif ($senha !== $confirmacao) {
        $erro = 'As senhas não conferem.';
    } else {
        try {
            $check = $pdo->prepare('SELECT id_usuario FROM usuarios WHERE email = :email LIMIT 1');
            $check->execute([':email' => $email]);

            if ($check->fetch()) {
                $erro = 'Já existe uma conta com esse e-mail.';
            } else {
                $stmt = $pdo->prepare(
                    'INSERT INTO usuarios (nome, email, telefone, data_nascimento, senha)
                     VALUES (:nome, :email, :telefone, :data_nascimento, :senha)'
                );
                $stmt->execute([
                    ':nome' => $nome,
                    ':email' => $email,
                    ':telefone' => $telefone,
                    ':data_nascimento' => $data_nascimento ?: null,
                    ':senha' => password_hash($senha, PASSWORD_DEFAULT),
                ]);

                $id = (int) $pdo->lastInsertId();
                $stmt = $pdo->prepare('SELECT id_usuario, nome, email, senha FROM usuarios WHERE id_usuario = :id');
                $stmt->execute([':id' => $id]);
                iniciar_sessao_usuario($stmt->fetch());

                header('Location: painel.php');
                exit;
            }
        } catch (PDOException $e) {
            $erro = 'Não foi possível criar sua conta. Verifique se a tabela usuarios possui os campos necessários.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar conta · MoneyCash</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=20260821">
    <style>

/* Fallback embutido das telas de autenticação: funciona mesmo se o style.css externo não carregar. */
.auth-page, .auth-page * { box-sizing: border-box; }
.auth-page { margin:0; min-height:100vh; font-family:Inter,Segoe UI,Arial,sans-serif; color:#18201c; background:#f4f7f5; }
.auth-layout { min-height:100vh; display:grid; grid-template-columns:minmax(360px,.9fr) minmax(520px,1.1fr); }
.auth-brand-panel { position:relative; display:flex; flex-direction:column; justify-content:space-between; padding:48px clamp(32px,6vw,88px); color:#fff; overflow:hidden; background:radial-gradient(circle at 85% 20%,rgba(72,207,139,.24),transparent 32%),linear-gradient(145deg,#061c14 0%,#0d3929 52%,#176344 100%); }
.auth-brand-panel:after { content:""; position:absolute; width:430px; height:430px; right:-210px; bottom:-210px; border:1px solid rgba(255,255,255,.12); border-radius:50%; box-shadow:0 0 0 70px rgba(255,255,255,.025),0 0 0 140px rgba(255,255,255,.018); }
.auth-brand { position:relative; z-index:1; display:flex; align-items:center; gap:10px; font-size:1.08rem; font-weight:800; letter-spacing:-.02em; }
.auth-brand .brand-mark { display:grid; place-items:center; width:36px; height:36px; border-radius:10px; overflow:hidden; background:rgba(255,255,255,.08); border:1px solid rgba(255,255,255,.16); } .auth-brand .brand-mark img{display:block;width:100%;height:100%;}
.auth-brand-content { position:relative; z-index:1; max-width:520px; }
.auth-kicker { display:inline-block; margin-bottom:16px; font-size:.72rem; font-weight:800; text-transform:uppercase; letter-spacing:.14em; color:rgba(255,255,255,.58); }
.auth-brand-content h1 { margin:0 0 18px; font-size:clamp(2.5rem,4.5vw,4.4rem); line-height:1.02; letter-spacing:-.055em; font-weight:500; }
.auth-brand-content h1 strong { color:#73d9a6; font-weight:800; }
.auth-brand-content p { margin:0; max-width:430px; color:rgba(255,255,255,.68); font-size:1rem; line-height:1.7; }
.auth-copyright { position:relative; z-index:1; color:rgba(255,255,255,.38); font-size:.74rem; }
.auth-form-panel { display:flex; align-items:center; justify-content:center; padding:42px clamp(20px,6vw,100px); background:#f7f9f8; }
.auth-card { width:min(100%,470px); background:#fff; border:1px solid #e3e9e5; border-radius:22px; padding:40px; box-shadow:0 24px 70px rgba(16,37,27,.10); }
.auth-card-register { width:min(100%,610px); }
.auth-card-header { margin-bottom:24px; }
.auth-mobile-brand { display:none; margin-bottom:16px; color:#14543a; font-weight:800; }
.auth-card-header h2 { margin:0 0 8px; color:#17201b; font-size:1.65rem; line-height:1.2; letter-spacing:-.035em; }
.auth-card-header p { margin:0; color:#737c77; font-size:.9rem; line-height:1.55; }
.auth-form { display:flex; flex-direction:column; gap:17px; margin:0; }
.auth-form .form-grupo { display:flex; flex-direction:column; gap:7px; }
.auth-form .form-grupo label { color:#303a34; font-size:.79rem; font-weight:700; }
.auth-form .form-linha { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:14px; }
.auth-form input { width:100%; min-height:48px; padding:12px 14px; border:1px solid #d9e1dc; border-radius:10px; outline:none; background:#fbfdfc; color:#18201c; font:inherit; font-size:.9rem; transition:.15s ease; }
.auth-form input::placeholder { color:#a0aaa4; }
.auth-form input:hover { border-color:#b9c5be; }
.auth-form input:focus { border-color:#1f8257; background:#fff; box-shadow:0 0 0 4px rgba(31,130,87,.10); }
.btn-auth { width:100%; min-height:50px; margin-top:4px; padding:12px 18px; border:0; border-radius:10px; background:linear-gradient(135deg,#14543a,#1f8257); color:#fff; font:inherit; font-size:.9rem; font-weight:800; cursor:pointer; box-shadow:0 9px 20px rgba(20,84,58,.18); transition:.15s ease; }
.btn-auth:hover { transform:translateY(-1px); filter:brightness(1.04); box-shadow:0 13px 25px rgba(20,84,58,.23); }
.auth-switch { margin:22px 0 0; padding-top:18px; border-top:1px solid #eef1ef; text-align:center; color:#77817b; font-size:.82rem; }
.auth-switch a { color:#14543a; font-weight:800; text-decoration:none; }
.auth-switch a:hover { text-decoration:underline; }
.alerta { display:flex; align-items:center; gap:10px; padding:12px 14px; margin-bottom:18px; border-radius:10px; font-size:.83rem; font-weight:700; }
.alerta-erro { background:#fff0ef; border:1px solid #f0c6c3; color:#b52c25; }
.alerta-erro:before { content:"!"; display:grid; place-items:center; width:18px; height:18px; flex:0 0 18px; border-radius:50%; background:#c22e26; color:#fff; font-size:.7rem; }
@media (max-width:900px) {
  .auth-layout { grid-template-columns:1fr; }
  .auth-brand-panel { min-height:235px; padding:28px 24px; }
  .auth-brand-content { margin-top:42px; }
  .auth-brand-content h1 { font-size:2.35rem; }
  .auth-copyright { display:none; }
  .auth-form-panel { padding:28px 18px 46px; }
}
@media (max-width:600px) {
  .auth-brand-panel { min-height:205px; }
  .auth-brand-content { margin-top:30px; }
  .auth-brand-content h1 { font-size:2rem; }
  .auth-brand-content p { font-size:.86rem; }
  .auth-card { padding:28px 20px; border-radius:16px; }
  .auth-card-register { width:100%; }
  .auth-mobile-brand { display:block; }
  .auth-form .form-linha { grid-template-columns:1fr; }
}

    </style>
</head>
<body class="auth-page">
    <main class="auth-layout">
        <section class="auth-brand-panel">
            <div class="auth-brand">
                <span class="brand-mark"><img src="assets/logo.svg" alt="MoneyCash"></span>
                <span>MoneyCash</span>
            </div>
            <div class="auth-brand-content">
                <span class="auth-kicker">Comece agora</span>
                <h1>Organize sua vida<br><strong>financeira.</strong></h1>
                <p>Crie sua conta e tenha uma visão clara do seu dinheiro.</p>
            </div>
            <span class="auth-copyright">MoneyCash © <?= date('Y') ?></span>
        </section>

        <section class="auth-form-panel">
            <div class="auth-card auth-card-register">
                <div class="auth-card-header">
                    <span class="auth-mobile-brand">MoneyCash</span>
                    <h2>Criar sua conta</h2>
                    <p>Preencha os dados abaixo. Você já entrará logado após o cadastro.</p>
                </div>

                <?php if ($erro): ?>
                    <div class="alerta alerta-erro"><?= htmlspecialchars($erro) ?></div>
                <?php endif; ?>

                <form method="POST" action="cadastro.php" class="auth-form">
                    <div class="form-grupo">
                        <label for="nome">Nome completo</label>
                        <input type="text" id="nome" name="nome" value="<?= htmlspecialchars($nome) ?>" placeholder="Seu nome" autocomplete="name" required>
                    </div>

                    <div class="form-grupo">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" placeholder="voce@exemplo.com" autocomplete="email" required>
                    </div>

                    <div class="form-linha">
                        <div class="form-grupo">
                            <label for="telefone">Telefone</label>
                            <input type="text" id="telefone" name="telefone" value="<?= htmlspecialchars($telefone) ?>" placeholder="(00) 00000-0000" autocomplete="tel">
                        </div>
                        <div class="form-grupo">
                            <label for="data_nascimento">Nascimento</label>
                            <input type="date" id="data_nascimento" name="data_nascimento" value="<?= htmlspecialchars($data_nascimento) ?>">
                        </div>
                    </div>

                    <div class="form-linha">
                        <div class="form-grupo">
                            <label for="senha">Senha</label>
                            <input type="password" id="senha" name="senha" placeholder="Mínimo 6 caracteres" autocomplete="new-password" required>
                        </div>
                        <div class="form-grupo">
                            <label for="confirmacao">Confirmar senha</label>
                            <input type="password" id="confirmacao" name="confirmacao" placeholder="Repita a senha" autocomplete="new-password" required>
                        </div>
                    </div>

                    <button type="submit" class="btn-auth">Criar conta</button>
                </form>

                <p class="auth-switch">Já possui uma conta? <a href="login.php">Fazer login</a></p>
            </div>
        </section>
    </main>
</body>
</html>
