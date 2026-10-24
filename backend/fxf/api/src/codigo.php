<?php
declare(strict_types=1);

const ALFABETO_CODIGO = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';

function gerar_codigo_rastreio(int $tamanho = 6): string
{
    $max = strlen(ALFABETO_CODIGO) - 1;
    $parte = '';
    for ($i = 0; $i < $tamanho; $i++) {
        $parte .= ALFABETO_CODIGO[random_int(0, $max)];
    }
    return 'RP-' . $parte;
}

function normalizar_codigo(string $entrada): string
{
    $limpo = strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $entrada) ?? '');
    if (str_starts_with($limpo, 'RP')) {
        $limpo = substr($limpo, 2);
    }
    return 'RP-' . $limpo;
}

function codigo_valido(string $codigo): bool
{
    return (bool) preg_match('/^RP-[' . ALFABETO_CODIGO . ']{6}$/', $codigo);
}