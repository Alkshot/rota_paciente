<?php require __DIR__.'/inc/layout.php';
if($_SERVER['REQUEST_METHOD']==='POST'){$_SESSION['lido']=true;header('Location: contrarreferencias.php');exit;}
topo('contrarreferencias','Contrarreferências'); ?>
<div class="wrap"><div class="eyebrow">UBS Jardim das Flores</div><h1 class="t">Contrarreferências</h1>
<?php if(empty($_SESSION['lido'])): ?><p class="muted">Um retorno do especialista aguarda sua leitura.</p>
<div class="card"><div class="row" style="border:0;padding:0"><div><span class="av">CL</span><span class="eyebrow">Oftalmologia • Recebido hoje</span><br><span style="margin-left:48px">Carlos Lima</span></div><span class="pill p-verde">Novo retorno</span></div>
<div class="two"><div><div class="eyebrow">Conduta</div>Iniciado colírio e solicitada campimetria visual.</div><div><div class="eyebrow">Orientação para a UBS</div>Revisar o uso da medicação em 30 dias.</div></div>
<form method="post" style="text-align:right"><button class="btn">Confirmar leitura</button></form></div>
<?php else: ?><p class="muted">Nenhum retorno pendente de leitura.</p><?php endif; ?></div>
<?php rodape();
