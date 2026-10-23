<?php
declare(strict_types=1);

const STATUS_VALIDOS = [
    'SOLICITADO', 'EM_REGULACAO', 'AGENDADO', 'ATENDIDO',
    'CONTRARREFERENCIA_ENVIADA', 'CONCLUIDO', 'DEVOLVIDO',
];

const TRANSICOES = [
    'SOLICITADO'   => ['EM_REGULACAO' => ['ESPECIALIZADA']],
    'EM_REGULACAO' => [
        'AGENDADO'  => ['ESPECIALIZADA'],
        'DEVOLVIDO' => ['ESPECIALIZADA'],
    ],
    'DEVOLVIDO'    => ['SOLICITADO' => ['UBS']],
    'AGENDADO'     => ['ATENDIDO' => ['ESPECIALIZADA']],
    'ATENDIDO'     => ['CONTRARREFERENCIA_ENVIADA' => ['ESPECIALIZADA']],
    'CONTRARREFERENCIA_ENVIADA' => ['CONCLUIDO' => ['UBS']],
    'CONCLUIDO'    => [],
];

const EXIGE_OBSERVACAO = ['DEVOLVIDO'];

function validar_transicao(string $de, string $para, string $perfil, ?string $observacao = null): array
{
    if (!in_array($de, STATUS_VALIDOS, true) || !in_array($para, STATUS_VALIDOS, true)) {
        return [false, 'Status inexistente'];
    }
    $destinos = TRANSICOES[$de] ?? [];
    if (!isset($destinos[$para])) {
        return [false, "Transição $de → $para não é permitida"];
    }
    if (!in_array($perfil, $destinos[$para], true)) {
        return [false, "O perfil $perfil não pode executar esta etapa"];
    }
    if (in_array($para, EXIGE_OBSERVACAO, true) && trim((string) $observacao) === '') {
        return [false, 'Informe o motivo da devolução'];
    }
    return [true, null];
}

function acoes_disponiveis(string $statusAtual, string $perfil): array
{
    $acoes = [];
    foreach (TRANSICOES[$statusAtual] ?? [] as $destino => $perfis) {
        if (in_array($perfil, $perfis, true)) {
            $acoes[] = $destino;
        }
    }
    return $acoes;
}

function rotulo_status(string $status): string
{
    return [
        'SOLICITADO'   => 'Solicitado',
        'EM_REGULACAO' => 'Em regulação',
        'AGENDADO'     => 'Agendado',
        'ATENDIDO'     => 'Atendido',
        'CONTRARREFERENCIA_ENVIADA' => 'Retorno enviado à UBS',
        'CONCLUIDO'    => 'Concluído',
        'DEVOLVIDO'    => 'Devolvido para correção',
    ][$status] ?? $status;
}