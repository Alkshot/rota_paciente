# Rota do Paciente

> Rastreio de encaminhamentos na rede de saúde, no estilo "Correios".
> Protótipo desenvolvido para o Hackathon, tema **Saúde Conectada**.

**Eixo principal:** Interligação (UBS ↔ Especializadas ↔ Hospitais)
**Stack:** HTML, CSS, JavaScript, PHP e MySQL
**Equipe:** 5 pessoas | **Prazo:** 1 dia

---

## Sumário

1. [O problema](#1-o-problema)
2. [A solução](#2-a-solução)
3. [Perfis de usuário](#3-perfis-de-usuário)
4. [Ciclo de vida do encaminhamento](#4-ciclo-de-vida-do-encaminhamento)
5. [Funcionalidades em detalhe](#5-funcionalidades-em-detalhe)
6. [O que é real e o que é simulado](#6-o-que-é-real-e-o-que-é-simulado)
7. [Prioridade de entrega](#7-prioridade-de-entrega)
8. [Banco de dados](#8-banco-de-dados)
9. [Divisão do time](#9-divisão-do-time)
10. [Cronograma de 1 dia](#10-cronograma-de-1-dia)
11. [Roteiro da demo](#11-roteiro-da-demo)
12. [Cuidados técnicos e segurança](#12-cuidados-técnicos-e-segurança)
13. [Pontos para o pitch](#13-pontos-para-o-pitch)
14. [Ideias complementares (outros eixos)](#14-ideias-complementares-outros-eixos)

**Regra do protótipo:** tudo que o jurado vê tem que funcionar de verdade no fluxo principal. O que seria caro de fazer (integrações, upload real, notificações reais) é simulado, e o grupo diz isso abertamente no pitch.

---

## 1. O problema

Quando uma UBS encaminha um paciente a um especialista, o pedido entra em um "buraco negro":

- O **paciente** não sabe se o pedido foi aceito, em que fila está ou quando será chamado.
- A **UBS** não sabe se o encaminhamento foi devolvido por falta de exame.
- O **especialista** atende, mas a UBS nunca recebe o retorno (o que foi diagnosticado e o que a UBS deve acompanhar).
- O **gestor** não enxerga onde a rede trava.

## 2. A solução

Cada encaminhamento vira um "pacote" com **código de rastreio** e um histórico de etapas, como uma encomenda. Cada ponto da rede atualiza a sua parte, e paciente, UBS e gestor veem em que pé está.

### Diferenciais

1. **Link/QR público para o paciente.** Sem login, ele vê uma linha do tempo pelo celular.
2. **Checklist obrigatório por especialidade.** A UBS não envia o encaminhamento sem os itens exigidos.
3. **Semáforo de prioridade por regras.** Verde, amarelo e vermelho, sem IA.
4. **Alerta de gargalo (SLA).** Encaminhamentos parados acendem em vermelho.
5. **Contrarreferência obrigatória.** O especialista só fecha o caso devolvendo o parecer à UBS.
6. **Alta hospitalar com retorno automático.** O hospital registra a alta e a UBS recebe a tarefa de retorno.
7. **Aviso por WhatsApp sem API.** Botão com mensagem pronta (`wa.me`).
8. **Painel do gestor.** Gráficos de gargalos e tempos por etapa.

---

## 3. Perfis de usuário

| Perfil | O que faz no sistema |
|---|---|
| **Profissional da UBS** | Cria o encaminhamento, preenche o checklist, recebe a contrarreferência e acompanha retornos |
| **Regulador/Especializada** | Recebe a fila, confirma o agendamento, atende e preenche a contrarreferência |
| **Hospital** | Registra a alta do paciente |
| **Gestor** | Só visualiza o painel com indicadores |
| **Paciente** | Sem login. Acessa pelo código ou QR e só vê o status |

**No protótipo:** 4 usuários fixos de teste (um por perfil, com senha), criados no script SQL. Sem cadastro de usuários nem recuperação de senha. Para a demo, a tela de login pode ter botões "entrar como UBS / Especializada / Hospital / Gestor".

---

## 4. Ciclo de vida do encaminhamento

É o centro do sistema. Cada encaminhamento tem um `status` e só avança na ordem correta:

1. **SOLICITADO**: a UBS criou o pedido.
2. **EM_REGULACAO**: a especializada está analisando.
3. **AGENDADO**: data e hora definidas.
4. **ATENDIDO**: o paciente foi atendido pelo especialista.
5. **CONTRARREFERENCIA_ENVIADA**: o especialista devolveu o parecer para a UBS.
6. **CONCLUIDO**: a UBS leu e assumiu o seguimento.

Desvio: **DEVOLVIDO**. A especializada devolve por informação faltante, com motivo escrito, e a UBS corrige e reenvia.

**Como implementar:** um array em PHP com as transições permitidas, por exemplo `'SOLICITADO' => ['EM_REGULACAO']`. Antes de atualizar o status, o PHP confere se a transição é válida **e** se o perfil logado tem permissão para aquela etapa (a UBS não pode marcar "atendido"). Isso mostra regra de negócio, não só CRUD.

**Toda mudança grava uma linha na tabela `eventos`** (etapa, quem fez, quando, observação). A linha do tempo e as métricas do painel saem dela.

---

## 5. Funcionalidades em detalhe

### 5.1 Criação do encaminhamento (UBS)

Formulário com: paciente (busca por nome ou CNS), especialidade de destino, motivo clínico, sinais de alarme (checkboxes) e checklist da especialidade. Ao salvar, o sistema gera o **código de rastreio** (ex.: `RP-7K3M9Q`), registra o primeiro evento e abre uma tela de confirmação com o QR e o botão de WhatsApp.

**Protótipo:** pacientes pré-cadastrados no seed. Cadastro de paciente novo é opcional.

### 5.2 Checklist obrigatório por especialidade

**Problema que resolve:** encaminhamentos incompletos são devolvidos e o paciente perde semanas.

**Como funciona:** cada especialidade tem uma lista de exigências no banco. Exemplo para Cardiologia:

- ECG recente
- Pressão arterial aferida
- Lista de medicamentos em uso
- Exames de colesterol e glicemia

Ao escolher a especialidade, o formulário carrega o checklist via JS (`fetch` em um endpoint PHP que devolve JSON). O botão "Enviar" fica desabilitado até todos os itens obrigatórios estarem marcados. **A validação é repetida no PHP**, porque validar só no navegador é contornável.

**Protótipo:** em vez de upload real, cada item é um checkbox "documento anexado" (ou um campo de upload que só guarda o nome do arquivo). O foco é demonstrar a regra, não o armazenamento.

> As exigências acima são exemplos. No pitch, dizer que a lista seria definida por protocolos clínicos reais e editável pela gestão.

### 5.3 Semáforo de prioridade

**Objetivo:** mostrar que a fila não deve ser só ordem de chegada, e sim risco.

| Fator | Pontos |
|---|---|
| Idade ≥ 60 | +1 |
| Comorbidade (hipertensão, diabetes, etc.) | +1 cada |
| Sinal de alarme (dor no peito, perda de força súbita, etc.) | +3 cada |

Resultado: **0 a 2 = 🟢 verde**, **3 a 4 = 🟡 amarelo**, **5 ou mais = 🔴 vermelho**.

A fila da especializada é ordenada primeiro por cor e depois por data de criação.

> **No pitch:** é uma regra simples de **apoio à decisão, não um protocolo clínico validado**. A versão real seguiria protocolos oficiais de regulação, e a decisão final é sempre humana.

### 5.4 Linha do tempo do paciente (página pública)

Página `acompanhar.php?codigo=RP-7K3M9Q` que consulta a tabela `eventos` e mostra:

- as etapas em ordem, com a etapa atual destacada;
- a próxima etapa em linguagem simples ("Aguardando agendamento pela especializada");
- quando agendado: data, local e o que levar.

**Privacidade:**

- Mostra só o primeiro nome e a inicial do sobrenome.
- Não mostra diagnóstico, motivo clínico nem observações.
- O código tem parte aleatória, para ninguém acertar o de outra pessoa por tentativa.

**QR code:** a biblioteca `qrcode.js` gera o QR no navegador apontando para essa URL. Para escanear na demo, o projeto precisa estar acessível pelo celular (mesma rede Wi-Fi usando o IP do computador, ou um túnel como o ngrok).

### 5.5 Alerta de gargalo (SLA)

Cada especialidade tem um prazo máximo por etapa (ex.: regulação em até 5 dias). Se o encaminhamento passa disso, está "atrasado".

**Como funciona:** o PHP compara `NOW()` com a data do último evento. Se `dias parados > sla_dias`, a linha aparece em vermelho nos painéis da especializada e do gestor, com a contagem de dias.

**Protótipo:** sem verificação em segundo plano. O cálculo acontece ao carregar a página, o que dispensa agendador de tarefas. Nos dados fictícios, deixar de propósito alguns encaminhamentos com datas antigas para os alertas aparecerem na demo (declarando que são dados de teste).

### 5.6 Contrarreferência obrigatória

**Problema:** o especialista atende e nada volta para a UBS.

**Como funciona:** para fechar o atendimento, a especializada preenche um formulário curto:

- diagnóstico/impressão;
- conduta adotada;
- orientações para a UBS acompanhar;
- precisa de retorno ao especialista? (sim/não e em quanto tempo).

Ao salvar, o encaminhamento vai para `CONTRARREFERENCIA_ENVIADA` e aparece em destaque na tela da UBS ("1 retorno aguardando leitura"). Quando a UBS abre e confirma, vira `CONCLUIDO`.

### 5.7 Alta hospitalar com retorno automático

O perfil Hospital registra uma alta (resumo curto e medicamentos prescritos). Ao salvar, o PHP cria automaticamente uma tarefa de **retorno na UBS de origem em até 7 dias**, e a UBS vê a lista "Pacientes com alta recente a acompanhar".

**Protótipo:** o paciente da demo tem uma UBS de referência cadastrada. O hospital escolhe o paciente em uma lista, sem modelar internação completa. Uma tabela simples `altas` (paciente, data, resumo, ubs_retorno_id, prazo) basta.

### 5.8 Aviso por WhatsApp

Botão que abre `https://wa.me/55NUMERO?text=...` com a mensagem pronta, por exemplo: "Olá, Maria. Sua consulta de Cardiologia foi agendada para 12/11 às 14h. Acompanhe: [link]". O profissional só clica em enviar.

> Não é envio automático. No pitch, dizer que a versão real usaria a API oficial do WhatsApp Business ou SMS.

### 5.9 Painel do gestor

Indicadores calculados com SQL sobre `eventos` e `encaminhamentos`, exibidos com **Chart.js**:

- total de encaminhamentos por status (barras);
- tempo médio em cada etapa (barras);
- encaminhamentos atrasados por especialidade (ranking de gargalos);
- distribuição por prioridade verde/amarelo/vermelho (rosca);
- lista dos casos críticos parados.

Extras: filtro por especialidade e período; mapa das UBS com Leaflet, se sobrar tempo.

---

## 6. O que é real e o que é simulado

| Real (funciona de verdade) | Simulado/simplificado |
|---|---|
| Login por perfil e permissões | Usuários fixos, sem cadastro |
| Criação e mudança de status com regras | Dados fictícios pré-carregados |
| Checklist, semáforo, SLA, contrarreferência, alta | Anexos como checkbox |
| Linha do tempo e QR | WhatsApp como link de atalho |
| Painel com consultas reais ao banco | Sem integração com e-SUS/SISREG/RNDS |

Falar isso no pitch passa maturidade, não fraqueza.

---

## 7. Prioridade de entrega

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
- mapa.

Se o relógio apertar, cortar de baixo para cima.

---

## 8. Banco de dados

Esboço das tabelas:

- `pacientes` (id, nome, cns, nascimento, telefone, ubs_referencia_id)
- `unidades` (id, nome, tipo: UBS/ESPECIALIZADA/HOSPITAL)
- `usuarios` (id, nome, perfil, unidade_id, senha_hash)
- `especialidades` (id, nome, sla_dias)
- `checklist_itens` (id, especialidade_id, descricao, obrigatorio)
- `encaminhamentos` (id, codigo, paciente_id, origem_id, destino_id, especialidade_id, prioridade, status, criado_em)
- `eventos` (id, encaminhamento_id, etapa, usuario_id, observacao, criado_em)
- `contrarreferencias` (id, encaminhamento_id, diagnostico, conduta, seguimento_ubs, precisa_retorno)
- `altas` (id, paciente_id, data_alta, resumo, ubs_retorno_id, prazo_retorno)

A tabela `eventos` é o coração do projeto: a linha do tempo e o cálculo de tempo por etapa saem dela.

---

## 9. Divisão do time

| Pessoa | Responsabilidade |
|---|---|
| 1 | Banco de dados, scripts SQL, dados fictícios realistas (20 a 30 casos em estágios variados) |
| 2 | Back-end PHP: login/perfis, cadastro e mudança de status, regras de checklist e semáforo (endpoints JSON) |
| 3 | Tela da UBS: novo encaminhamento, checklist, lista de pacientes |
| 4 | Tela pública do paciente (timeline + QR) e telas da especializada/hospital |
| 5 | Painel do gestor (Chart.js) e depois pitch, slides e roteiro da demo |

Todos usam o mesmo CSS base (cores e componentes definidos na primeira hora) para o sistema parecer um produto só.

---

## 10. Cronograma de 1 dia

| Período | Atividade |
|---|---|
| **Hora 0-1** | Fechar escopo, desenhar o banco, definir o padrão visual, criar o repositório no Git |
| **Hora 1-3** | Banco pronto com dados fictícios, login, fluxo básico criar encaminhamento → mudar status |
| **Hora 3-6** | Telas de cada perfil, timeline do paciente, checklist |
| **Hora 6-8** | Semáforo, alertas de SLA, contrarreferência, alta com retorno |
| **Hora 8-9** | Painel do gestor, QR, botão de WhatsApp |
| **Últimas 2 horas** | Nada de funcionalidade nova. Testar o fluxo completo, corrigir bugs e ensaiar o pitch |

---

## 11. Roteiro da demo (3 a 5 minutos)

1. **Problema:** "Maria, 62 anos, hipertensa, foi encaminhada ao cardiologista há 3 meses e ninguém sabe onde está o pedido."
2. A **UBS** cria o encaminhamento, o checklist bloqueia o envio até anexar o ECG, e o semáforo marca amarelo.
3. Maria recebe o link/QR e acompanha **pelo celular** (mostrar o celular de verdade).
4. A **especializada** atende e preenche a contrarreferência.
5. O **hospital** registra uma alta e o retorno à UBS é criado sozinho.
6. O **gestor** abre o painel e vê o gargalo: "Cardiologia está com 12 casos parados há mais de 15 dias."

**Fechamento:** "Reduz devoluções, dá transparência ao paciente e dá ao gestor dados para agir."

---

## 12. Cuidados técnicos e segurança

- **PDO com prepared statements**, sem montar SQL por concatenação.
- **`password_hash` / `password_verify`** para senhas.
- **Sessões PHP** para controlar o acesso por perfil em cada página (não apenas esconder botões).
- **`htmlspecialchars`** em todas as saídas, contra XSS.
- Validação repetida no servidor, nunca só no navegador.
- Página pública sem dados clínicos.

---

## 13. Pontos para o pitch

- **LGPD:** a tela pública mostra só nome abreviado e status; há perfis de acesso e consultas protegidas.
- **Escalabilidade:** o modelo de dados pode ser mapeado para **FHIR/RNDS** no futuro, sem precisar implementar agora.
- **Honestidade:** os dados são fictícios; a integração real com e-SUS/SISREG seria a próxima etapa.
- **Responsabilidade clínica:** o semáforo é apoio à decisão, não protocolo validado.

---

## 14. Ideias complementares (outros eixos)

Caso o grupo queira citar no pitch como evolução do projeto:

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
