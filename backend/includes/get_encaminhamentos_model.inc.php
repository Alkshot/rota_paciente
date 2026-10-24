<?php
declare(strict_types=1);

function get_model_encaminhamentos(PDO $pdo, string $cod){
$query='SELECT * from encaminhamentos where codigo = :cod;';
$stmt = $pdo->prepare($query);
$stmt->bindParam(':cod', $cod);
$stmt->execute();
$results = $stmt->fetch(PDO::FETCH_ASSOC);
return $results ? $results : null;
}