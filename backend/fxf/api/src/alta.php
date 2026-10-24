<?php
declare(strict_types=1);

const PRAZO_RETORNO_PADRAO_DIAS = 7;

function calcular_prazo_retorno(string $dataAltaYmd, int $dias = PRAZO_RETORNO_PADRAO_DIAS): string
{
    $data = DateTimeImmutable::createFromFormat('Y-m-d', $dataAltaYmd);
    if ($data === false) {
        throw new InvalidArgumentException('Data de alta inválida');
    }
    return $data->modify("+$dias days")->format('Y-m-d');
}

function dias_para_retorno(string $prazoYmd, ?DateTimeImmutable $hoje = null): int
{
    $hoje ??= new DateTimeImmutable('today');
    $prazo = new DateTimeImmutable($prazoYmd);
    $diff = $hoje->diff($prazo);
    return $diff->invert ? -$diff->days : $diff->days;
}

function validar_alta(array $in): array
{
    $erros = [];
    $resumo = trim((string) ($in['resumo'] ?? ''));
    $data = (string) ($in['data_alta'] ?? date('Y-m-d'));

    if (mb_strlen($resumo) < 5) {
        $erros['resumo'] = 'Informe o resumo da alta';
    }
    $d = DateTimeImmutable::createFromFormat('Y-m-d', $data);
    if ($d === false || $d->format('Y-m-d') !== $data) {
        $erros['data_alta'] = 'Data inválida';
    } elseif ($d > new DateTimeImmutable('today')) {
        $erros['data_alta'] = 'A data da alta não pode ser futura';
    }

    return [
        'ok' => $erros === [],
        'erros' => $erros,
        'dados' => [
            'resumo' => $resumo,
            'data_alta' => $data,
            'prazo_retorno' => $erros ? null : calcular_prazo_retorno($data),
        ],
    ];
}