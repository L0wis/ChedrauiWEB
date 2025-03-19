<header data-bs-theme="dark">
  <div class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <div class="d-flex align-items-center">
        <a href="index.php" class="navbar-brand">
          <strong><i class="bi bi-check-circle-fill"></i> Chedraui</strong>
          <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
        </a>
       
      </div>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" 
      aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarHeader">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="navbar-item">
            <a href="xmenu.php" class="nav-link active"><i class="bi bi-layout-text-sidebar"></i> Inicio</a>
          </li>
          <li class="navbar-item">
            <a href="index.php" class="nav-link active"><i class="bi bi-layout-text-sidebar"></i> Catalogo</a>
          </li>
          <li class="navbar-item">
            <a href="ofertas.php" class="nav-link active"><i class="bi bi-cart4"></i> Ofertas</a>
          </li>
          <li class="navbar-item">
            <a href="atencion_cliente.php" class="nav-link"><i class="bi bi-person-workspace"></i> Atencion al cliente</a>
          </li>
          <!--------
          <li class="navbar-item">
            <a href="http://localhost/chedraui/admin/" class="nav-link active"><i class="bi bi-person-fill-check"></i> Administradores</a>
          </li>
-->
        </ul>

<!-- Formulario de Búsqueda -->
<form class="d-flex ms-auto me-auto" method="GET" action="search.php">
    <input class="form-control me-2" type="search" placeholder="Buscar productos" aria-label="Buscar" name="query">
    <button class="btn btn-outline-success me-2" type="submit">
        <i class="bi bi-search"></i> <!-- Agrega el icono de búsqueda -->
    </button>
</form>


<!-- Botón de Acción -->
<div class="btn-group me-2">
    <button type="button" class="btn btn-info dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false"> <i class="bi bi-backpack4-fill"></i>
        Categorias
    </button>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="categorias.php?categoria=1"><i class="bi bi-cup-straw"></i> Bebidas</a></li>
        <li><a class="dropdown-item" href="categorias.php?categoria=2"><i class="bi bi-egg"></i> Comida</a></li>
        <li><a class="dropdown-item" href="categorias.php?categoria=5"><i class="bi bi-droplet"></i> Limpieza</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="mas_categorias.php"><i class="bi bi-eject-fill"></i> Ver más Categorias</a></li>
    </ul>
</div>


<!-- Botón de Carrito -->
<a href="checkout.php" class="btn btn-primary btn-sm me-2">
    <i class="bi bi-shop-window"></i> Carrito
    <span id="num_cart" class="badge bg-secondary"><?php echo $num_cart; ?></span>
</a>
        <?php if(isset($_SESSION['user_id'])) { ?>
        <div class="dropdown">
          <button class="btn btn-success btn-sm dropdown-toggle" type="button" id="btn_session" data-bs-toggle="dropdown" 
          aria-expanded="false">
            <i class="bi bi-person-circle"></i> &nbsp; <?php echo $_SESSION['user_name']; ?>
          </button>
          <ul class="dropdown-menu" aria-labelledby="btn_session">
            <li><a class="dropdown-item" href="compras.php"><i class="bi bi-bag-check-fill"></i> Mis compras</a></li>
            <li><a class="dropdown-item" href="logout.php"><i class="bi bi-door-open-fill"></i> Cerrar sesion</a></li>
          </ul>
        </div>
        <?php } else { ?>
        <a href="login.php" class="btn btn-success btn-sm">
          <i class="bi bi-person-circle"></i> Ingresar
        </a>
        <?php } ?>
      </div>
    </div>
  </div>
</header>
