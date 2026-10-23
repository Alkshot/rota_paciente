<?php require __DIR__.'/inc/layout.php';
$cod=strtoupper(trim($_GET['codigo']??'RP-7K3M9Q'));
$r=$rastreio[$cod]??null;
?><!doctype html><html lang="pt-br"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>Acompanhamento</title><link rel="stylesheet" href="assets/style.css"></head><body>
<div class="top" style="justify-content:space-between"><b>Rota do Paciente</b><a class="muted" href="index.php">← Voltar ao início</a></div>
<div class="wrap c">
<?php if(!$r): ?><h1 class="t">Código não encontrado</h1><form><input name="codigo" placeholder="RP-XXXXXX" style="padding:10px"> <button class="btn">Buscar</button></form>
<?php else: ?>
<div class="eyebrow">Código <?=htmlspecialchars($cod)?></div><h1 class="t">Olá, <?=htmlspecialchars($r['nome'])?></h1><p class="muted">Acompanhe abaixo o andamento do seu encaminhamento.</p>
<div class="card"><b style="font-size:16px"><?=$r['esp']?></b><hr style="border:0;border-top:1px solid #eee;margin:16px 0">
<div class="ok"><b>Sua consulta foi agendada.</b><div class="muted">Confira a data e prepare os documentos indicados abaixo.</div></div>
<?php foreach($r['etapas'] as $i=>$e): $n=$i+1; $cl=$n<$r['atual']?'f':($n==$r['atual']?'a':'n'); ?>
<div class="step <?=$cl?>"><div class="d"><?=$cl=='f'?'✓':$n?></div><div><b><?=$e[0]?></b><div class="muted"><?=$e[1]?></div></div></div>
<?php endforeach; ?>
<div class="box"><b>Consulta agendada — <?=$r['esp']?></b><div class="g3"><div><div class="muted">Data</div><b><?=$r['data']?></b></div><div><div class="muted">Horário</div><b><?=$r['hora']?></b></div><div><div class="muted">Local</div><b><?=$r['local']?></b><div class="muted"><?=$r['end']?></div></div></div><b>O que levar</b><?php foreach($r['levar'] as $l): ?><div class="muted">✓ <?=$l?></div><?php endforeach; ?></div></div>
<p class="muted" style="margin-top:20px;text-align:left"><b>Informação segura e simplificada</b><br>Esta página não exibe diagnóstico ou informações clínicas. Em caso de dúvida, procure sua UBS.</p>
<?php endif; ?></div></body></html>
