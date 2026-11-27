<?php
require __DIR__ . '/fxf/api/src/auth.php';
iniciar_sessao_segura();
require __DIR__ . '/includes/conexao.php';
require __DIR__ . '/includes/encaminhar_usuario.php';

if (!isset($_SESSION['usuario'])) {
    header('Location: login.php');
    exit;
}

$usuario = $_SESSION['usuario'];
$encaminhamentos = listarEncaminhamentos($pdo, $usuario);

echo '<h2>' . htmlspecialchars($usuario['perfil'] . ' - ' . $usuario['nome']) . '</h2>';
foreach ($encaminhamentos as $e) {
    echo '<p>' . htmlspecialchars($e['codigo'] . ' | ' . $e['paciente'] . ' | ' . $e['especialidade'] . ' | ' . $e['prioridade'] . ' | ' . $e['statuss']) . '</p>';
}
