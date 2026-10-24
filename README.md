# Rota do Paciente

## Sumário

1. [De onde veio a ideia](#1-de-onde-veio-a-ideia)
2. [O problema](#2-o-problema)
3. [O que a realidade mostra](#3-o-que-a-realidade-mostra)
4. [A nossa proposta](#4-a-nossa-proposta)
5. [Quem usa e para quê](#5-quem-usa-e-para-quê)
6. [O caminho de um encaminhamento](#6-o-caminho-de-um-encaminhamento)
7. [Como cada parte funciona](#7-como-cada-parte-funciona)
8. [A tela do paciente](#8-a-tela-do-paciente)
9. [Um exemplo do começo ao fim](#9-um-exemplo-do-começo-ao-fim)
10. [Diferença para os sistemas de regulação](#10-diferença-para-os-sistemas-de-regulação)
11. [O que o protótipo mostra e o que fica de fora](#11-o-que-o-protótipo-mostra-e-o-que-fica-de-fora)
12. [Próximos passos](#12-próximos-passos)
13. [Fontes](#13-fontes)

---

## 1. De onde veio a ideia

O tema do hackathon tem quatro eixos: as UBS (os postos de saúde), as unidades especializadas (cardiologia, neurologia e outras), os hospitais e a ligação entre os três. Quando discutimos os problemas de cada um, percebemos que o que mais aparecia era o mesmo: as coisas se perdem na passagem de um ponto para o outro.

O posto pede, a especializada recebe, o hospital interna, e cada um guarda a sua parte da história. O paciente fica no meio sem saber o que está acontecendo. Resolvemos trabalhar justamente nessa passagem, que é o eixo da interligação.

## 2. O problema

Pense na dona Maria, 62 anos, hipertensa. Ela vai ao posto e a médica diz que ela precisa de um cardiologista. A médica faz o pedido, e a partir daí começa uma espera sem informação:

- Maria volta para casa e não sabe se o pedido foi aceito, em que fila ele está ou quando será chamada.
- Se o pedido voltar porque faltou um exame, quase sempre ninguém avisa. Ela só descobre meses depois.
- Quando ela finalmente consulta com o especialista, o que ele concluiu e o que o posto deve acompanhar nem sempre volta para a UBS.
- Se ela for internada, o posto muitas vezes não sabe, e depois da alta ela fica sem acompanhamento nos dias mais delicados.
- Quem administra a saúde da cidade não tem como ver onde os pedidos ficam parados.

Cada um desses pontos é pequeno, mas somados deixam o paciente perdido e a rede sem enxergar a si mesma.

## 3. O que a realidade mostra

Procuramos notícias e relatórios recentes para ver se esse problema aparece fora da nossa imaginação. Aparece.

- Em junho de 2026, 430.223 pessoas esperavam por consulta especializada na rede municipal de São Paulo. A espera média era de 216 dias úteis, e 119 pessoas aguardavam havia cerca de cinco anos.
- Em Minas Gerais, uma reportagem de setembro de 2026 mostra que não existe uma fila estadual unificada. Cidades como Belo Horizonte, Juiz de Fora e Uberlândia mantêm filas separadas, e um pesquisador da Fiocruz Minas afirma que o estado não sabe, de forma homogênea, se as filas estão diminuindo.
- O Tribunal de Contas da União apontou, em auditoria de 2026, que muitos municípios não conseguem integrar seus dados ao sistema federal. Segundo o tribunal, só 6,8% das cirurgias realizadas em 2024 foram registradas na Rede Nacional de Dados em Saúde.

Os números mostram filas grandes, mas o que mais nos chamou atenção foi a falta de visibilidade: nem o paciente nem o gestor conseguem acompanhar direito o que está acontecendo. É isso que o projeto tenta resolver. Ele não cria novas vagas, e deixamos isso claro para não prometer o que não podemos entregar.

## 4. A nossa proposta

Funciona como o rastreio de uma encomenda. Quando alguém compra algo pela internet, recebe um código e vê cada etapa: pedido feito, saiu do depósito, a caminho, entregue. Fizemos o mesmo com o pedido médico.

Cada encaminhamento ganha um código, por exemplo RP-7K3M9Q, e passa por etapas visíveis. Cada ponto da rede (posto, especializada, hospital) cuida da sua etapa, e o paciente e o gestor veem o resultado em tempo real.

Além do rastreio, o sistema impõe algumas regras que atacam diretamente os problemas da seção 2: o posto precisa enviar o pedido completo, o especialista precisa devolver o resultado, e a alta do hospital gera um retorno no posto. Essas regras são o diferencial do projeto.

O sistema foi pensado para a rede municipal de saúde, e o gestor que o utiliza é o gestor municipal, ou seja, o secretário de saúde e, em última instância, o prefeito.

## 5. Quem usa e para quê

O sistema tem cinco tipos de usuário, e cada um enxerga uma tela diferente.

**Profissional da UBS.** Cria o encaminhamento, preenche o que a especialidade exige, acompanha os pedidos do seu posto e recebe de volta o resultado do especialista. Também vê a lista de pacientes que tiveram alta no hospital e precisam de acompanhamento.

**Profissional da especializada.** Recebe os pedidos, analisa, marca a consulta, atende e devolve o resultado para o posto. Se faltar alguma informação, devolve o pedido explicando o motivo.

**Profissional do hospital.** Registra a alta do paciente, com um resumo e os medicamentos prescritos.

**Gestor municipal.** Só consulta. Vê um painel com a situação geral da rede e os pontos onde os pedidos estão parados.

**Paciente.** Não precisa de cadastro nem senha. Acessa com o código do seu encaminhamento e acompanha o andamento.

## 6. O caminho de um encaminhamento

Todo encaminhamento segue as mesmas etapas, nesta ordem:

1. **Pedido enviado.** O posto criou o encaminhamento.
2. **Em análise.** A especializada está avaliando o pedido.
3. **Consulta agendada.** Já existem data, horário e local definidos.
4. **Atendimento realizado.** O paciente foi atendido pelo especialista.
5. **Retorno enviado à UBS.** O especialista devolveu o resultado para o posto.
6. **Concluído.** O posto leu o retorno e assumiu o acompanhamento do paciente.

Existe ainda um desvio. Se a especializada perceber que falta algo no pedido, ela o devolve ao posto com o motivo escrito. O posto corrige, reenvia, e o caminho continua de onde parou.

O sistema não deixa ninguém pular etapas. Um posto, por exemplo, não consegue marcar um paciente como atendido, porque essa etapa pertence à especializada. Cada passo registra quem fez e quando, e é esse histórico que alimenta a tela do paciente e o painel do gestor.

## 7. Como cada parte funciona

### 7.1 Criar o encaminhamento

O profissional do posto escolhe o paciente, a especialidade de destino, descreve o motivo do pedido e marca se há algum sinal de alarme. Ao enviar, o sistema gera o código de rastreio e mostra uma tela de confirmação com um QR code, que pode ser impresso ou mostrado ao paciente. Também há um botão que abre uma mensagem pronta de WhatsApp com o código e o link de acompanhamento, para o profissional só precisar enviar.

### 7.2 Lista de exigências por especialidade

Cada especialidade tem uma lista do que precisa acompanhar o pedido. Para cardiologia, por exemplo: eletrocardiograma recente, pressão aferida, lista de medicamentos em uso e exames de colesterol e glicemia.

O botão de enviar fica bloqueado até o profissional marcar todos os itens obrigatórios. A intenção é evitar um problema comum: o pedido que chega incompleto, volta, e faz o paciente perder semanas. As exigências que usamos são exemplos. Em um uso real, a lista seria definida pelos protocolos clínicos do município e poderia ser editada pela gestão.

### 7.3 Semáforo de prioridade

Nem todos os pedidos têm a mesma urgência, e a ordem de chegada nem sempre é a mais justa. O sistema faz uma classificação simples em três cores:

- Verde: situação de menor risco.
- Amarelo: atenção intermediária.
- Vermelho: risco maior, que deve ser visto primeiro.

A cor sai de uma soma de pontos. Idade de 60 anos ou mais soma um ponto, cada doença crônica relevante soma um ponto, e cada sinal de alarme (como dor no peito ou perda súbita de força) soma três. Quem tem uma pontuação maior sobe na fila da especializada.

É importante dizer que essa regra é uma ajuda para a equipe e não um protocolo clínico validado. A decisão final continua sendo sempre do profissional de saúde. Em uma versão real, os critérios viriam dos protocolos oficiais de regulação.

### 7.4 Alerta de atraso

Cada especialidade tem um prazo razoável para que um pedido fique parado em uma mesma etapa. Se esse prazo estoura, o pedido aparece destacado em vermelho, com a contagem de dias, na tela da especializada e no painel do gestor. Assim, ninguém precisa descobrir por acaso que um pedido ficou esquecido.

### 7.5 Resultado obrigatório (contrarreferência)

Para fechar um atendimento, o especialista precisa preencher um pequeno retorno com quatro informações: o que concluiu sobre o caso, o que foi feito, o que o posto deve acompanhar e se o paciente precisa voltar ao especialista.

Quando o retorno é enviado, a UBS recebe um aviso destacado na sua tela. Só depois que o posto abre e confirma a leitura o caso é considerado concluído. Isso transforma em etapa obrigatória algo que hoje depende da boa vontade de cada profissional.

### 7.6 Alta do hospital

Alta é o momento em que o paciente sai do hospital porque pode continuar o tratamento fora dele. É um ponto delicado: ele sai com remédios novos e orientações, mas o posto onde ele é acompanhado muitas vezes nem sabe que ele esteve internado, e sem acompanhamento nos primeiros dias há risco de ele voltar ao hospital.

No sistema, o hospital registra a alta com um resumo curto e os medicamentos prescritos. A partir disso, o sistema cria automaticamente uma tarefa para o posto de referência do paciente: atendê-lo em até sete dias. Ela aparece na área de altas recentes da UBS. O prazo de sete dias foi uma escolha nossa para o protótipo, e em uso real seria definido pela rede.

### 7.7 Aviso por WhatsApp

Quando uma consulta é agendada, o profissional pode abrir uma mensagem pronta de WhatsApp com os dados da consulta e o link de acompanhamento, como "Olá, Maria. Sua consulta de Cardiologia foi agendada para 12/11 às 14h". O envio é manual, o sistema apenas prepara o texto. Uma versão completa usaria os canais oficiais de envio automático.

### 7.8 Painel do gestor

O gestor municipal tem uma tela só de consulta, com os principais indicadores da rede:

- quantos encaminhamentos existem em cada etapa;
- quanto tempo, em média, um pedido leva em cada etapa;
- quais especialidades têm mais pedidos atrasados, o que mostra onde está o gargalo;
- quantos casos são verdes, amarelos e vermelhos;
- a lista dos casos mais graves que estão parados.

A ideia é que quem decide possa ver onde agir, em vez de depender de relatos soltos.

## 8. A tela do paciente

É a parte do sistema pensada para quem menos entende de tecnologia, e por isso foi feita para ser simples. É uma página que abre no navegador do celular, sem aplicativo, sem cadastro e sem senha.

O paciente chega até ela de três formas: apontando a câmera para o QR code do comprovante, clicando no link recebido por WhatsApp, ou digitando o código na página inicial.

Ao entrar, ele vê:

- o primeiro nome e a inicial do sobrenome (por exemplo, Maria S.);
- a especialidade do encaminhamento;
- uma linha do tempo com as etapas, em que as concluídas aparecem marcadas em verde, a etapa atual fica destacada e as próximas aparecem apagadas;
- uma frase em linguagem simples sobre o momento atual, como "Sua consulta foi agendada";
- quando a consulta está marcada, a data, o horário, o local e o que levar.

A página não mostra diagnóstico, motivo clínico nem observações dos profissionais. Como qualquer pessoa com o código poderia abrir a página, ela exibe só o mínimo necessário para o paciente se orientar. Por isso o código tem uma parte aleatória, para que seja difícil acertar o de outra pessoa.

## 9. Um exemplo do começo ao fim

1. A médica do posto atende a dona Maria e decide encaminhá-la ao cardiologista. No sistema, ela só consegue enviar o pedido depois de confirmar que o eletrocardiograma e os outros exames estão anexados. O sistema classifica o caso como amarelo e gera o código RP-7K3M9Q e o QR code.
2. Maria recebe o link pelo WhatsApp, abre no celular e vê que o pedido foi enviado.
3. A especializada recebe o pedido, que aparece na posição certa da fila por causa da cor. Analisa, aceita e marca a consulta. Maria vê, no mesmo link, que a consulta foi agendada, com data, horário, local e o que levar.
4. Depois da consulta, o cardiologista preenche o retorno. O posto recebe o aviso e Maria vê que o resultado foi enviado.
5. Se, nesse meio tempo, ela tivesse sido internada, o hospital registraria a alta e o posto receberia a tarefa de atendê-la em até sete dias.
6. Do outro lado, o gestor abre o painel e consegue ver, por exemplo, que a cardiologia tem vários pedidos parados há mais tempo do que o prazo, e sabe onde precisa agir.

## 10. Diferença para os sistemas de regulação

Existem sistemas de regulação em uso no SUS. Um exemplo é o Gercon, desenvolvido pela Procempa, de Porto Alegre, e usado em outras regiões do Rio Grande do Sul e em Goiás. Pelo que encontramos, ele organiza a fila de consultas especializadas e permite ao gestor estadual acompanhar a movimentação dos pedidos.

O Rota do Paciente não pretende substituir esse tipo de sistema. A regulação decide quem entra na fila e quando. O nosso projeto olha para o caminho completo do paciente dentro da rede municipal: o pedido completo na origem, o retorno do resultado ao posto, a ligação com o hospital depois da alta e a visão do gestor da cidade.

Em uma situação real, os dois poderiam funcionar juntos. Quando o pedido precisar passar pela regulação estadual, o Rota do Paciente continuaria acompanhando e mostrando o andamento para o paciente e para o posto.

## 11. O que o protótipo mostra e o que fica de fora

Por causa do tempo do hackathon, o que desenvolvemos é um protótipo. Dizemos isso abertamente.

**O que o protótipo apresenta:**
- a tela de entrada, com acesso do paciente pelo código e acesso dos profissionais;
- a tela de cadastro de profissionais;
- a tela do paciente, com a linha do tempo que muda conforme a etapa do encaminhamento;
- a tela inicial do profissional da UBS.

**O que fica de fora ou é simulado:**
- todos os dados são fictícios;
- os códigos de teste apresentados na demonstração são exemplos preparados para mostrar as diferentes etapas;
- as telas da especializada, do hospital e do gestor, e o formulário de novo encaminhamento, estão descritas neste documento mas ainda não foram construídas;
- não existe integração com sistemas oficiais como o e-SUS ou os sistemas de regulação;
- o envio por WhatsApp é um atalho manual, e anexos de exames seriam apenas marcados como entregues.

## 12. Próximos passos

Se o projeto continuasse, o caminho natural seria:

- construir as telas que faltam, começando pelo formulário de novo encaminhamento com a lista de exigências;
- validar com profissionais de saúde as listas de exigências, os critérios do semáforo e os prazos de atraso;
- testar com uma UBS e uma especializada reais, em um recorte pequeno, para medir se os pedidos devolvidos diminuem;
- estudar a integração com os sistemas de regulação e com a Rede Nacional de Dados em Saúde;
- revisar com cuidado a proteção dos dados dos pacientes, conforme a LGPD, antes de qualquer uso real.

## 13. Fontes

- Poder360, [Fila por especialista em São Paulo tem espera de até 5 anos](https://www.poder360.com.br/opiniao/fila-por-especialista-em-sao-paulo-tem-espera-de-ate-5-anos/), 12 de agosto de 2026.
- Brasil de Fato, [Falta de articulação do governo estadual expõe filas por consultas e exames no SUS em MG](https://www.brasildefato.com.br/2026/09/18/falta-de-articulacao-do-governo-estadual-expoe-filas-por-consultas-e-exames-no-sus-em-mg/), 18 de setembro de 2026.
- TCU, [TCU avalia programa Agora Tem Especialistas do Ministério da Saúde](https://portal.tcu.gov.br/imprensa/noticias/tcu-avalia-programa-agora-tem-especialistas-do-ministerio-da-saude), Acórdão 1622/2026.
- Procempa, [Procempa implementa sistema de gerenciamento de consultas para uso em todo Estado](https://prefeitura.poa.br/procempa/noticias/procempa-implementa-sistema-de-gerenciamento-de-consultas-para-uso-em-todo-estado).
