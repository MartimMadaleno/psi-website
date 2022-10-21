
<?php
if (isset($_SESSION["UTILIZADOR"])) {
?>
    <div class="container-fluid">
        <div class="row">
            <?php
            include_once './includes/sidenav.php';
            include_once './includes/graphics.php';
            ?>
        </div>
    </div>
<?php } else { ?>
    <?php
	$api_url = 'http://newsapi.org/v2/top-headlines?country=pt&category=business&apiKey=bd9e433c9a984bda8779f205a5e27c5e';
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $api_url);
	curl_setopt($ch, CURLOPT_HEADER, false);
	$api_data = curl_exec($ch);
	// $json = file_get_contents($api_url);
	echo $api_data;
	$json = $api_data;
	$json = file_get_contents($api_url);
	if(!empty($json)){
		curl_close($ch);
		$json = json_decode($json);
		$pageMax = 5;
		$pageNow = 0;
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
		if ($pageNow > count($json->articles)) {
			$pageNow = count($json->articles);
		}
	?>
    <div class="container mt-4">
        <div class="row justify-content-center align-items-center">
            <div class="col-8 text-center border-bottom">
                <h1 class="h2">Notícias</h1>
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
	<?php }else{ curl_close($ch);?>
		
		<div class="container mt-4">
		<div class="row justify-content-center align-items-center">
			<div class="col-8 text-center border-bottom">
				<h1 class="h2">No data found!</h1>
			</div>
		</div>
	</div>
	<?php } ?>
<?php } ?>