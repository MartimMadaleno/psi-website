<?php
error_reporting(E_ERROR | E_PARSE);
session_start();


include_once  './conexaobasedados.php';

$mensagemErroCodigo = "";
$mensagemErroSenha = "";

if (isset($_POST['botao-iniciar-sessao'])) {

    $codigo = strtolower(trim(mysqli_real_escape_string($_conn, $_POST["formCodigo"])));
    $codigo = trim($codigo);

    $senha = trim(mysqli_real_escape_string($_conn, $_POST["formSenha"]));
    $senha = trim($senha);

    $codigo = strip_tags($codigo);

    $stmt = $_conn->prepare('SELECT * FROM USERS WHERE CODIGO = ?');
    $stmt->bind_param('s', $codigo);
    $stmt->execute();

    $resultadoUsers = $stmt->get_result();

    if ($resultadoUsers->num_rows > 0) {
        while ($rowUsers = $resultadoUsers->fetch_assoc()) {

            if ($rowUsers['USER_STATUS'] == 2) { // utilizador bloqueado

                $mensagemErroSenha = "Não é possível entrar no sistema. Contacte os nossos serviços para obter ajuda.";
            } else  if ($rowUsers['USER_STATUS'] == 0) { // Utilizador criou a conta mas não ativou

                $mensagemErroSenha =  $rowUsers['NOME'] . ", ainda não ativou a sua conta. A mensagem com o código inicial de ativação de conta foi enviada para a sua caixa de correio. Caso não a encontre na sua caixa de entrada, verifique também o seu correio não solicitado ou envie-nos um email para ativarmos a sua conta. Obrigado.";
            } else  if (password_verify($senha, $rowUsers["PASSWORD"])) {

                $_SESSION["UTILIZADOR"] = $rowUsers["CODIGO"];
                $_SESSION["NIVEL_UTILIZADOR"] = $rowUsers["NIVEL"];
                $_SESSION["NOME_UTILIZADOR"] = $rowUsers["NOME"];
                $_SESSION["EMAIL_UTILIZADOR"] = $rowUsers["EMAIL"];

                header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
                header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // past date to encourage expiring immediately
                header("Location: index.php");
            } else {
                $mensagemErroSenha = "Senha incorreta!";
            }
        }
    } else {
        $mensagemErroCodigo = "O código de utilizador não existe na nossa base de dados!";
    }

    $stmt->free_result();
    $stmt->close();
}
?>

<!doctype html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>StockTravel - Entrar</title>




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
    <link rel="stylesheet" href="./css/signin.css">
</head>

<body class="text-center">
    <?php include_once  './includes/menus.php'; ?>

    <main class="form-signin mt-5">
        <form class="mt-5" action="" method="POST">
            <img class="mb-4" src="./imagens/logo.png" alt="" width="90" height="57">
            <h1 class="h3 mb-3 fw-normal">Iniciar Sessão</h1>

            <div class="form-floating">
                <input type="text" class="form-control" id="floatingInput" placeholder="name@example.com" name="formCodigo">
                <label for="floatingInput">Utilizador</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" id="floatingPassword" placeholder="Password" name="formSenha">
                <label for="floatingPassword">Senha</label>
            </div>
            <p class="text-danger"><?php echo $mensagemErroCodigo;?></p>
            <p class="text-danger"><?php echo $mensagemErroSenha;?></p>
            <button class="w-100 btn btn-lg btn-primary mb-3" name="botao-iniciar-sessao" type="submit">Entrar</button>
            <span class="mt-4">Esqueceu-se da senha?</span>
            <br>
            <span><a href="userRecuperarSenha.php" style="color:#ff0000"> Recuperar senha.</a></span>
        </form>
    </main>

</body>

</html>