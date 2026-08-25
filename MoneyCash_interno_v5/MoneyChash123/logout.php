<?php
require __DIR__ . '/includes/config.php';

encerrar_sessao();
header('Location: login.php?logout=1');
exit;
