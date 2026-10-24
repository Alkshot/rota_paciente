<?php

declare(strict_types=1);

require_once 'cadastro_model.inc.php';

function is_input_empty(string $username, string $pwd, string $email, int $age, int $DM) :bool
{
    if (empty($username) || empty($pwd) || empty($email) || empty($age) || empty($DM)) {
        return true;
    }
    return false;
}

function is_email_invalid(string $email) :bool
{
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    }
    return false;
}

function is_email_registered(PDO $pdo, string $email) :bool
{
    if (get_email($pdo, $email) === strtolower($email)) {
        return true;
    }
    return false;
}


function create_user(PDO $pdo, string $username, string $pwd, string $email, string $tipo, string $unidade)
{
    set_user($pdo, $username, $pwd, $email, $tipo, $unidade);
}

