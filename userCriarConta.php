<?php
$template_link = $motivo = "";
session_start();

include_once  './conexaobasedados.php';
include_once './function_mail_utf8.php';


// iniciar e limpar possíveis mensagens de erro
$msgTemporaria = "";

$mensagemErroCodigo = "";
$mensagemErroEmail = "";
$mensagemErroSenha = "";
$mensagemErroSenhaRecuperacao = "";
$mensagemErroNome = "";

// inciar e limpar variáveis
$codigo = "";
$email = "";
$senha = "";
$senhaConfirmacao = "";
$nome = "";
$aceito = "";
$aceitoMarketing = 0;

$geraFormulario = "Sim";


if (isset($_POST['submit-criar-conta'])) {


    $podeCriarRegisto = "Sim";

    // obter parametros (determinadas validações poderiam ser feitas no lado cliente)
    $codigo = mysqli_real_escape_string($_conn, $_POST['formCodigo']);
    $codigo = strtolower(trim($codigo));
    $email = mysqli_real_escape_string($_conn, $_POST['formEmail']);
    $email = strtolower(trim($email));
    $senha = mysqli_real_escape_string($_conn, $_POST['formSenha1']);
    $senha = trim($senha);
    $senhaConfirmacao = mysqli_real_escape_string($_conn, $_POST['formSenha2']);
    $senhaConfirmacao = trim($senhaConfirmacao);
    $nome = mysqli_real_escape_string($_conn, $_POST['formNome']);
    $nome = trim($nome);
    if (isset($_POST['formAceito'])){
        $aceito = $_POST['formAceito'];
    }
    

    if ($aceito == "aceito_marketing") {
        $aceitoMarketing = 1;
    } else {
        $aceitoMarketing = 0;
    }

    // retirar possíveis tags html do código
    $codigo = strip_tags($codigo);
    $email = strip_tags($email);
    $nome = strip_tags($nome);

    // não permitir que um user tenha espaços no código...
    $codigo = str_replace(' ', '', $codigo);

    // validar parametros recebidos

    if (strlen(trim($codigo)) < 4) {
        $mensagemErroCodigo = "O código é demasiado curto!";
        $podeCriarRegisto = "Nao";
    }

    $email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $mensagemErroEmail = "O e-mail não é válido!";
        $podeCriarRegisto = "Nao";
    }


    if (strlen(trim($senha)) < 8) {
        $mensagemErroSenha = "A senha tem que ter pelo menos 8 caracteres!";
        $podeCriarRegisto = "Nao";
    }

    if ($senha != $senhaConfirmacao) {
        $mensagemErroSenhaRecuperacao = "A senha de confirmação deve ser igual à primeira senha!";
        $podeCriarRegisto = "Nao";
    }


    if (strlen(trim($nome)) < 2) {
        $mensagemErroNome = "O nome é demasiado curto!";
        $podeCriarRegisto = "Nao";
    }


    if ($podeCriarRegisto == "Sim") {

        // validações corretas: validar se existe utilizador
        $stmt = $_conn->prepare('SELECT * FROM USERS WHERE CODIGO = ?');
        $stmt->bind_param('s', $codigo);
        $stmt->execute();

        $resultadoUsers = $stmt->get_result();

        if ($resultadoUsers->num_rows > 0) {

            $mensagemErroCodigo = "Já existe um utilizador registado com este código.";

            $stmt->free_result();
            $stmt->close();
        } else {


            ///////////////////////////////////
            // INSERE UTILIZADOR NA BASE DE DADOS
            //////////
            $sql = "INSERT INTO USERS (CODIGO, EMAIL, PASSWORD, NOME, NIVEL,USER_STATUS, MENSAGENS_MARKETING,DATA_HORA) 
                                        VALUES (?,?,?,?,?,?,?,?)";

            if ($stmt = mysqli_prepare($_conn, $sql)) {

                $nivel = 1;
                $status = 0;

                $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

                date_default_timezone_set('Europe/Lisbon');
                $data_hora = date("Y-m-d H:i:s", time());

                mysqli_stmt_bind_param($stmt, "ssssiiis", $codigo, $email, $senhaHash, $nome, $nivel, $status, $aceitoMarketing, $data_hora);


                mysqli_stmt_execute($stmt);

                $geraFormulario = "Nao";
            } else {

                echo "STATUS ADMIN (inserir user): " . mysqli_error($_conn);
            }

            mysqli_stmt_close($stmt);

            $code = md5(uniqid(rand()));

            $sql = "UPDATE  USERS SET TOKEN_CODE=? WHERE CODIGO=?";

            if ($stmt = mysqli_prepare($_conn, $sql)) {

                mysqli_stmt_bind_param($stmt, "ss", $code, $codigo);
                mysqli_stmt_execute($stmt);

                // Update efetuado com sucesso, preparar e enviar mensagem 
                $id = base64_encode($codigo);

                $urlPagina = "https://psi-website.herokuapp.com/";

                $subject = "Verificação de Email";

                $template_link = "";

                $template_link = $urlPagina . "userAtivarConta.php?id=$id&code=$code";

                include_once './emailTemplates.php';

                $mensagem = $verifyEmailTemplate;

                // send email
                mail_utf8($email, $subject, $mensagem, $nome);
                $msgTemporaria = $msgTemporaria . " " . "$nome, verifique por favor a sua caixa de correio para ativar de imediato a sua conta! Por vezes estas mensagens são consideradas correio não solicitado. Se não vir a mensagem de ativação verifique o seu correio não solicitado (SPAM).";
            } else {
                echo "STATUS ADMIN (gerar token): " . mysqli_error($_conn);
            }

            mysqli_stmt_close($stmt);
        }
    }
}
?>


