<?php require __DIR__.'/inc/layout.php'; topo('pacientes','Pacientes');
$q=mb_strtolower(trim($_GET['q']??''));
?>
<div class="wrap"><div class="eyebrow">UBS Jardim das Flores</div><h1 class="t">Pacientes</h1><p class="muted">Consulte os pacientes vinculados à unidade.</p>
<div class="card" style="padding:0"><form style="padding:16px"><input name="q" value="<?=htmlspecialchars($q)?>" placeholder="Buscar por nome ou CNS..." style="padding:10px;width:60%;border:1px solid #e3ded6;border-radius:8px"></form>
<?php foreach($pacientes as $p): if($q&&mb_strpos(mb_strtolower($p[0]),$q)===false)continue; ?><div class="row" style="padding:14px 20px"><div><span class="av"><?=$p[1]?></span><b><?=$p[0]?></b> <span class="muted"><?=$p[2]?></span></div><span><?=$p[3]?> ›</span></div><?php endforeach; ?></div></div>
<?php rodape();
