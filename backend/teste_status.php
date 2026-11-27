<?php
require __DIR__ . '/fxf/api/src/http.php';
require __DIR__ . '/fxf/api/src/auth.php';
iniciar_sessao_segura();
?>
<form method="post" action="fxf/api/mudar_status.php">
    <?php echo csrf_campo(); ?>
    <input name="encaminhamento_id" value="1">
    <input name="novo_status" value="EM_REGULACAO">
    <input name="observacao">
    <button>Enviar</button>
</form>