<?php require __DIR__ . '/inc/layout.php';
$perfis = [['UBS', 'Criar e acompanhar encaminhamentos', 'visao-geral.php'], ['Especializada', 'Regular, agendar e atender', '#'], ['Hospital', 'Registrar alta e retorno', '#'], ['Gestor', 'Visualizar indicadores da rede', '#']];
?><!doctype html>
<html lang="pt-br">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Rota do Paciente</title>
    <link rel="stylesheet" href="assets/style.css">
</head>

<body>
    <div class="login">
        <div class="l"><b style="font-size:18px">Rota do Paciente</b><small>SAÚDE CONECTADA</small>
            <h1>O cuidado não para no encaminhamento.</h1>
            <p>Acompanhe cada etapa do paciente entre UBS, especializadas e hospitais — com transparência para todos.
            </p>
            <p class="muted" style="color:#c5cbe0">Ambiente demonstrativo com dados fictícios e privacidade por padrão.
            </p>
        </div>
        <div class="r">
            <div class="eyebrow">Acesso demonstrativo</div>
            <h2>Como você quer entrar?</h2>
            <p class="muted" style="margin-bottom:24px">Escolha um perfil para explorar o fluxo completo.</p>
            <?php foreach ($perfis as $p): ?><a class="row"
                    href="<?= $p[2] ?>"><b><?= $p[0] ?></b><span><?= $p[1] ?></span><b>Entrar →</b></a><?php endforeach; ?><a
                class="row" href="paciente.php?codigo=RP-7K3M9Q"><span>É paciente?</span><b>Acompanhar encaminhamento
                    →</b></a>
        </div>
    </div>
</body>

</html>