function limparCodigo(texto) {
        return (texto || "").trim().toUpperCase();
    }

const codigo = limparCodigo(new URLSearchParams(window.location.search).get("codigo"));

async function get_encaminhamento() {

    try {
        const res = await fetch('../backend/get_encaminhamento.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ cod: codigo })
        });

        if (!res.ok) {
            throw new Error(`Erro HTTP: ${res.status}`);
        }

        const en = await res.json();
        console.log('Resposta do PHP:', en);

        return en;
    } catch (error) {
        console.error('Erro:', error);
    }
}


get_encaminhamento().then()

/* ---- Encaminhamentos fictícios ----
   etapa: 1 = Pedido enviado ... 5 = Retorno enviado à UBS, 6 = tudo concluído */
const encaminhamentos = {
        "RP-5C6D7E": { etapa: 1 },
        "RP-7K3M9Q": { etapa: 3 },
        "RP-4F2L8Z": { etapa: 4 },
        "RP-9D5T1W": { etapa: 6 }
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
       PÁGINA 2: acompanhar.html (linha do tempo do paciente)
       ============================================================ */
    async function iniciarAcompanhar() {
        const linha = document.querySelector(".timeline");
        if (!linha) return;

        // Lê o código que veio na URL: acompanhar.html?codigo=RP-7K3M9Q
        const codigo = limparCodigo(new URLSearchParams(window.location.search).get("codigo"));



        const dados = await get_encaminhamento();
        const cartao = document.querySelector(".cartao");

        // Código que não existe: mostra aviso no lugar da linha do tempo
        // if (!dados) {
        //     document.querySelector(".codigo").textContent = "CÓDIGO " + codigo;
        //     document.querySelector("h1").textContent = "Código não encontrado";
        //     cartao.innerHTML = '<p style="text-align:center">Confira o código no comprovante da UBS e tente novamente.</p>';
        //     return;
        // }

        // Cabeçalho: código e nome do paciente
        document.querySelector(".codigo").textContent = "CÓDIGO " + codigo;

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
        iniciarAcompanhar();
    });