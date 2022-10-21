<?php

session_start();

include_once  './conexaobasedados.php';


$mensagem = "";

if (isset($_GET['id']) && isset($_GET['code'])) {


    $codigo = base64_decode($_GET['id']); // código do utilizador...
    $code = $_GET['code']; // token...


    $stmt = $_conn->prepare('SELECT * FROM USERS WHERE CODIGO = ?');
    $stmt->bind_param('s', $codigo);
    $stmt->execute();

    $resultadoUsers = $stmt->get_result();

    if ($resultadoUsers->num_rows > 0) {
        while ($rowUsers = $resultadoUsers->fetch_assoc()) {

            $estado = $rowUsers['USER_STATUS'];

            if ($estado != 0) {

                $mensagem = "A sua conta já se encontra ativa. Pode iniciar sessão com a sua conta.";
            } else {
                // Procedimento de segurança para ativar a conta...
                if (($code != $rowUsers['TOKEN_CODE'] || $rowUsers['TOKEN_CODE'] == '')) {

                    $mensagem = "O código de ativação não está correto ou já foi utilizado.";
                } else {
                    // o código de ativação está correto e não foi ainda utilizado
                    // fazer update à tabela de USERS para atualizar o estado e limpar o token
                    $sql = "UPDATE  USERS SET USER_STATUS=1, TOKEN_CODE=? WHERE CODIGO=?";

                    if ($stmt = mysqli_prepare($_conn, $sql)) {

                        $code = "";
                        mysqli_stmt_bind_param($stmt, "ss", $code, $codigo);
                        mysqli_stmt_execute($stmt);


                        $mensagem = "A sua conta foi ativada com sucesso! Já pode iniciar a sua sessão."; // ok


                    } else {
                        // falhou a atualização 

                        echo "STATUS ADMIN (ativar conta): " . mysqli_error($_conn);
                    }
                    mysqli_stmt_close($stmt);
                }
            }
        }
    } else {

        $mensagem = "A conta para ativar não existe na nossa base de dados!";
    }
} else {

    // caso alguém use o endereço sem parametros volta 
    // de imediato para a página principal sem dar qualquer
    // tipo de mensagem

    // encaminhar para página principal
    header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
    header('Expires: Sat, 26 Jul 1997 05:00:00 GMT'); // past date to encourage expiring immediately
    header("Location: index.php"); // encaminhar de imediato




}


?>
<!DOCTYPE html>
<html>
<title>StockTravel - Ativar conta</title>
<?php include_once  './includes/estilos.php'; ?>

<body>
    <?php include_once  './includes/menus.php'; ?>
    <?php include_once './includes/sidenav.php'; ?>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-6">
                <div class="row justify-content-center">
                    <div class="col-8">
                        <h2 class="h2 text-center">ATIVAR CONTA</h2>
                        <h4 class="mt-2">Ativar a sua conta de utilizador.</h4>
                    </div>
                    <span class="mt4"><?php echo $mensagem;?></span>
                </div>

                <form action="./index.php" method="POST">

                    <p><button class="btn btn-dark" type="submit">PÁGINA PRINCIPAL</button></p>

                </form>
            </div>
        </div>
    </div>

    <?php include_once  './includes/scripts.php'; ?>
</body>

</html>