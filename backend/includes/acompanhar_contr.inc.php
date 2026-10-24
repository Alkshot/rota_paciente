<?php

declare(strict_types=1);
require_once 'acompanhar_model.inc.php';

function get_encaminhamento(PDO $pdo, string $cod)
{
    return get_model_encaminhamentos($pdo, $cod);
}

function check_code(PDO $pdo, string $cod)
{
    return get_model_encaminhamentos($pdo, $cod) !== null;
}

function is_code_invalid(PDO $pdo, string $cod)
{
    return trim($cod) === '' || !check_code($pdo, $cod);
}
