<?php
declare(strict_types=1);

function dias_parado(string $ultimoEventoDatetime, ?DateTimeImmutable $agora = null): int
{
    $agora ??= new DateTimeImmutable('now');
    $ultimo = new DateTimeImmutable($ultimoEventoDatetime);
    if ($ultimo > $agora) {
        return 0;
    }
    return (int) $ultimo->diff($agora)->days;
}

function esta_atrasado(int $diasParado, int $slaDias): bool
{
    return $diasParado > $slaDias;
}

function status_encerrado(string $status): bool
{
    return $status === 'CONCLUIDO';
}

function anexar_sla(array $linha, ?DateTimeImmutable $agora = null): array
{
    $dias = dias_parado($linha['ultimo_evento_em'], $agora);
    $linha['dias_parado'] = $dias;
    $linha['atrasado'] = !status_encerrado($linha['status'])
        && esta_atrasado($dias, (int) $linha['sla_dias']);
    return $linha;
}