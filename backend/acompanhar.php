<?php
require_once 'includes/dbh.inc.php';
require_once 'includes/acompanhar_contr.inc.php';

$cod = $_GET['codigo'];

if(is_code_invalid($pdo, $cod)){
    echo($cod);
header('Location: ../frontend/index.php');
}

header('Location: ../frontend/acompanhar.php?codigo='. $cod);

// RP-7K3M9Q