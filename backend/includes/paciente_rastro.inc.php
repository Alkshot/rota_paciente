<?php
declare(strict_types=1);

function get_name_paciente(PDO $pdo, int $id){
$query ='SELECT nome from pacientes where paciente_id = :id;';
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id',$id);
$results = $stmt->fetch(PDO::FETCH_ASSOC);
return $results;
};
