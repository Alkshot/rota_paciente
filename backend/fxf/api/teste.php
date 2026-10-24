<?php
declare(strict_types=1);

require __DIR__ . '/src/fluxo.php';
require __DIR__ . '/src/prioridade.php';
require __DIR__ . '/src/codigo.php';
require __DIR__ . '/src/paciente_view.php';
require __DIR__ . '/src/sla.php';
require __DIR__ . '/src/alta.php';
require __DIR__ . '/src/checklist.php';
require __DIR__ . '/src/whatsapp.php';

if (PHP_SAPI !== 'cli') {
    header('Content-Type: text/plain; charset=utf-8');
}

function checar(string $nome, mixed $obtido, mixed $esperado): void
{
    $ok = $obtido === $esperado;
    echo ($ok ? '[OK]   ' : '[FALHOU] ') . $nome;
    if (!$ok) {
        echo ' => obtido: ' . json_encode($obtido, JSON_UNESCAPED_UNICODE)
           . ' | esperado: ' . json_encode($esperado, JSON_UNESCAPED_UNICODE);
    }
    echo PHP_EOL;
}

checar('pular etapa é bloqueado', validar_transicao('SOLICITADO', 'AGENDADO', 'ESPECIALIZADA')[0], false);
checar('UBS não agenda', validar_transicao('EM_REGULACAO', 'AGENDADO', 'UBS')[0], false);
checar('devolver sem motivo é bloqueado', validar_transicao('EM_REGULACAO', 'DEVOLVIDO', 'ESPECIALIZADA')[0], false);
checar('devolver com motivo', validar_transicao('EM_REGULACAO', 'DEVOLVIDO', 'ESPECIALIZADA', 'Falta ECG')[0], true);
checar('UBS reenvia devolvido', validar_transicao('DEVOLVIDO', 'SOLICITADO', 'UBS')[0], true);
checar('ações da UBS em contrarreferência', acoes_disponiveis('CONTRARREFERENCIA_ENVIADA', 'UBS'), ['CONCLUIDO']);

checar('prioridade vermelha', calcular_prioridade(62, 1, 1), ['pontos' => 5, 'cor' => 'VERMELHO']);
checar('prioridade amarela', calcular_prioridade(40, 0, 1), ['pontos' => 3, 'cor' => 'AMARELO']);
checar('prioridade verde', calcular_prioridade(30, 1, 0), ['pontos' => 1, 'cor' => 'VERDE']);

$fila = [
    ['prioridade' => 'VERDE', 'criado_em' => '2026-10-01'],
    ['prioridade' => 'VERMELHO', 'criado_em' => '2026-10-10'],
    ['prioridade' => 'VERMELHO', 'criado_em' => '2026-10-05'],
];
usort($fila, 'comparar_fila');
checar('ordem da fila', array_column($fila, 'criado_em'), ['2026-10-05', '2026-10-10', '2026-10-01']);

$codigo = gerar_codigo_rastreio();
checar('código gerado é válido', codigo_valido($codigo), true);
checar('normalizar código', normalizar_codigo('rp 7k3m9q'), 'RP-7K3M9Q');

checar('nome abreviado', nome_abreviado('maria aparecida dos Santos'), 'Maria S.');
checar('timeline devolvido vira em análise', montar_timeline('DEVOLVIDO')[1]['estado'], 'atual');
checar('timeline concluído tudo feito', array_unique(array_column(montar_timeline('CONCLUIDO'), 'estado')), ['feito']);
$pub = dados_publicos([
    'codigo' => 'RP-7K3M9Q', 'paciente_nome' => 'Maria Souza', 'especialidade' => 'Cardiologia',
    'status' => 'AGENDADO', 'diagnostico' => 'segredo',
]);
checar('página pública não vaza campo extra', array_key_exists('diagnostico', $pub), false);

$agora = new DateTimeImmutable('2026-10-23 12:00:00');
checar('dias parado', dias_parado('2026-10-13 12:00:00', $agora), 10);
checar('atrasado', anexar_sla(['ultimo_evento_em' => '2026-10-13 12:00:00', 'status' => 'EM_REGULACAO', 'sla_dias' => 5], $agora)['atrasado'], true);
checar('concluído nunca atrasa', anexar_sla(['ultimo_evento_em' => '2026-01-01 12:00:00', 'status' => 'CONCLUIDO', 'sla_dias' => 5], $agora)['atrasado'], false);

checar('prazo de retorno da alta', calcular_prazo_retorno('2026-10-20'), '2026-10-27');
checar('alta futura é rejeitada', validar_alta(['resumo' => 'Alta sem intercorrências', 'data_alta' => '2099-01-01'])['ok'], false);

checar('checklist incompleto', itens_faltantes([1, 2, 3], [1, 3]), [2]);
checar('checklist completo', checklist_completo([1, 2], [2, 1]), true);

checar('telefone normalizado', normalizar_telefone_br('(51) 99999-8888'), '5551999998888');
checar('telefone inválido', normalizar_telefone_br('123'), null);
checar('link whatsapp', str_starts_with((string) link_whatsapp('51999998888', 'Olá'), 'https://wa.me/5551999998888?text='), true);

