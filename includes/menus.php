<?php 
if (isset($_SESSION["UTILIZADOR"])) { 
?>

<header class="navbar navbar-dark bg-dark flex-md-nowrap p-0 shadow">
  <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 py-2 fs-5" href="#">StockTravel</a>
  <button class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon btn"></span>
  </button>
  <input class="form-control form-control-dark w-100" onblur="validar()" id="Search" type="text" placeholder="Search" aria-label="Search">
  <div class="navbar-nav">
    <div class="nav-item text-nowrap">
      <a class="nav-link px-3" href="./userSair.php"><i class="material-icons" style="font-size:24px;vertical-align:middle;">logout</i>Sair</a>
    </div>
  </div>
</header>

<?php } else { ?> 

  <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container-fluid">
      <a class="navbar-brand" href="./">StockTravel</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#main-nav" aria-controls="main-nav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="main-nav">
        <ul class="navbar-nav mb-2 mb-lg-0 ">
          <li class="nav-item mx-2 d-flex align-items-center" >
          <a class="btn btn-outline-secondary nav-link " aria-current="page" href="./userCriarConta.php"><i class="material-icons" style="font-size:24px;vertical-align:middle;">add_box</i>Criar conta</a>
          </li>
          <li class="nav-item mx-2 d-flex align-items-center">
          <a class="btn btn-outline-secondary nav-link " aria-current="page" href="./userEntrar.php"><i class="material-icons" style="font-size:24px;vertical-align:middle;">login</i>Entrar</a>
          </li>
        </u>
    </div>
  </div>
</nav>

<?php } ?>
    <?php
        // if (isset($_SESSION["NIVEL_UTILIZADOR"])) {
        // if ($_SESSION["NIVEL_UTILIZADOR"] == 2) { 
    ?> 