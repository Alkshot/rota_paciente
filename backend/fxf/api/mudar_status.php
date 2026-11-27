<?php
declare(strict_types=1);

require __DIR__ . '/src/http.php';
require __DIR__ . '/src/auth.php';
require __DIR__ . '/src/fluxo.php';
require __DIR__ . '/src/contrareferencia.php';
require __DIR__ . '/../../includes/conexao.php';

iniciar_sessao_segura();
exigir_metodo('POST');
$usuario = exigir_perfil_api(['UBS', 'ESPECIALIZADA']);

$in = ler_entrada();
exigir_csrf($in);

$id         = filter_var($in['encaminhamento_id'] ?? null, FILTER_VALIDATE_INT);
$novoStatus = (string) ($in['novo_status'] ?? '');
$obs        = trim((string) ($in['observacao'] ?? ''));

if (!$id) {
    json_erro('encaminhamento_id inválido');
}

$st = $pdo->prepare('SELECT statuss, origem_fk, destino_fk FROM encaminhamentos WHERE encaminhamento_id = ?');
$st->execute([$id]);
$linha = $st->fetch();
if (!$linha) {
    json_erro('Encaminhamento não encontrado', 404);
}
$enc = [
    'status'     => $linha['statuss'],
    'origem_id'  => (int) $linha['origem_fk'],
    'destino_id' => (int) $linha['destino_fk']
];

$minhaUnidade = $usuario['unidade_id'];
$ehDaMinhaUnidade = $usuario['perfil'] === 'UBS'
    ? $enc['origem_id'] === $minhaUnidade
    : $enc['destino_id'] === $minhaUnidade;
if (!$ehDaMinhaUnidade) {
    json_erro('Encaminhamento não pertence à sua unidade', 403);
}

[$ok, $motivo] = validar_transicao($enc['status'], $novoStatus, $usuario['perfil'], $obs);
if (!$ok) {
    json_erro($motivo, 422);
}

if ($novoStatus === 'CONTRARREFERENCIA_ENVIADA') {
    $v = validar_contrarreferencia($in);
    if (!$v['ok']) {
        json_erro('Formulário incompleto', 422, ['campos' => $v['erros']]);
    }
}

$pdo->beginTransaction();
$pdo->prepare('UPDATE encaminhamentos SET statuss = ? WHERE encaminhamento_id = ?')->execute([$novoStatus, $id]);
$pdo->prepare('INSERT INTO eventos (encaminhamento_fk, etapa, usuario_fk, observacao, criado_em) VALUES (?,?,?,?,NOW())')->execute([$id, $novoStatus, $usuario['id'], $obs]);
$pdo->commit();

json_response(['ok' => true, 'status' => $novoStatus, 'rotulo' => rotulo_status($novoStatus)]);