<?php
declare(strict_types=1);

function itens_faltantes(array $obrigatoriosIds, array $marcados): array
{
    $marcados = array_map('intval', $marcados);
    return array_values(array_diff(array_map('intval', $obrigatoriosIds), $marcados));
}

function checklist_completo(array $obrigatoriosIds, array $marcados): bool
{
    return itens_faltantes($obrigatoriosIds, $marcados) === [];
}