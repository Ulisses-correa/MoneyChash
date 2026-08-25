<?php
require_once __DIR__ . '/includes/config.php';
$logado = usuario_logado();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="MoneyCash — gestão financeira simples, elegante e organizada para controlar receitas, despesas, contas e metas.">
    <title>MoneyCash · Gestão financeira</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css?v=20260821">
    <style>
        .landing-page{background:#f5f8f6;color:#17201c;overflow-x:hidden}
        .landing-nav{position:sticky;top:0;z-index:50;display:flex;align-items:center;justify-content:space-between;gap:24px;padding:18px clamp(20px,5vw,72px);background:rgba(255,255,255,.88);border-bottom:1px solid #e4ebe7;backdrop-filter:blur(18px)}
        .landing-brand{display:flex;align-items:center;gap:11px;color:#0a281d;text-decoration:none;font-weight:800;font-size:1.08rem}
        .landing-mark{display:grid;place-items:center;width:38px;height:38px;border-radius:12px;overflow:hidden;background:transparent;box-shadow:0 8px 22px rgba(23,99,66,.18)}.landing-mark img{display:block;width:100%;height:100%}
        .landing-nav-links{display:flex;align-items:center;gap:8px}
        .landing-nav-links a{padding:9px 13px;color:#53615a;text-decoration:none;font-size:.86rem;font-weight:600;border-radius:9px}
        .landing-nav-links a:hover{background:#eef5f1;color:#176342}
        .landing-actions{display:flex;align-items:center;gap:9px}
        .landing-btn{display:inline-flex;align-items:center;justify-content:center;min-height:44px;padding:0 17px;border-radius:11px;text-decoration:none;font-size:.86rem;font-weight:700;transition:.18s ease;border:1px solid transparent}
        .landing-btn-outline{border-color:#d7e1db;color:#254238;background:#fff}
        .landing-btn-outline:hover{border-color:#b9cfc2;transform:translateY(-1px)}
        .landing-btn-primary{color:#fff;background:#176342;box-shadow:0 10px 24px rgba(23,99,66,.18)}
        .landing-btn-primary:hover{background:#0f4f33;transform:translateY(-1px);box-shadow:0 14px 28px rgba(23,99,66,.22)}
        .landing-hero{position:relative;display:grid;grid-template-columns:minmax(0,1.08fr) minmax(360px,.92fr);gap:clamp(40px,7vw,100px);align-items:center;max-width:1320px;margin:0 auto;padding:92px clamp(20px,5vw,72px) 105px}
        .landing-hero:before{content:"";position:absolute;width:560px;height:560px;left:-280px;top:-180px;border-radius:50%;background:radial-gradient(circle,rgba(58,180,119,.12),transparent 68%);pointer-events:none}
        .hero-kicker{display:inline-flex;align-items:center;gap:8px;padding:7px 11px;border:1px solid #cfe5d8;border-radius:999px;background:#eff9f3;color:#176342;font-size:.72rem;font-weight:800;letter-spacing:.08em;text-transform:uppercase}
        .hero-kicker span{width:7px;height:7px;border-radius:50%;background:#36b576;box-shadow:0 0 0 4px rgba(54,181,118,.12)}
        .landing-hero h1{max-width:760px;margin:20px 0 18px;color:#0d1d16;font-size:clamp(2.7rem,5.7vw,5.25rem);line-height:1.02;letter-spacing:-.065em;font-weight:800}
        .landing-hero h1 em{font-style:normal;color:#1a704b}
        .hero-copy{max-width:650px;color:#5d6963;font-size:1.06rem;line-height:1.8}
        .hero-actions{display:flex;flex-wrap:wrap;gap:11px;margin-top:30px}
        .hero-note{display:flex;align-items:center;gap:9px;margin-top:18px;color:#7a857f;font-size:.76rem}
        .hero-note strong{color:#3f5048}
        .hero-visual{position:relative}
        .dashboard-preview{position:relative;padding:14px;border:1px solid rgba(255,255,255,.9);border-radius:24px;background:linear-gradient(145deg,#fff,#f0f6f2);box-shadow:0 35px 80px rgba(12,53,36,.14),0 6px 20px rgba(12,53,36,.07);transform:rotate(1.5deg)}
        .preview-window{overflow:hidden;border:1px solid #e0e8e3;border-radius:17px;background:#fff}
        .preview-top{display:flex;align-items:center;justify-content:space-between;padding:13px 15px;border-bottom:1px solid #e7ede9}
        .preview-dots{display:flex;gap:5px}.preview-dots i{display:block;width:7px;height:7px;border-radius:50%;background:#cbd5cf}
        .preview-profile{width:86px;height:8px;border-radius:99px;background:#dce6e0}
        .preview-body{display:grid;grid-template-columns:76px 1fr;min-height:340px}
        .preview-side{padding:17px 10px;background:#0b3022}.preview-side b{display:block;width:42px;height:8px;margin:0 auto 22px;border-radius:9px;background:#3cb77a}.preview-side span{display:block;height:7px;margin:14px 7px;border-radius:9px;background:rgba(255,255,255,.2)}.preview-side span:first-of-type{background:#73d6a4}
        .preview-content{padding:20px;background:#f6f8f7}.preview-title{width:125px;height:12px;border-radius:7px;background:#25352e;margin-bottom:18px}.preview-cards{display:grid;grid-template-columns:repeat(2,1fr);gap:10px}.preview-card{padding:14px;border:1px solid #e2e9e5;border-radius:12px;background:#fff}.preview-card small{display:block;width:55px;height:6px;margin-bottom:9px;border-radius:6px;background:#b9c5bf}.preview-card strong{display:block;width:92px;height:10px;border-radius:7px;background:#1a6b48}.preview-chart{height:112px;margin-top:10px;border:1px solid #e2e9e5;border-radius:12px;background:#fff;position:relative;overflow:hidden}.preview-chart:before{content:"";position:absolute;inset:30px 16px 18px;background:linear-gradient(145deg,transparent 46%,#2d9b67 47%,#2d9b67 50%,transparent 51%),linear-gradient(165deg,transparent 49%,#73c99e 50%,#73c99e 52%,transparent 53%);opacity:.65}
        .hero-float{position:absolute;right:-22px;bottom:28px;display:flex;align-items:center;gap:11px;padding:13px 15px;border:1px solid #dce8e1;border-radius:14px;background:rgba(255,255,255,.95);box-shadow:0 16px 32px rgba(9,41,28,.12)}
        .float-icon{display:grid;place-items:center;width:35px;height:35px;border-radius:10px;background:#e8f6ee;color:#18724b;font-weight:800}.hero-float small{display:block;color:#79847e;font-size:.65rem}.hero-float strong{display:block;margin-top:1px;color:#1b2b24;font-size:.9rem}
        .landing-section{padding:92px clamp(20px,5vw,72px)}
        .landing-section.alt{background:#fff;border-top:1px solid #e7ede9;border-bottom:1px solid #e7ede9}
        .section-inner{max-width:1180px;margin:0 auto}.section-heading{text-align:center;max-width:700px;margin:0 auto 45px}.section-heading .eyebrow{margin-bottom:9px;color:#1a704b;font-size:.68rem;font-weight:800;letter-spacing:.12em;text-transform:uppercase}.section-heading h2{color:#14231c;font-size:clamp(1.8rem,3vw,2.6rem);line-height:1.1;letter-spacing:-.045em}.section-heading p{margin-top:12px;color:#6c7771;line-height:1.7}
        .feature-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:17px}.feature-card{padding:27px;border:1px solid #e2eae5;border-radius:18px;background:#fff;box-shadow:0 8px 25px rgba(12,53,36,.045);transition:.18s ease}.feature-card:hover{transform:translateY(-4px);box-shadow:0 18px 35px rgba(12,53,36,.08);border-color:#cfe0d6}.feature-icon{display:grid;place-items:center;width:42px;height:42px;margin-bottom:19px;border-radius:12px;background:#eaf7ef;color:#176342;font-weight:800}.feature-card h3{margin-bottom:7px;color:#1a2821;font-size:1rem}.feature-card p{color:#69756f;font-size:.87rem;line-height:1.7}
        .steps{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}.step{position:relative;padding:27px;border-radius:18px;background:#f7faf8;border:1px solid #e2eae5}.step-number{display:grid;place-items:center;width:34px;height:34px;margin-bottom:18px;border-radius:10px;background:#0f3d2c;color:#fff;font-size:.78rem;font-weight:800}.step h3{margin-bottom:7px;color:#1a2821}.step p{color:#6c7771;font-size:.87rem;line-height:1.7}
        .landing-cta{max-width:1180px;margin:0 auto;padding:48px 54px;border-radius:24px;background:radial-gradient(circle at 85% 20%,rgba(72,207,139,.2),transparent 30%),linear-gradient(135deg,#08271c,#14543a);color:#fff;display:flex;align-items:center;justify-content:space-between;gap:30px;box-shadow:0 25px 55px rgba(8,39,28,.16)}.landing-cta h2{font-size:clamp(1.7rem,3vw,2.45rem);letter-spacing:-.045em;line-height:1.1}.landing-cta p{margin-top:9px;color:rgba(255,255,255,.7);font-size:.9rem}.landing-cta .landing-btn{background:#fff;color:#125337}.landing-cta .landing-btn:hover{background:#eef7f2}
        .landing-footer{padding:26px clamp(20px,5vw,72px);background:#061d15;color:rgba(255,255,255,.55)}.landing-footer-inner{max-width:1180px;margin:0 auto;display:flex;align-items:center;justify-content:space-between;gap:20px;font-size:.76rem}.landing-footer-brand{display:flex;align-items:center;gap:9px;color:#fff;font-weight:700}.landing-footer-brand .landing-mark{width:28px;height:28px;border-radius:8px}
        @media(max-width:900px){.landing-nav-links{display:none}.landing-hero{grid-template-columns:1fr;padding-top:65px}.hero-visual{max-width:620px;margin:0 auto;width:100%}.feature-grid,.steps{grid-template-columns:1fr}.landing-cta{padding:38px 30px;flex-direction:column;align-items:flex-start}}
        @media(max-width:600px){.landing-nav{padding:14px 18px}.landing-actions .landing-btn-outline{display:none}.landing-hero{padding:55px 18px 70px}.landing-hero h1{font-size:clamp(2.6rem,14vw,4rem)}.hero-copy{font-size:.95rem}.dashboard-preview{padding:8px;border-radius:18px}.preview-body{grid-template-columns:52px 1fr;min-height:270px}.preview-content{padding:13px}.preview-side{padding:13px 6px}.hero-float{right:-4px;bottom:14px}.landing-section{padding:70px 18px}.landing-cta{margin:0 18px;padding:30px 24px}.landing-footer-inner{flex-direction:column;align-items:flex-start}}
    </style>
</head>
<body class="landing-page">
    <nav class="landing-nav">
        <a href="index.php" class="landing-brand"><span class="landing-mark"><img src="assets/logo.svg" alt="MoneyCash"></span><span>MoneyCash</span></a>
        <div class="landing-nav-links">
            <a href="#recursos">Recursos</a>
            <a href="#como-funciona">Como funciona</a>
            <a href="#sobre">Sobre</a>
        </div>
        <div class="landing-actions">
            <?php if ($logado): ?>
                <a href="painel.php" class="landing-btn landing-btn-primary">Abrir painel</a>
            <?php else: ?>
                <a href="login.php" class="landing-btn landing-btn-outline">Entrar</a>
                <a href="cadastro.php" class="landing-btn landing-btn-primary">Criar conta</a>
            <?php endif; ?>
        </div>
    </nav>

    <main>
        <section class="landing-hero" id="sobre">
            <div>
                <div class="hero-kicker"><span></span> Gestão financeira inteligente</div>
                <h1>Seu dinheiro merece <em>mais controle.</em></h1>
                <p class="hero-copy">Organize receitas, despesas, contas e metas em um único lugar. O MoneyCash transforma a bagunça financeira em uma visão clara do que entra, do que sai e de onde você quer chegar.</p>
                <div class="hero-actions">
                    <?php if ($logado): ?>
                        <a href="painel.php" class="landing-btn landing-btn-primary">Ir para meu painel →</a>
                    <?php else: ?>
                        <a href="cadastro.php" class="landing-btn landing-btn-primary">Começar agora →</a>
                        <a href="login.php" class="landing-btn landing-btn-outline">Já tenho uma conta</a>
                    <?php endif; ?>
                </div>
                <div class="hero-note"><strong>Simples.</strong> Organizado. <strong>Feito para você.</strong></div>
            </div>
            <div class="hero-visual" aria-label="Prévia do painel MoneyCash">
                <div class="dashboard-preview">
                    <div class="preview-window">
                        <div class="preview-top"><div class="preview-dots"><i></i><i></i><i></i></div><div class="preview-profile"></div></div>
                        <div class="preview-body">
                            <div class="preview-side"><b>M</b><span></span><span></span><span></span><span></span><span></span></div>
                            <div class="preview-content">
                                <div class="preview-title"></div>
                                <div class="preview-cards"><div class="preview-card"><small>Saldo</small><strong>R$ 4.820</strong></div><div class="preview-card"><small>Receitas</small><strong>R$ 6.300</strong></div></div>
                                <div class="preview-chart"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="hero-float"><div class="float-icon">✓</div><div><small>Controle em dia</small><strong>Finanças organizadas</strong></div></div>
            </div>
        </section>

        <section class="landing-section alt" id="recursos">
            <div class="section-inner">
                <div class="section-heading"><div class="eyebrow">Tudo em um só lugar</div><h2>Menos planilhas. Mais clareza.</h2><p>Tenha as principais ferramentas para cuidar da sua vida financeira sem complicação.</p></div>
                <div class="feature-grid">
                    <article class="feature-card"><div class="feature-icon">R$</div><h3>Receitas e despesas</h3><p>Registre entradas e saídas, acompanhe os valores e entenda para onde seu dinheiro está indo.</p></article>
                    <article class="feature-card"><div class="feature-icon">▣</div><h3>Contas organizadas</h3><p>Centralize contas, carteiras e outras fontes financeiras para enxergar seu saldo com facilidade.</p></article>
                    <article class="feature-card"><div class="feature-icon">◎</div><h3>Metas financeiras</h3><p>Defina objetivos e acompanhe seu progresso para transformar planos em resultados.</p></article>
                    <article class="feature-card"><div class="feature-icon">↗</div><h3>Relatórios</h3><p>Visualize sua situação financeira de forma resumida e tome decisões com mais segurança.</p></article>
                    <article class="feature-card"><div class="feature-icon">≡</div><h3>Extrato completo</h3><p>Consulte seu histórico de movimentações e mantenha tudo registrado e fácil de encontrar.</p></article>
                    <article class="feature-card"><div class="feature-icon">✓</div><h3>Rotina mais simples</h3><p>Uma interface limpa para você gastar menos tempo procurando informações e mais tempo planejando.</p></article>
                </div>
            </div>
        </section>

        <section class="landing-section" id="como-funciona">
            <div class="section-inner">
                <div class="section-heading"><div class="eyebrow">Como funciona</div><h2>Comece em poucos passos.</h2><p>O MoneyCash foi pensado para deixar o controle financeiro mais direto e natural.</p></div>
                <div class="steps">
                    <article class="step"><div class="step-number">01</div><h3>Crie sua conta</h3><p>Cadastre seus dados e entre no sistema com segurança para começar a organizar suas finanças.</p></article>
                    <article class="step"><div class="step-number">02</div><h3>Registre suas movimentações</h3><p>Adicione receitas, despesas, contas e categorias para construir uma visão real do seu dinheiro.</p></article>
                    <article class="step"><div class="step-number">03</div><h3>Acompanhe e planeje</h3><p>Use o painel, os relatórios e as metas para acompanhar sua evolução e tomar decisões melhores.</p></article>
                </div>
            </div>
        </section>

        <section class="landing-section" style="padding-top:0">
            <div class="landing-cta">
                <div><h2>Pronto para colocar suas finanças em ordem?</h2><p>Entre no MoneyCash e tenha tudo organizado em um único painel.</p></div>
                <?php if ($logado): ?><a href="painel.php" class="landing-btn">Acessar painel →</a><?php else: ?><a href="cadastro.php" class="landing-btn">Criar minha conta →</a><?php endif; ?>
            </div>
        </section>
    </main>

    <footer class="landing-footer">
        <div class="landing-footer-inner"><div class="landing-footer-brand"><span class="landing-mark"><img src="assets/logo.svg" alt="MoneyCash"></span><span>MoneyCash</span></div><span>© <?= date('Y') ?> · Gestão financeira pessoal</span></div>
    </footer>
</body>
</html>
