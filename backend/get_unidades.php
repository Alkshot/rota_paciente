<?php
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../frontend/pages/login.php');
    die();
}

require_once 'includes/get_unidades_contr.inc.php';
require_once 'includes/dbh.inc.php';

echo json_encode(get_unidades($pdo));