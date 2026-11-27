<?php require __DIR__.'/inc/layout.php'; topo('encaminhamentos','Encaminhamentos'); ?>
<div class="wrap"><div class="eyebrow">UBS Jardim das Flores</div><h1 class="t">Encaminhamentos</h1><p class="muted">Veja rapidamente onde cada pedido está.</p>
<div class="stats"><div><b>24</b><span class="muted">ativos</span></div><div><b>5</b><span class="muted">precisam de ação</span></div><div><b>2</b><span class="muted">atrasados</span></div></div>
<div class="card" style="padding:0;overflow:hidden"><div style="padding:20px"><b>Pedidos recentes</b><div class="muted">Ordenados pela última movimentação</div></div>
<table class="tb"><tr><th>PACIENTE</th><th>ESPECIALIDADE</th><th>STATUS</th><th>PRIORIDADE</th></tr>
<?php foreach($encaminhamentos as $e): ?><tr><td><span class="av"><?=$e['i']?></span><b><?=$e['n']?></b> <span class="muted"><?=$e['c']?></span></td><td><?=$e['e']?></td><td><span class="pill p-<?=$e['sc']?>"><?=$e['s']?></span></td><td><?=$e['p']?></td></tr><?php endforeach; ?></table></div></div>
<?php rodape();
