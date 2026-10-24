<?php
//// dbh handler

$url ='localhost';
$dbname='rota_paciente';
$dbusername = 'root';
$dbpassword = '';

$dsn = 'mysql:host='.$url.';dbname='. $dbname;

try {
    $pdo = new PDO($dsn, $dbusername, $dbpassword);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'Connection failed: ' . $e->getMessage();
}