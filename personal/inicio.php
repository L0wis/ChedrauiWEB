<?php include 'config/config.php'; ?>
<?php include 'header.php'; ?>

<?php
// Verificar si el usuario ha iniciado sesión y obtener su nombre
$nombre_usuario = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "Usuario";
?>

<main>
    <div class="container-fluid px-3">
        <h1 class="mt-4">¡Hola, que tal <?php echo $nombre_usuario; ?>!</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">¿Qué tienes en mente hoy?</li>
        </ol>

        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <!-- Tarjeta 1 -->
            <div class="col">
                <div class="card h-100">
                    <a href="ventas/index.php">
                        <img src="../personal/images/estrategias-de-ventas.png" class="card-img-top" alt="Realizar Venta">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">Realizar Venta</h5>
                        <p class="card-text">
                            Administra tus productos, realiza ventas y gestiona tu inventario de manera eficiente.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 2 -->
            <div class="col">
                <div class="card h-100">
                    <a href="ventas_hechas/index.php">
                        <img src="../personal/images/ventas.jpg" class="card-img-top" alt="Ventas Realizadas">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">Ventas Realizadas</h5>
                        <p class="card-text">
                            Explora la información detallada de tus ventas, clientes y pedidos recientes de manera sencilla.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 3 -->
            <div class="col">
                <div class="card h-100">
                    <a href="inventario/index.php">
                        <img src="../personal/images/control-de-inventario.1.0.jpg" class="card-img-top" alt="Administrar Inventario">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title">Administrar Inventario</h5>
                        <p class="card-text">
                            Mantén un control total sobre tu inventario, comunícate con proveedores y realiza pedidos con facilidad.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>
