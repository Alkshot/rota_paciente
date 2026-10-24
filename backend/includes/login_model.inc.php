<?php

function get_user(PDO $pdo, string $email){
$query ='SELECT * from usuarios where email = :email;';
$stmt = $pdo->prepare($query);
$stmt->bindParam(':email',$email);
$stmt->execute();
$results = $stmt->fetch(PDO::FETCH_ASSOC);
return $results;
}