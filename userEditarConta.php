<?php

session_start();

include_once  './conexaobasedados.php';


if (isset($_POST['botao-cancelar-alteracoes'])) {
    header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
    header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // past date to encourage expiring immediately
    header("Location: index.php");
}

if (isset($_POST['botao-esqueci-senha'])) {

    session_destroy(); // destruição imediata de sessão

    header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
    header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // past date to encourage expiring immediately
    header("Location: userRecuperarSenha.php");
}




$msgTemporaria = "none";

$mensagemErroNome = "";
$mensagemErroSenha = "";

if (!isset($_SESSION["UTILIZADOR"])) {
    header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
    header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // past date to encourage expiring immediately
    header("Location: index.php");
} else {
    // ler informações de conta 
    $codigo = $_SESSION["UTILIZADOR"];

    $stmt = $_conn->prepare('SELECT * FROM USERS WHERE CODIGO = ?');
    $stmt->bind_param('s', $codigo);
    $stmt->execute();

    $resultadoUsers = $stmt->get_result();

    if ($resultadoUsers->num_rows > 0) {
        while ($rowUsers = $resultadoUsers->fetch_assoc()) {

            $senha = "";
            $senhaEncriptada = $rowUsers['PASSWORD'];

            if (!isset($_POST["nome"])) {

                $nome = $rowUsers['NOME'];
                $receberMsgs = $rowUsers['MENSAGENS_MARKETING'];
            } else {

                $podeRegistar = "Sim";

                ///////// em modo de alteração - filtrar e validar campos
                $nome = mysqli_real_escape_string($_conn, $_POST['nome']);
                $nome = trim($nome);

                $receberMensagens = $_POST['receberMensagens'];
                if ($receberMensagens == "Sim") {
                    $receberMsgs = 1;
                } else {
                    $receberMsgs = 0;
                }

                if (strlen(trim($nome)) < 2) {
                    $mensagemErroNome = "O nome é demasiado curto!";
                    $podeRegistar = "Nao";
                }
            }
        }
    } else {
        echo "STATUS ADMIN (Editar conta): " . mysqli_error($_conn);
    }
    mysqli_stmt_close($stmt);
}

if (isset($_POST['botao-gravar-alteracoes'])) {


    // verificar senha por questões de segurança

    $senha = mysqli_real_escape_string($_conn, $_POST['senha']);
    $senha = trim($senha);


    if (password_verify($senha, $senhaEncriptada)) {

        // senha OK, filtar e validar inputs



    } else {

        $mensagemErroSenha = "Senha incorreta!";
        $podeRegistar = "Nao";
    }


    if ($podeRegistar == "Sim") {


        ///////////////////////////////////
        // ALTERA
        //////////////////////////////////

        $nome = strip_tags($nome); // demonstração da remoção de caracteres especiais html por exemplo..

        $sql = "UPDATE USERS SET NOME = ?, MENSAGENS_MARKETING = ?  WHERE CODIGO = ?";

        if ($stmt = mysqli_prepare($_conn, $sql)) {

            mysqli_stmt_bind_param($stmt, "sis", $nome, $receberMsgs, $codigo);


            mysqli_stmt_execute($stmt);

            $msgTemporaria = "block";

            // atualizar variável de sessão, a questão de receber mensagens de marketing não
            // é uma variável de sessão, não é necessário guardar em sessão.

            $_SESSION["NOME_UTILIZADOR"] = $nome;


            // encaminhar com timer 3 segundos
            header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
            header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // past date to encourage expiring immediately
            header("Refresh: 3; URL=index.php");
        } else {
            //echo "ERROR: Could not prepare query: $sql. " . mysqli_error($_conn);
            echo "STATUS ADMIN (alterar definições): " . mysqli_error($_conn);
        }

        mysqli_stmt_close($stmt);
    }
}
?>


<!DOCTYPE html>
<html>
<title>StockTravel - Editar conta</title>
<?php include_once  './includes/estilos.php'; ?>



<body>
    <?php include_once  './includes/menus.php'; ?>
    <?php include_once './includes/sidenav.php'; ?>

    <!-- interface para entrar no sistema -->
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-6">
                <div class="row justify-content-center">
                    <div class="col-8">
                        <h2 class="h2 text-center">EDITAR CONTA</h2>
                    </div>

                    <p class="mt-3 ">Por uma questão de segurança, para alterar as suas definições de conta deverá digitar a sua senha. No final, não se esqueça de gravar as alterações. Se apenas pretende alterar a sua senha use a opção "Esqueci-me da senha".</p>
                    <p class="alert alert-success" id="messageTemp" style="display: <?php echo $msgTemporaria ?>;">Definições de conta alteradas com sucesso. <br> Será reencaminhado para a página principal.</p>

                    <div class="mt-3">


                        <form action="#" method="POST">


                            <input class="form-control" type="text" placeholder="Nome" name="nome" value="<?php echo $nome; ?>">
                            <p class="text-danger"><?php echo $mensagemErroNome; ?></p>

                            <p><input class="form-control" type="password" placeholder="Senha" name="senha" value="<?php echo $senha; ?>"></p>
                            <p class="w3-large w3-text-red"><?php echo $mensagemErroSenha; ?></p>


                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <label class="input-group-text" for="inputGroupSelect01">Pretendo receber mensagens de marketing:</label>
                                </div>
                                <select class="custom-select" name="receberMensagens">
                                    <option value="Sim" <?php if ($receberMsgs == 1) {
                                                            echo " selected";
                                                        } ?>>Sim</option>
                                    <option value="Não" <?php if ($receberMsgs == 0) {
                                                            echo " selected";
                                                        } ?>>Não</option>
                                </select>
                            </div>


                            <p class="mt-3">
                                <button class="btn btn-success" name="botao-gravar-alteracoes" type="submit"> <i class="material-icons" style="font-size:24px;vertical-align:middle;">done</i> GRAVAR ALTERAÇÕES</button>
                                <button class="btn btn-danger" name=botao-cancelar-alteracoes type="submit"> <i class="material-icons" style="font-size:24px;vertical-align:middle;">cancel</i> CANCELAR ALTERAÇÕES</button>

                            </p>
                        </form>


                        <form action="./userCancelarConta.php" method="POST">

                            <button class="btn btn-dark" name="botao-cancelar-minha-conta" type="submit"> <i class="material-icons" style="font-size:24px;vertical-align:middle;">no_accounts</i> Pretendo cancelar a minha conta</button>

                        </form>

                        <form action="./userRecuperarSenha.php" method="POST" class="mt-2">

                            <button class="btn btn-dark" name="botao-esqueci-senha" type="submit"> <i class="material-icons" style="font-size:24px;vertical-align:middle;">key</i> Esqueci-me da senha</button>

                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>