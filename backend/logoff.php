<?php
require __DIR__ . '/fxf/api/src/auth.php';
iniciar_sessao_segura();
fazer_logout();
header('Location: ../frontend/index.html');
exit;