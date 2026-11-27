<?php require __DIR__.'/inc/layout.php'; topo('altas','Altas recentes'); ?>
<div class="wrap"><div class="eyebrow">UBS Jardim das Flores</div><h1 class="t">Altas recentes</h1><p class="muted">Organize o retorno à UBS após a alta hospitalar.</p>
<div class="card" style="padding:0"><div class="row" style="border:0;padding:20px"><div><b>Retornos após alta</b><div class="muted">Prazo recomendado: até 7 dias</div></div><span class="pill p-amarelo">2 pendentes</span></div>
<?php foreach($altas as $a): ?><div class="row" style="padding:14px 20px"><div><span class="av"><?=$a[1]?></span><b><?=$a[0]?></b> <span class="muted"><?=$a[2]?></span></div><div><span><?=$a[3]?></span> <?php if($a[4]): ?><a class="btn" style="padding:8px 14px;margin-left:12px" href="#">Agendar</a><?php endif; ?></div></div><?php endforeach; ?></div></div>
<?php rodape();
