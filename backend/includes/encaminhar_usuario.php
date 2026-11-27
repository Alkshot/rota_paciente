<?php
function autenticar($pdo, $login, $senha) {
    $st = $pdo->prepare('SELECT usuario_id, nome, tipo_funcionario, unidade_fk, senha_hash FROM usuarios WHERE email = ?');
    $st->execute([$login]);
    $u = $st->fetch();

    if ($u && password_verify($senha, $u['senha_hash'])) {
        return [
            'perfil'     => $u['tipo_funcionario'],
            'id'         => (int) $u['usuario_id'],
            'nome'       => $u['nome'],
            'unidade_id' => (int) $u['unidade_fk']
        ];
    }

    if (!ctype_digit($login) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $senha)) {
        return null;
    }

    $st = $pdo->prepare('SELECT id_paciente, nome FROM pacientes WHERE cns = ? AND nascimento = ?');
    $st->execute([$login, $senha]);
    $p = $st->fetch();

    if ($p) {
        return [
            'perfil'     => 'PACIENTE',
            'id'         => (int) $p['id_paciente'],
            'nome'       => $p['nome'],
            'unidade_id' => 0
        ];
    }

    return null;
}

function listarEncaminhamentos($pdo, $s) {
    $sql = 'SELECT e.encaminhamento_id, e.codigo, e.prioridade, e.statuss, e.criado_em,
                   p.nome AS paciente, esp.nome AS especialidade
            FROM encaminhamentos e
            JOIN pacientes p ON p.id_paciente = e.paciente_fk
            JOIN especialidades esp ON esp.especialidade_id = e.especialidade_fk';
    $params = [];

    switch ($s['perfil']) {
        case 'PACIENTE':
            $sql .= ' WHERE e.paciente_fk = ?';
            $params = [$s['id']];
            break;
        case 'UBS':
            $sql .= ' WHERE e.origem_fk = ?';
            $params = [$s['unidade_id']];
            break;
        case 'ESPECIALIZADA':
        case 'HOSPITAL':
            $sql .= ' WHERE e.destino_fk = ?';
            $params = [$s['unidade_id']];
            break;
    }

    $st = $pdo->prepare($sql . ' ORDER BY e.criado_em DESC');
    $st->execute($params);
    return $st->fetchAll();
}