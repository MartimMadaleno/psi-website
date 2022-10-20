<!-- Header with full-height image -->
<?php 
?>
 <?php if (!isset($_SESSION["UTILIZADOR"]) ) { ?>
    <header class="bgimg-1" id="home">
      <div class="w3-display-left w3-text-white" style="padding:48px">
        <span class="w3-jumbo w3-hide-small">StockTravel - Imagens na cloud!</span><br>
        <span class="w3-xxlarge w3-hide-large w3-hide-medium">Cloud Gallery</span><br>
        <span class="w3-large">Guardar imagens a partir de qualquer local e a qualquer hora.</span>
        <p>
          <a href="userCriarConta.php" class="w3-button w3-white w3-padding-large w3-large w3-margin-top w3-opacity w3-hover-opacity-off">Criar conta e começar já!</a>
          <a href="userEntrar.php" class="w3-button w3-white w3-padding-large w3-large w3-margin-top w3-opacity w3-hover-opacity-off">Já tenho conta.</a>
                  
        </p>
      </div> 
    </header>
<?php  } ?>
