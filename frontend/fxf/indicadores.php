<?php require __DIR__.'/inc/layout.php'; topo('indicadores','Indicadores'); ?>
<div class="wrap"><div class="eyebrow">UBS Jardim das Flores</div><h1 class="t">Indicadores</h1><p class="muted">Uma visão simples do desempenho da unidade.</p>
<div class="stats"><div><b>42</b><span class="muted">criados no mês</span></div><div><b>78%</b><span class="muted">concluídos</span></div><div><b>4,8 dias</b><span class="muted">até regulação</span></div></div>
<div class="card"><b>Demanda por especialidade</b><div class="muted" style="margin-bottom:20px">Últimos 30 dias</div>
<?php foreach($demanda as $d): ?><div class="row" style="border:0;padding:0"><span><?=$d[0]?></span><b><?=$d[1]?>%</b></div><div class="bar"><i style="width:<?=$d[1]*2?>%"></i></div><?php endforeach; ?></div></div>
<?php rodape();
