<?php

if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header('Location: ../frontend/cadastro.php');
    die();
}

$username = $_POST['nome'];
$tipo_esp = $_POST['perfil'];
$unidade = $_POST['unidade'];
$email = $_POST['email'];
$pwd = $_POST['senha'];

//TODO

try{
require_once 'includes/dbh.inc.php';
require_once 'includes/cadastro_contr.inc.php';


$errors = [];


//TODO
if(is_input_empty($username, $pwd, $email, $unidade, $tipo_esp)){ 
    $errors['empty_input'] = 'Preencha Todos os campos';
}

if(is_email_invalid($email)){
$errors['invalid_email'] = 'Email está incorreto';
}
if(is_unidade_invalid($pdo, $unidade)){
 $errors['invalid_unidade'] ='Unidade inválida';
}

if(is_email_registered($pdo, $email)){
$errors['registered_email'] = 'Já existe uma conta com este e-mail.';
}

if(mb_strlen($pwd)<6){
$errors['short_pwd'] = 'A senha deve ter ao menos 6 caracteres.';
}

require_once 'includes/config_session.inc.php';

if($errors){
$_SESSION['errors_signup'] = $errors;

$signup_data = ['nome' => $username, 'email' => $email]; 

$_SESSION['signup_data'] = $signup_data;


    header('Location: ../frontend/pages/cadastro.php');
die();
}

create_user($pdo, $username, $pwd, $email, $unidade, $tipo_esp);
header("Location: ../frontend/pages/painel.php");

// $pdo= null;
// $stmt = null;
die();
}catch(PDOException $e){
    die('Query failed: '. $e->getMessage());
}
