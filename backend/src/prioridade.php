<?php
declare(strict_types=1);

const PESO_COR = ['VERMELHO' => 0, 'AMARELO' => 1, 'VERDE' => 2];

function idade_em_anos(string $nascimentoYmd, ?DateTimeImmutable $hoje = null): int
{
    $hoje ??= new DateTimeImmutable('today');
    $nasc = DateTimeImmutable::createFromFormat('Y-m-d', $nascimentoYmd);
    if ($nasc === false) {
        throw new InvalidArgumentException('Data de nascimento inválida');
    }
    return $nasc->diff($hoje)->y;
}

function calcular_prioridade(int $idade, int $qtdComorbidades, int $qtdSinaisAlarme): array
{
    $pontos = 0;
    $pontos += $idade >= 60 ? 1 : 0;
    $pontos += max(0, $qtdComorbidades);
    $pontos += 3 * max(0, $qtdSinaisAlarme);

    $cor = match (true) {
        $pontos >= 5 => 'VERMELHO',
        $pontos >= 3 => 'AMARELO',
        default      => 'VERDE',
    };

    return ['pontos' => $pontos, 'cor' => $cor];
}

function comparar_fila(array $a, array $b): int
{
    return [PESO_COR[$a['prioridade']] ?? 9, $a['criado_em']]
       <=> [PESO_COR[$b['prioridade']] ?? 9, $b['criado_em']];
}

function emoji_prioridade(string $cor): string
{
    return ['VERDE' => '🟢', 'AMARELO' => '🟡', 'VERMELHO' => '🔴'][$cor] ?? '⚪';
}