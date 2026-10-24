<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../frontend/pages/login.php');
    die();
}

require_once 'includes/get_encaminhamentos_contr.inc.php';
require_once 'includes/dbh.inc.php';

$dados = json_decode(file_get_contents('php://input'), true);
$cod = strtoupper(trim($dados['cod'] ?? ''));

$g = get_encaminhamentos($pdo, $cod);
echo json_encode($g);