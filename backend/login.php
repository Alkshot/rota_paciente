<?php
require __DIR__ . '/fxf/api/src/auth.php';
require __DIR__ . '/includes/conexao.php';
require __DIR__ . '/includes/encaminhar_usuario.php';

iniciar_sessao_segura();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../frontend/index.html');
    exit;
}

$sessao = autenticar($pdo, trim($_POST['login'] ?? ''), $_POST['senha'] ?? '');

if (!$sessao || $sessao['perfil'] === 'PACIENTE') {
    header('Location: ../frontend/index.html?erro=1');
    exit;
}

registrar_login($sessao);

$destinos = [
    'UBS'           => '../frontend/fxf/visao-geral.php',
    'ESPECIALIZADA' => '../frontend/fxf/visao-geral.php',
    'HOSPITAL'      => '../frontend/fxf/visao-geral.php',
    'GESTOR'        => '../frontend/fxf/indicadores.php'
];

header('Location: ' . $destinos[$sessao['perfil']]);
exit;