<?php include_once  './conexaobasedados.php'; ?>
<?php
    $defaultShop = "GOOGL";
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add':
                $sql = "INSERT INTO Favoritos (User, Shop) 
                VALUES (?,?)";
                $shop = $_POST['shop'];
                if ($stmt = mysqli_prepare($_conn, $sql)) {

                    mysqli_stmt_bind_param($stmt, "ss", $_SESSION["UTILIZADOR"], $shop);

                    mysqli_stmt_execute($stmt);

                } else {

                    echo "STATUS ADMIN (inserir user): " . mysqli_error($_conn);
                }
                break;
            case 'remove':
                break;
        }
    }
    if (isset($_POST['shopName'])) {
        $defaultShop = $_POST['shopName'];
    }

?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
        <h1 class="h2">Dashboard</h1>
    </div>
    <div>
        <div class="d-flex">
            <span class="h3 ms-4 " id="acName" >GOOGL</span>
            <form action="" method="POST">
                <input type="text" style="display: none;" name="shop" id="shopName"></input>
                <button class="btn btn-outline-secondary ms-3" type="submit" value="add" name="action"><svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-activity">
                            <path d="M19 21l-7-5-7 5V5a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2z"></path>
                        </svg>
                </button>
            </form>
        </div>


        <div id="wrapper">
            <div id="table" class="table-div">
                <table id="series-table" class="table">
                    <thead>
                        <tr class="table-primary">			  
                        <th scope="col">Data</th>
                        <th scope="col">Abertura</th>
                        <th scope="col">Fecho</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>	
            </div>
                
            <div id="chart-column" class="column-chart"></div>
        </div>
            
        <div class="col-md-12">
            <div id="chart-line" class="line-chart"></div>
        </div>
    </div>
</main>
<?php include_once "js/alphavantage.php" ?>