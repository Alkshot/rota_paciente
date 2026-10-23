<?php require __DIR__.'/inc/layout.php'; topo('visao-geral','Visão geral'); ?>
<div class="wrap c"><div class="eyebrow">UBS Jardim das Flores</div><h1 class="t">Bom dia, Marina.</h1><p class="muted">Você tem 5 encaminhamentos que precisam de atenção.</p>
<div class="card"><b>Profissional da UBS</b><div class="stats"><div><b>24</b><span class="muted">ativos</span></div><div><b>5</b><span class="muted">aguardando ação</span></div><div><b><?=empty($_SESSION['lido'])?1:0?></b><span class="muted">retorno recebido</span></div></div>
<div class="box"><div class="eyebrow">Próxima ação</div><b>Ana Costa</b><div class="muted">Neurologia • documento pendente</div></div>
<div style="text-align:right;margin-top:20px"><a class="btn" href="encaminhamentos.php">Novo encaminhamento →</a></div></div></div>
<?php rodape();
