/* ---- Encaminhamentos fictícios ----
   etapa: 1 = Pedido enviado ... 5 = Retorno enviado à UBS, 6 = tudo concluído */
const encaminhamentos = {
  "RP-5C6D7E": { nome: "João P.",  etapa: 1 },
  "RP-7K3M9Q": { nome: "Maria S.", etapa: 3 },
  "RP-4F2L8Z": { nome: "Ana C.",   etapa: 4 },
  "RP-9D5T1W": { nome: "Carlos M.", etapa: 6 }
};

/* Mensagem de destaque para cada etapa */
const mensagens = {
  1: ["Seu pedido foi enviado.", "Aguarde a análise da especializada."],
  3: ["Sua consulta foi agendada.", "Confira a data e prepare os documentos indicados abaixo."],
  4: ["Seu atendimento foi realizado.", "O resultado está sendo enviado à sua UBS."],
  6: ["Tudo concluído.", "Sua UBS recebeu o retorno e vai acompanhar você."]
};

/* Texto pequeno de cada etapa quando ela já foi concluída */
const texto_feito = [
  "UBS Jardim das Flores • 18 out, 09:14",
  "AME Centro • 19 out, 14:32",
  "Consulta marcada",
  "AME Centro • atendimento concluído",
  "Retorno recebido pela UBS"
];

/* Deixa o código no padrão: sem espaços e em maiúsculas */
function limparCodigo(texto) {
  return (texto || "").trim().toUpperCase();
}

/* ============================================================
   PÁGINA 1: index.html (formulário do paciente)
   ============================================================ */
function iniciarIndex() {
  const form = document.querySelector('form[action="acompanhar.html"]');
  if (!form) return;

  const campo = form.querySelector('input[name="codigo"]');

  // Mensagem de erro (criada pelo JS, logo abaixo do campo)
  const erro = document.createElement("p");
  erro.style.cssText = "color:#b85c50;font-size:13px;margin-top:8px;display:none;";
  campo.insertAdjacentElement("afterend", erro);

  // Some com o erro assim que a pessoa volta a digitar
  campo.addEventListener("input", () => { erro.style.display = "none"; });

  form.addEventListener("submit", (evento) => {
    const codigo = limparCodigo(campo.value);
    campo.value = codigo; // já envia em maiúsculas

    if (!/^RP-[A-Z0-9]{6}$/.test(codigo)) {
      evento.preventDefault();
      erro.textContent = "Formato inválido. Use o padrão RP-XXXXXX (ex.: RP-7K3M9Q).";
      erro.style.display = "block";
    } else if (!encaminhamentos[codigo]) {
      evento.preventDefault();
      erro.textContent = "Código não encontrado. Confira o comprovante da UBS.";
      erro.style.display = "block";
    }
    // Se estiver tudo certo, o formulário segue para acompanhar.html?codigo=...
  });
}

/* ============================================================
   PÁGINA 2: acompanhar.html (linha do tempo do paciente)
   ============================================================ */
function iniciarAcompanhar() {
  const linha = document.querySelector(".timeline");
  if (!linha) return;

  // Lê o código que veio na URL: acompanhar.html?codigo=RP-7K3M9Q
  const codigo = limparCodigo(new URLSearchParams(window.location.search).get("codigo"));

  // Sem código na URL: volta para o início
  if (!codigo) {
    window.location.href = "index.html";
    return;
  }

  const dados = encaminhamentos[codigo];
  const cartao = document.querySelector(".cartao");

  // Código que não existe: mostra aviso no lugar da linha do tempo
  if (!dados) {
    document.querySelector(".codigo").textContent = "CÓDIGO " + codigo;
    document.querySelector("h1").textContent = "Código não encontrado";
    cartao.innerHTML = '<p style="text-align:center">Confira o código no comprovante da UBS e tente novamente.</p>';
    return;
  }

  // Cabeçalho: código e nome do paciente
  document.querySelector(".codigo").textContent = "CÓDIGO " + codigo;
  document.querySelector("h1").textContent = "Olá, " + dados.nome;

  // Mensagem de destaque (caixa verde)
  const [titulo, descricao] = mensagens[dados.etapa];
  document.querySelector(".mensagem").innerHTML = "<strong>✓ " + titulo + "</strong>" + descricao;

  // Bolinhas: troca a classe de cada etapa (feito, atual ou proximo)
  linha.querySelectorAll("li").forEach((li, i) => {
    const numero = i + 1;
    const bolinha = li.querySelector(".bolinha");
    const detalhe = li.querySelector("small");

    if (numero < dados.etapa) {
      li.className = "feito";
      bolinha.textContent = "✓";
      detalhe.textContent = texto_feito[i];
    } else if (numero === dados.etapa) {
      li.className = "atual";
      bolinha.textContent = numero;
      detalhe.textContent = "Etapa atual";
    } else {
      li.className = "proximo";
      bolinha.textContent = numero;
      detalhe.textContent = "Próxima etapa";
    }
  });

  // Caixa "Consulta agendada" só aparece quando a consulta está marcada (etapa 3)
  const consulta = document.querySelector(".consulta");
  if (consulta) consulta.style.display = dados.etapa === 3 ? "block" : "none";
}

/* ---- Liga tudo quando a página termina de carregar ---- */
document.addEventListener("DOMContentLoaded", () => {
  iniciarIndex();
  iniciarAcompanhar();
});