<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Chedraui-Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="<?php echo ADMIN_URL; ?>css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="http://localhost/chedraui/admin/inicio.php">Chedraui</a>
        <!-- Botón de retroceso al índice -->
        <a class="btn btn-primary" href="index.php"><i class="bi bi-house-door-fill"></i> Inicio</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <!-- Resto del contenido del encabezado... -->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group">
                <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..."
                    aria-describedby="btnNavbarSearch" />
                <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i
                        class="fas fa-search"></i></button>
            </div>
        </form>
        <!-- Navbar-->
        <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown"
                    aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    <li><a class="dropdown-item" href="#!">Settings</a></li>
                    <li><a class="dropdown-item" href="#!">Activity Log</a></li>
                    <li>
                        <hr class="dropdown-divider" />
                    </li>
                    <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
                </ul>
            </li>
        </ul>
    </nav>
    <div id="layoutSidenav">
        <div id="layoutSidenav_nav">
            <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                <div class="sb-sidenav-menu">
                    <div class="nav">
                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>configuracion">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Configuracion
                        </a>

                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>backups">
                            <div class="sb-nav-link-icon"><i class="fas fa-save"></i></div>
                            Backups
                        </a>

                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>categorias">
                            <div class="sb-nav-link-icon"><i class="bi bi-tags-fill"></i></div>
                            Categorias y Ofertas
                        </a>

                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>ventas">
                            <div class="sb-nav-link-icon"><i class="bi bi-cash"></i></div>
                            Ventas Totales
                        </a>

                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>precio">
                            <div class="sb-nav-link-icon"><i class="bi bi-currency-exchange"></i></div>
                            Manejo de Precio
                        </a>

                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>proveedores">
                            <div class="sb-nav-link-icon"><i class="bi bi-truck"></i></div>
                            Proveedores
                        </a>

                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>productos">
                            <div class="sb-nav-link-icon"><i class="bi bi-basket3-fill"></i></div>
                            Productos
                        </a>

                        <a class="nav-link" href="<?php echo ADMIN_URL; ?>estadisticas">
                            <div class="sb-nav-link-icon"><i class="bi bi-bar-chart-line-fill"></i></div>
                            Estadisticas
                        </a>

                        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            Usuarios
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne"
                            data-bs-parent="#sidenavAccordion">
                            <nav class="sb-sidenav-menu-nested nav">
                                <div class="mb-2 text-center"> <!-- Agrega margen inferior -->
                                    <a href="../usuarios/administradores.php"
                                        class="btn btn-outline-primary me-4 btn-block w-90">Administrador</a>
                                </div>
                                <div class="mb-2 text-center"> <!-- Agrega margen inferior -->
                                    <a href="../usuarios/clientes.php"
                                        class="btn btn-outline-secondary me-4 btn-block w-90">Clientes</a>
                                </div>
                                <div class="mb-2 text-center"> <!-- Agrega margen inferior -->
                                    <a href="../usuarios/personal.php"
                                        class="btn btn-outline-primary me-4 btn-block w-90">Personal</a>
                                </div>
                            </nav>
                        </div>



                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Haz iniciado sesion como:</div>
                    Administrador
                </div>
            </nav>
        </div>
        <div id="layoutSidenav_content">
</body>

</html>