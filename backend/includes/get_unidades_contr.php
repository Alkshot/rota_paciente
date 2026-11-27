<?php
declare(strict_types=1);
require_once 'get_unidades_model.inc.php';

function get_unidades(PDO $pdo){
 return get_model_unidades($pdo);
}