<!doctype html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StockTravel - Criar conta</title>




    <!-- Bootstrap core CSS -->
    <?php include_once  './includes/estilos.php'; ?>

    <style>
        .bd-placeholder-img {
            font-size: 1.125rem;
            text-anchor: middle;
            -webkit-user-select: none;
            -moz-user-select: none;
            user-select: none;
        }

        @media (min-width: 768px) {
            .bd-placeholder-img-lg {
                font-size: 3.5rem;
            }
        }
    </style>
    <!-- Custom styles for this template -->
    <link rel="stylesheet" href="./css/criarConta.css">
</head>

<body class="text-center">
    <?php include_once  './includes/menus.php'; ?>

    <main class="form-signin mt-5">
        <?php
            if ($geraFormulario == "Sim") {
        ?>
        <form class="mt-5" action="" method="POST">
            <img class="mb-4" src="./imagens/logo.png" alt="" width="90" height="57">
            <h1 class="h3 mb-3 fw-normal">Criar Conta</h1>

            <div class="form-floating">
                <input type="text" class="form-control" id="floatingUser" placeholder="Utilizador" name="formCodigo" value="<?php echo $codigo; ?>">
                <label for="floatingUser">Utilizador</label>
            </div>
            <p class=""><?php echo $mensagemErroCodigo; ?></p>
            <div class="form-floating">
                <input type="text" class="form-control" id="floatingName" placeholder="Nome completo" name="formNome" value="<?php echo $nome; ?>">
                <label for="floatingName">Nome completo</label>
            </div>
            <p class=""><?php echo $mensagemErroNome; ?></p>
            <div class="form-floating">
                <input type="email" class="form-control" id="floatingEmail" placeholder="E-mail" name="formEmail" value="<?php echo $email; ?>">
                <label for="floatingEmail">E-mail</label>
            </div>
            <p class=""><?php echo $mensagemErroEmail; ?></p>
            <div class="form-floating">
                <input type="password" class="form-control" id="floatingPassword" placeholder="Senha" name="formSenha1" value="<?php echo $senha; ?>">
                <label for="floatingPassword">Senha</label>
            </div>
            <p class=""><?php echo $mensagemErroSenha; ?></p>
            <div class="form-floating">
                <input type="password" class="form-control" id="floatingConfirmPassword" placeholder="Confirmação de Senha" name="formSenha2" value="<?php echo $senhaConfirmacao; ?>">
                <label for="floatingConfirmPassword">Confirmação de Senha</label>
            </div>
            <p class=""><?php echo $mensagemErroSenhaRecuperacao; ?></p>
            <p><input class="w3-large w3-text-black" type="checkbox" name="formAceito" value="aceito_marketing" <?php if ($aceitoMarketing == 1 ) { echo " checked"; } ?>> Aceito que os meus dados sejam utilizados para efeitos de marketing</p>   
            </div>
            <button class="w-100 btn btn-lg btn-success mb-3" name="submit-criar-conta" type="submit">Criar Conta</button>
        </form>
    </main>

</body>

</html>

<!-- ___________________________________________ -->
    <?php
            } else {
    ?>


        <p class="w3-center w3-large">Conta criada com sucesso</p>
        <p class="w3-center w3-large"><b><?php echo $msgTemporaria; ?></b></p>

        <form action="./index.php" method="POST">
            <p><button class="w3-button w3-black" type="submit">VOLTAR</button></p>
        </form>


    <?php
            }
    ?>




    </div>
</body>

</html>