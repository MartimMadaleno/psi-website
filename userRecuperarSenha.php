<?php
$template_link = $motivo = "";
session_start();


include_once  './conexaobasedados.php';
include_once './function_mail_utf8.php';

$mensagemErroCodigo = "";
$mensagemErroSenha = "";
$geraFormulario = "Sim";
$codigo = "";
$mensagemEmail = "";

if (isset($_POST['botao-recuperar-senha'])) {

  $codigo = mysqli_real_escape_string($_conn, $_POST['formCodigo']);
  $codigo = strtolower(trim($codigo));


  $stmt = $_conn->prepare('SELECT * FROM USERS WHERE CODIGO = ?');
  $stmt->bind_param('s', $codigo);
  $stmt->execute();

  $resultadoUsers = $stmt->get_result();

  if ($resultadoUsers->num_rows > 0) {
    while ($rowUsers = $resultadoUsers->fetch_assoc()) {

      $nome = $rowUsers['NOME'];
      $email =  $rowUsers["EMAIL"];

      if ($rowUsers['USER_STATUS'] == 2) { // utilizador bloqueado

        $mensagemErroSenha = "Não foi enviada mensagem de recuperação de senha, contacte os nossos serviços para obter ajuda.";
      } else  if ($rowUsers['USER_STATUS'] == 0) { // Utilizador criou a conta mas não ativou

        $mensagemErroSenha =  $rowUsers['NOME'] . ", ainda não ativou a sua conta. A mensagem com o código inicial de ativação de conta foi enviada para a sua caixa de correio. Caso não a encontre na sua caixa de entrada, verifique também o seu correio não solicitado ou envie-nos um email para ativarmos a sua conta. Obrigado.";
      } else {

        // Recuperar a senha 
        // gerar token, preparar e enviar mail de recuperação

        $code = md5(uniqid(rand()));

        $sql = "UPDATE  USERS SET TOKEN_CODE=? WHERE CODIGO=?";

        if ($stmt = mysqli_prepare($_conn, $sql)) {

          mysqli_stmt_bind_param($stmt, "ss", $code, $codigo);
          mysqli_stmt_execute($stmt);

          // Update efetuado com sucesso, preparar e enviar mensagem 
          $id = base64_encode($codigo);


          $urlPagina = "https://psi-website.herokuapp.com/";

          $subject = "Recuperação da sua senha";

          $template_link = "";

          $template_link = $urlPagina . "userNovaSenha.php?id=$id&code=$code";
          include_once './emailTemplates.php';
          $mensagem = $NewPasswordEmailTemplate;

          // send email
          mail_utf8($email, $subject, $mensagem, $nome);

          $mensagemEmail = " " . "$nome, verifique por favor a sua caixa de correio para recuperar de imediato a sua senha!";

          // fim do envio de mensagem //////////////////////////////////////////////////////////////////            

          $geraFormulario = "Nao";
        } else {
          // erro
          echo "STATUS ADMIN (recuperar senha): " . mysqli_error($_conn);
        }
      }
    }
  } else {
    $mensagemErroCodigo = "O código de utilizador não existe na nossa base de dados!";
  }

  $stmt->free_result();
  $stmt->close();
}

?>
<!DOCTYPE html>
<html>
<title>StockTravel - Recuperar senha</title>
<?php include_once  './includes/estilos.php'; ?>

<body>
  <?php include_once  './includes/menus.php'; ?>
  <?php include_once './includes/sidenav.php'; ?>

  <div class="container mt-4">
    <div class="row justify-content-center">
      <div class="col-6">
        <div class="row justify-content-center">
          <div class="col-8">
            <h2 class="h2 text-center">RECUPERAR SENHA</h2>
          </div>
          <p class="fs-5">Recuperar a sua senha de utilizador.</p>

          <?php

          if ($geraFormulario == "Sim") {

          ?>

            <form action="#" method="POST">

              <p class="fs-6">Introduza o código que utiliza para inciar sessão e carregue no botão para recuperar senha. Será enviada para o seu e-mail uma mensagem com um código de recuperação de senha.
                Por vezes estas mensagens são consideradas correio não solicitado. Se não vir a mensagem de recuperação verifique o seu correio não solicitado (SPAM).
              </p>

              <p><input class="form-control" type="text" style="width:300px" placeholder="Código de utilizador" name="formCodigo" value="<?php echo $codigo; ?>"></p>
              <p class="w3-large w3-text-red"><?php echo $mensagemErroCodigo; ?></p>

              <p class="w3-large w3-text-red"><?php echo $mensagemErroSenha; ?></p>
              <p class="w3-large w3-text-red"><?php echo $mensagemEmail; ?></p>

              <p><button class="btn btn-dark" name="botao-recuperar-senha" type="submit">Recuperar senha agora</button></p>

            </form>

          <?php
          } else {
          ?>
            <p class="fs-5">Recuperação de senha: <i>link</i> de recuperação enviado. Verifique a sua caixa de correio.</p>

            <form action="./index.php" method="POST">

              <p><button class="btn btn-dark" type="submit">PÁGINA PRINCIPAL</button></p>

            </form>

          <?php
          }
          ?>

        </div>
      </div>
    </div>
  </div>
  <?php include_once  './includes/scripts.php'; ?>
</body>

</html>