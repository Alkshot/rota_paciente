<?php
$encaminhamentos=[
['n'=>'Maria Oliveira','i'=>'MO','c'=>'RP-7K3M9Q','e'=>'Cardiologia','s'=>'Em regulação','sc'=>'amarelo','p'=>'Amarela'],
['n'=>'João Santos','i'=>'JS','c'=>'RP-2F8N4L','e'=>'Ortopedia','s'=>'Agendado','sc'=>'azul','p'=>'Verde'],
['n'=>'Ana Costa','i'=>'AC','c'=>'RP-9B1X6R','e'=>'Neurologia','s'=>'Devolvido','sc'=>'rosa','p'=>'Vermelha'],
['n'=>'Carlos Lima','i'=>'CL','c'=>'RP-4D7P2W','e'=>'Oftalmologia','s'=>'Contrarreferência','sc'=>'verde','p'=>'Verde'],
];
$pacientes=[
['Maria Oliveira','MO','62 anos','2 encaminhamentos'],
['João Santos','JS','48 anos','1 encaminhamento'],
['Ana Costa','AC','56 anos','1 pendência'],
['Carlos Lima','CL','71 anos','1 retorno'],
];
$altas=[
['Elza Ferreira','EF','Hospital Municipal','Retorno até 28 out',true],
['Roberto Alves','RA','Hospital Municipal','Agendado para 24 out',false],
['Lúcia Ramos','LR','Hospital Regional','Acompanhamento concluído',false],
];
$demanda=[['Cardiologia',35],['Ortopedia',26],['Neurologia',21]];
$rastreio=[
'RP-7K3M9Q'=>['nome'=>'Maria S.','esp'=>'Cardiologia','atual'=>3,'data'=>'12 de novembro','hora'=>'14:00','local'=>'AME Centro','end'=>'Av. Central, 450',
'etapas'=>[['Pedido enviado','UBS Jardim das Flores • 18 out, 09:14'],['Análise concluída','AME Centro • 19 out, 14:32'],['Consulta agendada','Etapa atual'],['Atendimento realizado','Próxima etapa'],['Retorno enviado à UBS','Próxima etapa']],
'levar'=>['Documento com foto e cartão SUS','Exames recentes e lista de medicamentos']],
];
