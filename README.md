# Rota do Paciente

> Um site que mostra, em tempo real, onde está o pedido médico de cada paciente, obriga a rede de saúde a trabalhar de forma completa e mostra ao gestor onde as coisas estão travando.

Protótipo desenvolvido para o Hackathon, tema **Saúde Conectada**.

| | |
|---|---|
| **Eixo principal** | Interligação (UBS ↔ Especializadas ↔ Hospitais) |
| **Stack** | HTML, CSS, JavaScript, PHP e MySQL |
| **Equipe** | 5 pessoas |
| **Prazo** | 1 dia |

> **Nome:** "Rota do Paciente" é o nome de trabalho. Alternativas consideradas: **Elo**, RastreiaSUS, TrilhaSaúde, ConectaCuidado, Ponte SUS.

---

## Sumário

1. [A ideia em palavras simples](#1-a-ideia-em-palavras-simples)
2. [O problema](#2-o-problema)
3. [A solução](#3-a-solução)
4. [Quem usa o sistema](#4-quem-usa-o-sistema)
5. [A interface da paciente](#5-a-interface-da-paciente)
6. [Ciclo de vida do encaminhamento](#6-ciclo-de-vida-do-encaminhamento)
7. [Funcionalidades em detalhe](#7-funcionalidades-em-detalhe)
8. [Exemplo completo, do começo ao fim](#8-exemplo-completo-do-começo-ao-fim)
9. [O que é real e o que é simulado](#9-o-que-é-real-e-o-que-é-simulado)
10. [Prioridade de entrega](#10-prioridade-de-entrega)
11. [Banco de dados](#11-banco-de-dados)
12. [Divisão do time](#12-divisão-do-time)
13. [Cronograma de 1 dia](#13-cronograma-de-1-dia)
14. [Roteiro da demo](#14-roteiro-da-demo)
15. [Cuidados técnicos e segurança](#15-cuidados-técnicos-e-segurança)
16. [Pontos para o pitch](#16-pontos-para-o-pitch)
17. [Ideias complementares](#17-ideias-complementares)

**Regra do protótipo:** tudo que o jurado vê tem que funcionar de verdade no fluxo principal. O que seria caro de fazer (integrações, upload real, notificações reais) é simulado, e o grupo diz isso abertamente no pitch.

---

## 1. A ideia em palavras simples

A dona Maria vai ao postinho de saúde. O médico diz: "A senhora precisa de um médico do coração. Vou fazer um pedido."

Maria volta para casa e **não sabe nada**: o pedido foi aceito? Quando será a consulta? Ninguém avisa.

A nossa ideia é **a mesma coisa que o rastreio dos Correios, mas para o pedido médico**. Quando você compra algo na internet, recebe um código e vê: "pedido feito → saiu do depósito → a caminho → entregue". O pedido de Maria ganha um código, como `RP-7K3M9Q`, e ela acompanha cada etapa pelo celular.

> ✅ Pedido feito → ✅ Em análise → ⏳ **Aguardando data da consulta** → ⬜ Consulta → ⬜ Resultado volta para o postinho

### Resumo em uma frase

**É um site que mostra onde está o pedido médico de cada paciente, exige que cada etapa da rede seja feita completa e dá ao gestor um painel dos gargalos.**

---

## 2. O problema

Quando uma UBS encaminha um paciente a um especialista, o pedido entra em um "buraco negro":

- O **paciente** não sabe se o pedido foi aceito, em que fila está ou quando será chamado.
- A **UBS** não sabe se o encaminhamento foi devolvido por falta de exame.
- O **especialista** atende, mas a UBS nunca recebe o retorno (o que foi diagnosticado e o que a UBS deve acompanhar).
- O **gestor** não enxerga onde a rede trava.

## 3. A solução

Cada encaminhamento vira um "pacote" com **código de rastreio** e um histórico de etapas. Cada ponto da rede atualiza a sua parte, e paciente, UBS e gestor veem em que pé está.

### Diferenciais (as "pegadinhas inteligentes")

1. **Link/QR público para o paciente.** Sem login, ele vê uma linha do tempo pelo celular.
2. **Lista de exigências por especialidade.** Para pedir cardiologista, a UBS é obrigada a marcar que anexou o eletrocardiograma e os outros exames. Se faltar, o botão de enviar fica travado.
3. **Semáforo de prioridade.** Verde, amarelo e vermelho mostram quem está mais grave. Quem tem dor no peito passa na frente de quem só faz check-up. Sem IA, só regras.
4. **Alerta de atraso (SLA).** Pedido parado tempo demais fica vermelho no painel.
5. **Resultado obrigatório (contrarreferência).** O especialista só fecha o caso escrevendo o que descobriu e o que a UBS deve fazer.
6. **Alta hospitalar com retorno automático.** O hospital registra a alta e a UBS recebe o aviso: "esse paciente deve voltar em até 7 dias".
7. **Aviso por WhatsApp sem API.** Botão que abre uma mensagem pronta (`wa.me`).
8. **Painel do gestor.** Gráficos de gargalos e tempos por etapa.

---

## 4. Quem usa o sistema

Cada perfil vê uma tela diferente.

| Perfil | O que faz no sistema |
|---|---|
| **UBS** (profissional do postinho) | Cria o encaminhamento, preenche o checklist, recebe a contrarreferência e acompanha retornos |
| **ESPECIALIZADA** (regulador/especialista) | Recebe a fila, confirma o agendamento, atende e preenche a contrarreferência |
| **HOSPITAL** | Registra a alta do paciente |
| **GESTOR** | Só visualiza o painel com indicadores |
| **Paciente** | Sem login. Acessa pelo código ou QR e só vê o andamento |

**No protótipo:** 4 usuários fixos de teste (um por perfil, com senha), criados no script SQL. Sem cadastro de usuários nem recuperação de senha. Para a demo, a tela de login pode ter botões "entrar como UBS / Especializada / Hospital / Gestor".

### O que é o campo `perfil` no banco de dados

O campo `perfil`, na tabela `usuarios`, diz **que tipo de funcionário** é aquela pessoa. É ele que decide **o que ela pode ver e fazer**.

| id | nome | perfil | unidade_id |
|---|---|---|---|
| 1 | Dra. Ana | `UBS` | 1 (Posto Centro) |
| 2 | Dr. Carlos | `ESPECIALIZADA` | 5 (Clínica do Coração) |
| 3 | Enf. Paula | `HOSPITAL` | 8 (Hospital Municipal) |
| 4 | Sr. Roberto | `GESTOR` | 10 (Secretaria de Saúde) |

- `perfil` é **o papel** da pessoa (o que ela faz).
- `unidade_id` é **o lugar** onde ela trabalha.

Ao fazer login, o PHP guarda o perfil na sessão e confere em cada página e em cada ação:

```php
if ($_SESSION['perfil'] !== 'UBS') {
    die('Acesso negado');
}
```

Regras de cada perfil:

- `UBS`: cria encaminhamento, mas **não** marca "atendido".
- `ESPECIALIZADA`: agenda, atende e preenche a contrarreferência, mas **não** cria encaminhamento.
- `HOSPITAL`: só registra altas.
- `GESTOR`: só vê o painel.

Use **ENUM** no MySQL, que aceita só valores da lista e evita erros de digitação:

```sql
perfil ENUM('UBS','ESPECIALIZADA','HOSPITAL','GESTOR') NOT NULL
```

A paciente **não aparece** na tabela `usuarios`, porque não faz login.

---

## 5. A interface da paciente

A Maria ganha uma interface simples: uma **página web que abre no navegador do celular**. Ela não precisa baixar app, criar conta nem lembrar senha.

### Como ela chega à página

1. **QR code:** a UBS entrega o QR impresso no comprovante do pedido, e ela aponta a câmera do celular.
2. **Link por WhatsApp:** o profissional clica no botão de aviso, e a mensagem já vai com o link.
3. **Digitando o código:** ela abre o endereço do site (por exemplo `/acompanhar`) e digita o código do pedido.

### O que ela vê

- Primeiro nome e inicial do sobrenome ("Maria S.").
- A especialidade ("Cardiologia").
- A **linha do tempo**: etapas já feitas marcadas, a atual destacada e as próximas apagadas.
- Uma frase em linguagem simples sobre a etapa atual: "Seu pedido está sendo analisado. Aguarde o agendamento."
- Quando agendado: data, hora, local e o que levar.

### O que ela NÃO vê

Diagnóstico, motivo clínico e observações dos profissionais. Qualquer pessoa com o código poderia abrir a página, então só dados mínimos aparecem. O código tem parte aleatória (`RP-7K3M9Q`), para ninguém acertar o de outra pessoa por tentativa.

### Mais de um encaminhamento

No protótipo, **cada encaminhamento tem o seu código**, e ela abre um por vez. Como bônus, uma página "Meus encaminhamentos" pode pedir CNS e data de nascimento e listar todos os pedidos (exige mais cuidado com segurança).

### Como fazer

Uma página PHP (`acompanhar.php?codigo=...`) que recebe o código pela URL, consulta a tabela `eventos` e desenha a linha do tempo em HTML/CSS. O QR é gerado na tela da UBS com a biblioteca `qrcode.js`.

Para escanear na demo, o projeto precisa estar acessível pelo celular: mesma rede Wi-Fi usando o IP do computador, ou um túnel como o ngrok.

---

## 6. Ciclo de vida do encaminhamento

É o centro do sistema. Cada encaminhamento tem um `status` e só avança na ordem correta:

1. **SOLICITADO**: a UBS criou o pedido.
2. **EM_REGULACAO**: a especializada está analisando.
3. **AGENDADO**: data e hora definidas.
4. **ATENDIDO**: o paciente foi atendido pelo especialista.
5. **CONTRARREFERENCIA_ENVIADA**: o especialista devolveu o parecer para a UBS.
6. **CONCLUIDO**: a UBS leu e assumiu o seguimento.

Desvio: **DEVOLVIDO**. A especializada devolve por informação faltante, com motivo escrito, e a UBS corrige e reenvia.

**Como implementar:** um array em PHP com as transições permitidas, por exemplo `'SOLICITADO' => ['EM_REGULACAO']`. Antes de atualizar o status, o PHP confere se a transição é válida **e** se o perfil logado tem permissão para aquela etapa. Isso mostra regra de negócio, não só CRUD.

**Toda mudança grava uma linha na tabela `eventos`** (etapa, quem fez, quando, observação). A linha do tempo e as métricas do painel saem dela.

---

## 7. Funcionalidades em detalhe

### 7.1 Criação do encaminhamento (UBS)

Formulário com: paciente (busca por nome ou CNS), especialidade de destino, motivo clínico, sinais de alarme (checkboxes) e checklist da especialidade. Ao salvar, o sistema gera o **código de rastreio**, registra o primeiro evento e abre uma tela de confirmação com o QR e o botão de WhatsApp.

**Protótipo:** pacientes pré-cadastrados no seed. Cadastro de paciente novo é opcional.

### 7.2 Checklist obrigatório por especialidade

**Problema que resolve:** encaminhamentos incompletos são devolvidos e o paciente perde semanas.

Cada especialidade tem uma lista de exigências no banco. Exemplo para Cardiologia:

- ECG recente
- Pressão arterial aferida
- Lista de medicamentos em uso
- Exames de colesterol e glicemia

Ao escolher a especialidade, o formulário carrega o checklist via JS (`fetch` em um endpoint PHP que devolve JSON). O botão "Enviar" fica desabilitado até todos os itens obrigatórios estarem marcados. **A validação é repetida no PHP**, porque validar só no navegador é contornável.

**Protótipo:** cada item é um checkbox "documento anexado" (ou um upload que só guarda o nome do arquivo). O foco é demonstrar a regra, não o armazenamento.

> As exigências acima são exemplos. No pitch, dizer que a lista seria definida por protocolos clínicos reais e editável pela gestão.

### 7.3 Semáforo de prioridade

| Fator | Pontos |
|---|---|
| Idade ≥ 60 | +1 |
| Comorbidade (hipertensão, diabetes, etc.) | +1 cada |
| Sinal de alarme (dor no peito, perda de força súbita, etc.) | +3 cada |

Resultado: **0 a 2 = 🟢 verde**, **3 a 4 = 🟡 amarelo**, **5 ou mais = 🔴 vermelho**.

A fila da especializada é ordenada primeiro por cor e depois por data de criação.

> **No pitch:** é uma regra simples de **apoio à decisão, não um protocolo clínico validado**. A versão real seguiria protocolos oficiais de regulação, e a decisão final é sempre humana.

### 7.4 Alerta de gargalo (SLA)

Cada especialidade tem um prazo máximo por etapa (ex.: regulação em até 5 dias). O PHP compara `NOW()` com a data do último evento. Se `dias parados > sla_dias`, a linha aparece em vermelho nos painéis da especializada e do gestor, com a contagem de dias.

**Protótipo:** sem verificação em segundo plano. O cálculo acontece ao carregar a página. Nos dados fictícios, deixar de propósito alguns encaminhamentos com datas antigas para os alertas aparecerem na demo (declarando que são dados de teste).

### 7.5 Contrarreferência obrigatória

**Problema:** o especialista atende e nada volta para a UBS.

Para fechar o atendimento, a especializada preenche um formulário curto:

- diagnóstico/impressão;
- conduta adotada;
- orientações para a UBS acompanhar;
- precisa de retorno ao especialista? (sim/não e em quanto tempo).

Ao salvar, o encaminhamento vai para `CONTRARREFERENCIA_ENVIADA` e aparece em destaque na tela da UBS ("1 retorno aguardando leitura"). Quando a UBS abre e confirma, vira `CONCLUIDO`.

### 7.6 Alta hospitalar com retorno automático

O perfil Hospital registra uma alta (resumo curto e medicamentos prescritos). O PHP cria automaticamente uma tarefa de **retorno na UBS de origem em até 7 dias**, e a UBS vê a lista "Pacientes com alta recente a acompanhar".

**Protótipo:** o paciente da demo tem uma UBS de referência cadastrada. O hospital escolhe o paciente em uma lista, sem modelar internação completa. Uma tabela simples `altas` basta.

### 7.7 Aviso por WhatsApp

Botão que abre `https://wa.me/55NUMERO?text=...` com a mensagem pronta, por exemplo: "Olá, Maria. Sua consulta de Cardiologia foi agendada para 12/11 às 14h. Acompanhe: [link]". O profissional só clica em enviar.

> Não é envio automático. No pitch, dizer que a versão real usaria a API oficial do WhatsApp Business ou SMS.

### 7.8 Painel do gestor

Indicadores calculados com SQL sobre `eventos` e `encaminhamentos`, exibidos com **Chart.js**:

- total de encaminhamentos por status (barras);
- tempo médio em cada etapa (barras);
- encaminhamentos atrasados por especialidade (ranking de gargalos);
- distribuição por prioridade verde/amarelo/vermelho (rosca);
- lista dos casos críticos parados.

Extras: filtro por especialidade e período; mapa das UBS com Leaflet, se sobrar tempo.

---

## 8. Exemplo completo, do começo ao fim

1. O médico do postinho cadastra o pedido da Maria. O sistema trava até ele marcar o eletrocardiograma. Depois, gera o código e o QR.
2. Maria escaneia o QR e vê "Pedido enviado".
3. O especialista abre o sistema, vê o pedido de Maria (cor amarela) e marca a consulta. Maria vê a nova etapa no celular.
4. Depois da consulta, o especialista escreve o resultado. O postinho recebe o aviso: "voltou o resultado de Maria".
5. O gestor abre o painel e vê: "Cardiologia tem 12 pedidos parados há mais de 15 dias". Agora ele sabe onde agir.

---

## 9. O que é real e o que é simulado

| Real (funciona de verdade) | Simulado/simplificado |
|---|---|
| Login por perfil e permissões | Usuários fixos, sem cadastro |
| Criação e mudança de status com regras | Dados fictícios pré-carregados |
| Checklist, semáforo, SLA, contrarreferência, alta | Anexos como checkbox |
| Linha do tempo e QR | WhatsApp como link de atalho |
| Painel com consultas reais ao banco | Sem integração com e-SUS/SISREG/RNDS |

Falar isso no pitch passa maturidade, não fraqueza.

---

## 10. Prioridade de entrega

**Obrigatório (sem isso não há demo):**
- login por perfil;
- criar encaminhamento com checklist;
- mudar status com regras;
- linha do tempo pública com QR;
- contrarreferência.

**Importante:**
- semáforo;
- SLA no painel;
- painel com 3 gráficos.

**Bônus (só se sobrar tempo):**
- alta hospitalar com retorno;
- botão de WhatsApp;
- devolução com motivo;
- página "Meus encaminhamentos";
- mapa.

Se o relógio apertar, cortar de baixo para cima.

---

## 11. Banco de dados

- `pacientes` (id, nome, cns, nascimento, telefone, ubs_referencia_id)
- `unidades` (id, nome, tipo: UBS/ESPECIALIZADA/HOSPITAL)
- `usuarios` (id, nome, **perfil**, unidade_id, senha_hash)
- `especialidades` (id, nome, sla_dias)
- `checklist_itens` (id, especialidade_id, descricao, obrigatorio)
- `encaminhamentos` (id, codigo, paciente_id, origem_id, destino_id, especialidade_id, prioridade, status, criado_em)
- `eventos` (id, encaminhamento_id, etapa, usuario_id, observacao, criado_em)
- `contrarreferencias` (id, encaminhamento_id, diagnostico, conduta, seguimento_ubs, precisa_retorno)
- `altas` (id, paciente_id, data_alta, resumo, ubs_retorno_id, prazo_retorno)

A tabela `eventos` é o coração do projeto: a linha do tempo e o cálculo de tempo por etapa saem dela.

---

## 12. Divisão do time

| Pessoa | Responsabilidade |
|---|---|
| 1 | Banco de dados, scripts SQL, dados fictícios realistas (20 a 30 casos em estágios variados) |
| 2 | Back-end PHP: login/perfis, cadastro e mudança de status, regras de checklist e semáforo (endpoints JSON) |
| 3 | Tela da UBS: novo encaminhamento, checklist, lista de pacientes |
| 4 | Tela pública da paciente (timeline + QR) e telas da especializada/hospital |
| 5 | Painel do gestor (Chart.js) e depois pitch, slides e roteiro da demo |

Todos usam o mesmo CSS base (cores e componentes definidos na primeira hora) para o sistema parecer um produto só.

---

## 13. Cronograma de 1 dia

| Período | Atividade |
|---|---|
| **Hora 0-1** | Fechar escopo, desenhar o banco, definir o padrão visual, criar o repositório no Git |
| **Hora 1-3** | Banco pronto com dados fictícios, login, fluxo básico criar encaminhamento → mudar status |
| **Hora 3-6** | Telas de cada perfil, timeline da paciente, checklist |
| **Hora 6-8** | Semáforo, alertas de SLA, contrarreferência, alta com retorno |
| **Hora 8-9** | Painel do gestor, QR, botão de WhatsApp |
| **Últimas 2 horas** | Nada de funcionalidade nova. Testar o fluxo completo, corrigir bugs e ensaiar o pitch |

---

## 14. Roteiro da demo (3 a 5 minutos)

1. **Problema:** "Maria, 62 anos, hipertensa, foi encaminhada ao cardiologista há 3 meses e ninguém sabe onde está o pedido."
2. A **UBS** cria o encaminhamento, o checklist bloqueia o envio até anexar o ECG, e o semáforo marca amarelo.
3. Maria recebe o link/QR e acompanha **pelo celular** (mostrar o celular de verdade).
4. A **especializada** atende e preenche a contrarreferência.
5. O **hospital** registra uma alta e o retorno à UBS é criado sozinho.
6. O **gestor** abre o painel e vê o gargalo: "Cardiologia está com 12 casos parados há mais de 15 dias."

**Fechamento:** "Reduz devoluções, dá transparência ao paciente e dá ao gestor dados para agir."

---

## 15. Cuidados técnicos e segurança

- **PDO com prepared statements**, sem montar SQL por concatenação.
- **`password_hash` / `password_verify`** para senhas.
- **Sessões PHP** para controlar o acesso por perfil em cada página (não apenas esconder botões).
- **`htmlspecialchars`** em todas as saídas, contra XSS.
- Validação repetida no servidor, nunca só no navegador.
- Página pública sem dados clínicos.

---

## 16. Pontos para o pitch

- **LGPD:** a tela pública mostra só nome abreviado e status; há perfis de acesso e consultas protegidas.
- **Escalabilidade:** o modelo de dados pode ser mapeado para **FHIR/RNDS** no futuro, sem precisar implementar agora.
- **Honestidade:** os dados são fictícios; a integração real com e-SUS/SISREG seria a próxima etapa.
- **Responsabilidade clínica:** o semáforo é apoio à decisão, não protocolo validado.

---

## 17. Ideias complementares

Para citar no pitch como evolução do projeto.

**UBS**
- Previsão de faltas em consultas com lembretes e lista de espera dinâmica.
- Busca ativa de pacientes crônicos (hipertensos e diabéticos) priorizada por risco.
- Pré-triagem de demanda espontânea.
- Previsão de demanda de medicamentos e vacinas por território.

**Especializadas**
- Telediagnóstico e teleinterconsulta (ECG, foto de lesão de pele, retinografia).
- Linha de cuidado por especialidade (ex.: insuficiência cardíaca, pós-AVC).

**Hospitais**
- Previsão de demanda e gestão de leitos em tempo real.
- Alerta precoce de sepse ou deterioração clínica.
- Reconciliação de medicamentos na admissão e na alta.

**Interligação**
- Resumo clínico unificado em padrão FHIR/RNDS.
- Painel municipal integrado com simulação de cenários.
