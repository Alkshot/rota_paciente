<?php

declare(strict_types=1);

function get_email(PDO $pdo, string $email) :array|null
{
    $query = 'SELECT email from usuarios where email = :email';
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $results = $stmt->fetch(PDO::FETCH_ASSOC);
    return $results ? $results['email'] : null;
}


function get_user_by_email(PDO $pdo, string $email) :array|null 
{
    $query = 'SELECT * from usuarios where email = :email;';

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $results = $stmt->fetch(PDO::FETCH_ASSOC);
    return  $results['id_users'] ? $results : null;
}

function get_unidade_by_name(PDO $pdo, string $name){
$query='SELECT * from unidades where nome = :nome';
$stmt = $pdo->prepare($query);
$stmt->bindParam(':nome', $name);
$stmt->execute();
$results = $stmt->fetch(PDO::FETCH_ASSOC);
return $results ? $results['unidade_id'] : null;
}


function set_user(PDO $pdo, string $username, string $pwd, string $email, string $tipo, string $unidade)
{
    $query = 'INSERT into users(nome, email, senha_hash,tipo_funcionario, unidade_fk)
values (:nome, :email, :senha_hash,:tipo, :uni )';

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':nome', $username);

    $options = ['cost' => 11];
    $hashed_PWD = password_hash($pwd, PASSWORD_BCRYPT, $options);


$unidade = get_unidade_by_name($pdo, $unidade);

    $stmt->bindParam(':senha_hash', $hashed_PWD);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':tipo', $tipo);
    $stmt->bindParam(':unidade', $unidade);

    $stmt->execute();
}