<?php
require __DIR__.'/../../../backend/fxf/api/src/auth.php';
iniciar_sessao_segura();
require __DIR__.'/dados.php';
function topo($ativo,$titulo){
$u=exigir_perfil(['UBS','ESPECIALIZADA','HOSPITAL','GESTOR']);
require __DIR__.'/../../../backend/includes/conexao.php';
$st=$pdo->prepare('SELECT nome FROM unidades WHERE unidade_id = ?');
$st->execute([$u['unidade_id']]);
$unidade=(string)$st->fetchColumn();
$menu=['visao-geral'=>'Visão geral','encaminhamentos'=>'Encaminhamentos','pacientes'=>'Pacientes','contrarreferencias'=>'Contrarreferências','altas'=>'Altas recentes','indicadores'=>'Indicadores'];
$lido=!empty($_SESSION['lido']);
echo '<!doctype html><html lang="pt-br"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>'.htmlspecialchars($titulo).' · Rota do Paciente</title><link rel="stylesheet" href="../assets/style.css"></head><body><div class="app"><aside class="side"><div class="brand">Rota do Paciente<div class="muted" style="font-weight:400">Rede integrada de saúde</div></div><div class="un"><div class="eyebrow">Unidade atual</div><b>'.htmlspecialchars($unidade).'</b></div>';
foreach($menu as $k=>$v){
echo '<a class="i'.($ativo==$k?' on':'').'" href="'.$k.'.php">'.$v.(($k=='contrarreferencias'&&!$lido)?'<span class="badge">1</span>':'').'</a>';
}
echo '<div class="sp"></div><a class="i'.($ativo=='configuracoes'?' on':'').'" href="configuracoes.php">Configurações</a><a class="i" href="../../backend/logoff.php">Sair</a><div class="un"><b>Ambiente seguro</b><div class="muted">Dados protegidos pela LGPD</div></div><div><b>'.htmlspecialchars($u['nome']).'</b><div class="muted">'.htmlspecialchars($u['perfil']).'</div></div></aside><div class="main"><div class="top"><input placeholder="Buscar paciente, CNS ou código de rastreio..."><div class="u"><b>'.htmlspecialchars($u['nome']).'</b><br><span class="muted">'.htmlspecialchars($u['perfil']).'</span></div></div>';
return $u + ['unidade'=>$unidade];
}
function rodape(){echo '</div></div></body></html>';}