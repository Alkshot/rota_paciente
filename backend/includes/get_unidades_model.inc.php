<?php
declare(strict_types=1);

function get_model_unidades(PDO $pdo){
$query='SELECT * from unidades;';
$stmt = $pdo->prepare($query);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
return $results;
}
