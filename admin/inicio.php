<?php include 'config/config.php'; ?>
<?php include 'header.php'; ?>

<main>
    <div class="container-fluid px-3">
        <h1 class="mt-4 text-center">¡Bienvenido Administrador!</h1>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active text-center">¿Qué tienes en mente hoy?</li>
        </ol>

        <div class="row">
            <!-- Administrar Productos -->
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <a href="productos/index.php">
                        <img src="../images/administrar_productos.jpeg" class="card-img-top img-fluid" alt="Administrar Productos" style="height: 250px; object-fit: cover;">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title text-center mb-3">Administrar Productos</h5>
                        <p class="card-text text-center">Aquí puedes administrar tus productos, agregar nuevos, actualizar información o eliminarlos según sea necesario.</p>
                        <a href="productos/index.php" class="btn btn-primary btn-sm d-block mx-auto">Ir a la página</a>
                    </div>
                </div>
            </div>

            <!-- Veamos los Clientes -->
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <a href="usuarios/clientes.php">
                        <img src="../images/ver_clientes.jpeg" class="card-img-top img-fluid" alt="Veamos los Clientes" style="height: 250px; object-fit: cover;">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title text-center mb-3">Veamos los Clientes</h5>
                        <p class="card-text text-center">Aquí puedes revisar la información de tus clientes, ver sus pedidos recientes y actualizar su información de contacto.</p>
                        <a href="usuarios/clientes.php" class="btn btn-primary btn-sm d-block mx-auto">Ir a la página</a>
                    </div>
                </div>
            </div>

            <!-- Hablemos a los Proveedores -->
            <div class="col-md-4">
                <div class="card mb-4 shadow-sm">
                    <a href="proveedores/index.php">
                        <img src="../images/hablemos_proveedores.jpg" class="card-img-top img-fluid" alt="Hablemos a los Proveedores" style="height: 250px; object-fit: cover;">
                    </a>
                    <div class="card-body">
                        <h5 class="card-title text-center mb-3">Hablemos a los Proveedores</h5>
                        <p class="card-text text-center">Aquí puedes comunicarte con tus proveedores, realizar pedidos de reposición o discutir términos de contrato.</p>
                        <a href="proveedores/index.php" class="btn btn-primary btn-sm d-block mx-auto">Ir a la página</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>
