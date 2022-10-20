<?php
session_start();
if (isset($_SESSION["UTILIZADOR"])) {


    $pageMax = 5;
    if (isset($_POST['next'])) {
        $next = (int)$_POST['next'];
        $prev = $next - 1;
        $pageMin = $prev * $pageMax;
        $pageNow = $next * $pageMax;
    } elseif (isset($_POST['back'])) {
        $next = (int)$_POST['back'];
        $prev = $next - 1;
        $pageMin = $prev * $pageMax;
        $pageNow = $next * $pageMax;
    } else {
        $next = 1;
        $prev = 0;
        $pageMin = 0;
        $pageNow = 5;
    }
    $category = "business";
    $country = "pt";
    if (isset($_SESSION["category"])) {
        $category = $_SESSION["category"];
    }
    if (isset($_GET["category"])) {
        $category = $_GET["category"];
        $_SESSION["category"] = $category;
    }
    $json = file_get_contents("https://newsapi.org/v2/top-headlines?country=pt&category=$category&language=pt&apiKey=bd9e433c9a984bda8779f205a5e27c5e");
    $json = json_decode($json);
    if ($pageNow > count($json->articles)) {
        $pageNow = count($json->articles);
    }
    $categoryTitle = $category;
    $categoryTitle = ucfirst($categoryTitle);
?>
    <?php include_once  './includes/estilos.php'; ?>
    <?php include_once './includes/sidenav.php'; ?>
    <?php include_once  './includes/menus.php'; ?>
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-6">
                <div class="row justify-content-center">
                    <div class="col-8">
                        <h2 class="h2 text-center">Notícias | <?php echo $categoryTitle ?></h2>
                    </div>

                </div>
                <div class="row justify-content-end border-bottom">
                    <div class="col-3">
                        <div class="btn-group py-2">
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="dropdowncategory" data-bs-toggle="dropdown" aria-expanded="false">
                                Categoria
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdowncategory">
                                    <li><a class="dropdown-item" href="./news.php?category=general">Geral</a></li>
                                    <li><a class="dropdown-item" href="./news.php?category=business">Negócio</a></li>
                                    <li><a class="dropdown-item" href="./news.php?category=technology">Tecnologia</a></li>
                                    <li><a class="dropdown-item" href="./news.php?category=science">Ciência</a></li>
                                    <li><a class="dropdown-item" href="./news.php?category=sports">Desporto</a></li>
                                    <li><a class="dropdown-item" href="./news.php?category=entertainment">Entretenimento</a></li>
                                    <li><a class="dropdown-item" href="./news.php?category=health">Saúde</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php for ($i = $pageMin; $i < $pageNow; $i++) { ?>
            <?php $new = $json->articles[$i]; ?>
            <?php
            $title  = "";
            $description = "";
            $url = "";
            $urlToImage = "";
            if ($new->title) {
                $title = $new->title;
            }
            if ($new->description) {
                $description = $new->description;
            }
            if ($new->url) {
                $url = $new->url;
            }
            if ($new->urlToImage) {
                $urlToImage = $new->urlToImage;
            }
            ?>
            <div class="row mt-3 justify-content-center align-items-center">
                <div class="col-8 d-flex justify-content-center">
                    <div class="card" style="width: 40rem;">
                        <?php if ($urlToImage != "") { ?>
                            <img src="<?php echo $urlToImage; ?>" class="card-img-top" alt="News Img">
                        <?php } ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $title; ?></h5>
                            <p class="card-text"><?php echo $description; ?></p>
                            <a href="<?php echo $url; ?>" class="btn btn-primary">Ver notícia</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
        <div class="row mt-3 justify-content-center align-items-center my-5">
            <div class="col-8 d-flex justify-content-center">
                <form action="" method="POST">
                    <ul class="pagination">

                        <?php if ($pageMin == 0) { ?>

                            <li class="page-item disabled"><button class="page-link" name="back">Previous</button></li>

                        <?php } else { ?>

                            <li class="page-item"><button type="submit" value="<?php echo $next - 1 ?>" class="page-link" name="back">Previous</button></li>

                        <?php } ?>
                        <?php if ($pageNow >= count($json->articles)) { ?>

                            <li class="page-item disabled"><button class="page-link" name="next">Next</button></li>

                        <?php } else { ?>

                            <li class="page-item"><button type="submit" value="<?php echo $next + 1 ?>" class="page-link" name="next">Next</button></li>

                        <?php } ?>

                    </ul>
                </form>
            </div>
        </div>
    </div>
<?php } else {
    header("Location: index.php");
}
?>