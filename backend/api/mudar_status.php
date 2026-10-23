<?php
declare(strict_types=1);

require __DIR__ . '/../src/http.php';
require __DIR__ . '/../src/auth.php';
require __DIR__ . '/../src/fluxo.php';
require __DIR__ . '/../src/contrarreferencia.php';

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

$enc = ['status' => 'EM_REGULACAO', 'origem_id' => 1, 'destino_id' => 5];

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

json_response(['ok' => true, 'status' => $novoStatus, 'rotulo' => rotulo_status($novoStatus)]);