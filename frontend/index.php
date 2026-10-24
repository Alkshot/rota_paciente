<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Rota do Paciente</title>
  <link rel="stylesheet" href="style.css">
  <script src="script.js" defer></script>
  <?php
  require_once '../backend/includes/login_view.inc.php';
  require_once '../backend/includes/acompanhar_view.inc.php';
  ?>
</head>
<body>
  <div class="tela-login">

    <section class="lado-azul">
      <div class="marca">
        <div class="icone">⤴</div>
        <div><strong>Rota do Paciente</strong><small>Rede integrada de saúde</small></div>
      </div>
      <div>
        <span class="tag">Saúde conectada</span>
        <h1>O cuidado não para no encaminhamento.</h1>
        <p>Acompanhe cada etapa do paciente entre UBS, especializadas e hospitais, com transparência para todos.</p>
      </div>
      <div class="aviso">🛡 Ambiente demonstrativo com dados fictícios e privacidade por padrão.</div>
    </section>

    <section class="lado-form">
      <div class="caixa">

        <!-- ACESSO DO PACIENTE (principal) -->
        <span class="tag">Para o paciente</span>
        <h2>Acompanhe seu encaminhamento</h2>
        <p class="sub">Digite o código que você recebeu na UBS.</p>

        <!-- Na versão com PHP: action="acompanhar.php" method="get" -->
        <form action="../backend/acompanhar.php" method="get">
          <label for="codigo">Código do encaminhamento</label>
          <input type="text" id="codigo" name="codigo" placeholder="Ex.: RP-7K3M9Q" required>

          <div id="erro">
          <?php
          check_acomp_errors();
          ?>
          </div>
          <button class="btn" type="submit">Acompanhar</button>
        </form>

        <!-- ACESSO DOS PROFISSIONAIS -->
        <div class="divisor"><span>Sou profissional da rede</span></div>

        <p class="sub">UBS, Especializada ou Hospital</p>

        <!-- Na versão com PHP: action="login.php" method="post" -->
        <form action="ubs.html" action="../backend/login.php" method="POST">
        <?php
        login_input();
        ?> 
        
        
       <!-- <label for="email">Email</label>
          <input type="text" id="email" name="email" placeholder="Seu email" required> -->

          <label for="senha">Senha</label>
          <input type="password" id="senha" name="senha" placeholder="Sua senha" required>

          <button class="btn btn-claro" type="submit">Entrar</button>
          <div id="erro">
            <?php
            check_login_errors();
            ?>
          </div>
        </form>

        <div class="links">
          <span>Profissional sem conta? <a href="cadastro.php">Cadastre-se</a></span>
        </div>

      </div>
    </section>

  </div>
</body>
</html>
