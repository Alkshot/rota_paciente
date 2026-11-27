<?php
declare(strict_types=1);

function validar_contrarreferencia(array $in): array
{
    $erros = [];

    $diagnostico = trim((string) ($in['diagnostico'] ?? ''));
    $conduta     = trim((string) ($in['conduta'] ?? ''));
    $seguimento  = trim((string) ($in['seguimento_ubs'] ?? ''));
    $precisa     = filter_var($in['precisa_retorno'] ?? false, FILTER_VALIDATE_BOOLEAN);
    $prazoDias   = null;

    if (mb_strlen($diagnostico) < 5) {
        $erros['diagnostico'] = 'Descreva o diagnóstico/impressão';
    }
    if (mb_strlen($conduta) < 5) {
        $erros['conduta'] = 'Descreva a conduta adotada';
    }
    if (mb_strlen($seguimento) < 5) {
        $erros['seguimento_ubs'] = 'Informe o que a UBS deve acompanhar';
    }
    foreach (['diagnostico' => $diagnostico, 'conduta' => $conduta, 'seguimento_ubs' => $seguimento] as $campo => $txt) {
        if (mb_strlen($txt) > 2000) {
            $erros[$campo] = 'Texto muito longo (máx. 2000 caracteres)';
        }
    }

    if ($precisa) {
        $prazoDias = filter_var($in['prazo_retorno_dias'] ?? null, FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => 365],
        ]);
        if ($prazoDias === false || $prazoDias === null) {
            $erros['prazo_retorno_dias'] = 'Informe em quantos dias (1 a 365)';
        }
    }

    return [
        'ok'    => $erros === [],
        'erros' => $erros,
        'dados' => [
            'diagnostico'        => $diagnostico,
            'conduta'            => $conduta,
            'seguimento_ubs'     => $seguimento,
            'precisa_retorno'    => $precisa,
            'prazo_retorno_dias' => $prazoDias ?: null,
        ],
    ];
}