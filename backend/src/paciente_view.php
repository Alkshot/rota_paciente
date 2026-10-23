<?php
declare(strict_types=1);

const ETAPAS_TIMELINE = [
    'SOLICITADO'   => 'Pedido feito',
    'EM_REGULACAO' => 'Em análise',
    'AGENDADO'     => 'Consulta agendada',
    'ATENDIDO'     => 'Consulta realizada',
    'CONTRARREFERENCIA_ENVIADA' => 'Resultado enviado ao postinho',
    'CONCLUIDO'    => 'Acompanhamento no postinho',
];

function nome_abreviado(string $nomeCompleto): string
{
    $partes = preg_split('/\s+/', trim($nomeCompleto), -1, PREG_SPLIT_NO_EMPTY) ?: [];
    if ($partes === []) {
        return '';
    }
    $ligacoes = ['de', 'da', 'do', 'das', 'dos', 'e'];
    $primeiro = mb_convert_case($partes[0], MB_CASE_TITLE, 'UTF-8');

    for ($i = count($partes) - 1; $i > 0; $i--) {
        if (!in_array(mb_strtolower($partes[$i]), $ligacoes, true)) {
            return $primeiro . ' ' . mb_strtoupper(mb_substr($partes[$i], 0, 1)) . '.';
        }
    }
    return $primeiro;
}

function montar_timeline(string $status): array
{
    $chaves = array_keys(ETAPAS_TIMELINE);
    $indiceAtual = $status === 'DEVOLVIDO'
        ? array_search('EM_REGULACAO', $chaves, true)
        : array_search($status, $chaves, true);

    if ($indiceAtual === false) {
        $indiceAtual = 0;
    }

    $timeline = [];
    foreach ($chaves as $i => $chave) {
        $estado = $i < $indiceAtual ? 'feito' : ($i === $indiceAtual ? 'atual' : 'pendente');
        if ($status === 'CONCLUIDO') {
            $estado = 'feito';
        }
        $timeline[] = ['status' => $chave, 'rotulo' => ETAPAS_TIMELINE[$chave], 'estado' => $estado];
    }
    return $timeline;
}

function frase_para_paciente(string $status): string
{
    return [
        'SOLICITADO'   => 'Seu pedido foi enviado e está aguardando análise.',
        'EM_REGULACAO' => 'Seu pedido está sendo analisado. Aguarde o agendamento.',
        'DEVOLVIDO'    => 'Seu pedido está sendo ajustado pelo postinho e será reenviado em breve.',
        'AGENDADO'     => 'Sua consulta está marcada! Veja data, hora e local abaixo.',
        'ATENDIDO'     => 'Você já foi atendido(a). O resultado será enviado ao seu postinho.',
        'CONTRARREFERENCIA_ENVIADA' => 'O resultado já foi enviado ao seu postinho. Procure a equipe para o acompanhamento.',
        'CONCLUIDO'    => 'Tudo certo! Seu acompanhamento continua no postinho.',
    ][$status] ?? 'Situação em atualização.';
}

function dados_publicos(array $enc): array
{
    return [
        'codigo'       => $enc['codigo'],
        'paciente'     => nome_abreviado($enc['paciente_nome']),
        'especialidade'=> $enc['especialidade'],
        'status'       => $enc['status'],
        'frase'        => frase_para_paciente($enc['status']),
        'timeline'     => montar_timeline($enc['status']),
        'agendamento'  => $enc['status'] === 'AGENDADO' ? ($enc['agendamento'] ?? null) : null,
    ];
}