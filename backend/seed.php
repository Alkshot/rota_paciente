<?php
require __DIR__ . '/includes/conexao.php';

$pdo->exec("INSERT INTO unidades (nome, tipo) VALUES ('UBS Centro','UBS'),('Clinica Cardio','ESPECIALIZACAO'),('Hospital Geral','HOSPITAL')");
$pdo->exec("INSERT INTO especialidades (nome, sla_dias) VALUES ('Cardiologia', 15)");
$pdo->exec("INSERT INTO pacientes (nome, cns, nascimento, telefone, unidade_fk) VALUES ('Maria Silva', 123456789012345, '1980-05-20', '51999999999', 1)");

$hash = password_hash('123456', PASSWORD_DEFAULT);
$st = $pdo->prepare('INSERT INTO usuarios (nome, email, tipo_funcionario, unidade_fk, senha_hash) VALUES (?,?,?,?,?)');
$st->execute(['Ana UBS', 'ubs@teste.com', 'UBS', 1, $hash]);
$st->execute(['Dr Carlos', 'doutor@teste.com', 'ESPECIALIZADA', 2, $hash]);
$st->execute(['Gestor', 'gestor@teste.com', 'GESTOR', 1, $hash]);

$pdo->exec("INSERT INTO encaminhamentos (codigo, paciente_fk, origem_fk, destino_fk, especialidade_fk, prioridade, statuss, criado_em) VALUES ('ENC-001', 1, 1, 2, 1, 'amarelo', 'aguardando', NOW())");

echo 'ok';