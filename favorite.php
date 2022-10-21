<?php
session_start();
if (isset($_SESSION["UTILIZADOR"])) {
    include_once  './conexaobasedados.php';
    if (isset($_POST['action'])) {
        $shop = $_POST['shop'];
        $stmt = $_conn->prepare('DELETE FROM Favoritos WHERE User = ? and Shop = ?');
        $stmt->bind_param('ss', $_SESSION["UTILIZADOR"], $shop);
        $stmt->execute();
    }
    $codigo = $_SESSION["UTILIZADOR"];
    $stmt = $_conn->prepare('SELECT * FROM Favoritos WHERE User = ?');
    $stmt->bind_param('s', $codigo);
    $stmt->execute();
    $resultadoUsers = $stmt->get_result();
?>
    <?php include_once  './includes/estilos.php'; ?>
    <?php include_once './includes/sidenav.php'; ?>
    <?php include_once  './includes/menus.php'; ?>
    <title>StockTravel - Favoritos</title>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-6">
                <div class="row justify-content-center">
                    <div class="col-8">
                        <h2 class="h2 text-center">Favoritos</h2>
                    </div>
                </div>
            </div>
        </div>
        <?php while ($rowUsers = $resultadoUsers->fetch_assoc()) { ?>

            <div class="row mt-3 justify-content-center align-items-center">
                <div class="col-8 d-flex justify-content-center">
                    <div class="card" style="width: 100%;">
                        <div class="card-body pt-4 d-flex justify-content-between align-items-center">
                            <form action="./index.php" method="POST">
                                <button class="card-title btn btn-l fs-4" type="submit" name="shopName" value="<?php echo $rowUsers["Shop"]; ?>"><?php echo $rowUsers["Shop"]; ?></button>
                            </form>
                            <form action="" method="POST">
                                <input type="text" style="display: none;" name="shop" value="<?php echo $rowUsers["Shop"]; ?>"></input>
                                <button class="btn btn-danger ms-2 justify-content-end" type="submit" value="remove" name="action">
                                <i class="material-icons">delete</i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        <?php } ?>
    <?php } else {
    header("Location: index.php");
}
    ?>