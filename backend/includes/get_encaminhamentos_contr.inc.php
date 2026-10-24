<?php
declare(strict_types=1);
require_once 'get_encaminhamentos_model.inc.php';

function get_encaminhamentos(PDO $pdo, string $cod){
 return get_model_encaminhamentos($pdo,$cod);
}