


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

/* ---- Liga tudo quando a página termina de carregar ---- */
document.addEventListener("DOMContentLoaded", () => {
  iniciarIndex();
  iniciarAcompanhar();
});