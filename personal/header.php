<?php

ob_start();

// Verificar si el usuario ha iniciado sesión y obtener su ID
$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Verificar si se ha iniciado sesión
if ($user_id) {
    // Conexión a la base de datos
    require '../config/database.php';
    $db = new Database();
    $con = $db->conectar();

    // Consulta para obtener el puesto y el nombre del usuario
    $sql = "SELECT puesto, nombre FROM personal WHERE id = :user_id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Si el usuario no se encuentra, establecer un valor predeterminado
    if (!$userData) {
        $userData['nombre'] = "Usuario no identificado";
        $userData['puesto'] = "Puesto no identificado";
    }
} else {
    // Si el usuario no ha iniciado sesión, establecer valores predeterminados
    $userData['nombre'] = "Usuario no identificado";
    $userData['puesto'] = "Puesto no identificado";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Chedraui-Personal</title>
    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/style.min.css" rel="stylesheet" />
    <link href="<?php echo PEARSON_URL; ?>css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.2/font/bootstrap-icons.min.css">
</head>

<body class="sb-nav-fixed">
    <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
        <!-- Navbar Brand-->
        <a class="navbar-brand ps-3" href="http://localhost/chedraui/personal/inicio.php">Chedraui</a>
        <!-- Botón de retroceso al índice -->
        <a class="btn btn-primary" href="index.php"><i class="bi bi-house-door-fill"></i> Inicio</a>
        <!-- Sidebar Toggle-->
        <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0" id="sidebarToggle" href="#!"><i
                class="fas fa-bars"></i></button>
        <!-- Navbar Search-->
        <!-- Resto del contenido del encabezado... -->
        <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
            <div class="input-group">
                
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

                        <a class="nav-link" href="<?php echo PEARSON_URL; ?>ventas">
                            <div class="sb-nav-link-icon"><i class="bi bi-tags-fill"></i></div>
                            Ventas Recientes
                        </a>

                        <a class="nav-link" href="<?php echo PEARSON_URL; ?>ventas_hechas">
                            <div class="sb-nav-link-icon"><i class="bi bi-truck"></i></div>
                            Ventas Hechas
                        </a>

                        <a class="nav-link" href="<?php echo PEARSON_URL; ?>inventario">
                            <div class="sb-nav-link-icon"><i class="bi bi-basket3-fill"></i></div>
                            Inventario
                        </a>

                        <a class="nav-link" href="<?php echo PEARSON_URL; ?>estadisticas">
                            <div class="sb-nav-link-icon"><i class="bi bi-bar-chart-line-fill"></i></div>
                            Estadisticas
                        </a>
                    </div>
                </div>
                <div class="sb-sidenav-footer">
                    <div class="small">Haz iniciado sesión como:</div>
                    <div><?php echo $userData['nombre']; ?></div>
                    <div><?php echo $userData['puesto']; ?></div>

            </nav>
        </div>
        <div id="layoutSidenav_content">
</body>

</html>