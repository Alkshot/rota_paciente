<?php
declare(strict_types=1);

function get_model_encaminhamentos(PDO $pdo, string $cod){
    $query = 'SELECT * FROM encaminhamentos WHERE codigo = :cod';

    $stmt = $pdo->prepare($query);
    $stmt->execute(['cod' => trim($cod)]);

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result === false ? null : $result;
}


function get_name_paciente(PDO $pdo, int $id){
$query ='SELECT nome from pacientes where paciente_id = :id;';
$stmt = $pdo->prepare($query);
$stmt->bindParam(':id',$id);
$results = $stmt->fetch(PDO::FETCH_ASSOC);
return $results;
};

