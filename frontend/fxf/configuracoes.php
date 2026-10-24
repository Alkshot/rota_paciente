<?php require __DIR__.'/inc/layout.php';
$opts=['contra'=>['Novas contrarreferências','Avisar quando um parecer chegar'],'prazo'=>['Alertas de prazo','Avisar antes do vencimento do SLA'],'alta'=>['Altas hospitalares','Avisar quando um paciente receber alta']];
if($_SERVER['REQUEST_METHOD']==='POST'){foreach($opts as $k=>$_)$_SESSION['cfg'][$k]=isset($_POST[$k]);$salvo=true;}
topo('configuracoes','Configurações'); ?>
<div class="wrap"><div class="eyebrow">UBS Jardim das Flores</div><h1 class="t">Configurações</h1><p class="muted">Ajustes essenciais da unidade e dos avisos.</p>
<form method="post" class="card"><b>Preferências de aviso</b><div class="muted">Escolha quais situações devem aparecer como prioridade.</div>
<?php foreach($opts as $k=>$o): ?><label class="tg"><span><b><?=$o[0]?></b><br><span class="muted"><?=$o[1]?></span></span><input type="checkbox" name="<?=$k?>" <?=($_SESSION['cfg'][$k]??true)?'checked':''?>></label><?php endforeach; ?>
<div style="text-align:right;margin-top:20px"><?php if(!empty($salvo)): ?><span class="muted" style="margin-right:12px">Salvo.</span><?php endif; ?><button class="btn">Salvar</button></div></form></div>
<?php rodape();
