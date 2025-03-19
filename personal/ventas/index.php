<?php
// Incluir archivo de configuración y encabezado
require '../config/config.php';
require '../header.php';
?>

<!-- Incluir las bibliotecas de Bootstrap y jQuery -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ygbV9kiqUc6oa4msXn9868p6vTuh5yoIUkNy4AyopA+8MnyeKqzsu2KTKg7iwwr6"
    crossorigin="anonymous"></script>

<?php
// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

// Crear una instancia de la clase Database
$db = new Database();

// Obtener la conexión a la base de datos
$con = $db->conectar();

// Verificar si la conexión se estableció correctamente
if (!$con) {
    // Manejar el error de conexión
    echo "Error de conexión a la base de datos.";
    exit;
}

// Obtener el ID del personal
$personal_id = $_SESSION['user_id'];

// Realizar la consulta SQL para obtener los datos de ventas
$sql = "SELECT id, id_transaccion, status, email, id_cliente, fecha, total 
        FROM compra_personal 
        WHERE id_personal = :personal_id AND activo = 1
        ORDER BY fecha DESC 
        LIMIT 20";
try {
    // Preparar la consulta SQL
    $stmt = $con->prepare($sql);

    // Vincular parámetro personal_id
    $stmt->bindParam(':personal_id', $personal_id, PDO::PARAM_INT);

    // Ejecutar la consulta SQL
    $stmt->execute();

    // Verificar si hay resultados
    if ($stmt->rowCount() > 0) {
        // Obtener los datos de ventas y mostrarlos en la tabla
        ?>

        <main>
            <div class="container-fluid px-4">
                <h2 class="mt-3">Lista de Ventas Recientes</h2>

                <!-- Botones para acciones adicionales -->
                <div class="mb-3">
                    <a href="crear_venta.php" class="btn btn-primary"><i class="bi bi-cart-plus-fill"></i> Crear Nueva Venta</a>
                    <a href="reporte_ventas.php" class="btn btn-success mx-2"><i class="bi bi-file-pdf-fill"></i> Reporte
                        PDF</a>
                    <a href="reporte_ventas_Excel" class="btn btn-info"><i class="bi bi-file-excel-fill"></i> Reporte Excel</a>
                    <a href="desactivar_venta.php" class="btn btn-danger"><i class="bi bi-trash-fill"></i> Ventas borradas</a>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">ID Transacción</th>
                                <th scope="col">Status</th>
                                <th scope="col">Email</th>
                                <th scope="col">ID Cliente</th>
                                <th scope="col">Fecha</th>
                                <th scope="col">Total</th>
                                <!-- Añadir más columnas según necesites -->
                                <th scope="col">Detalles</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($venta = $stmt->fetch(PDO::FETCH_ASSOC)) { ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($venta['id'], ENT_QUOTES); ?></td>
                                    <td><?php echo htmlspecialchars($venta['id_transaccion'], ENT_QUOTES); ?></td>
                                    <td><?php echo htmlspecialchars($venta['status'], ENT_QUOTES); ?></td>
                                    <td><?php echo htmlspecialchars($venta['email'], ENT_QUOTES); ?></td>
                                    <td><?php echo htmlspecialchars($venta['id_cliente'], ENT_QUOTES); ?></td>
                                    <td><?php echo htmlspecialchars($venta['fecha'], ENT_QUOTES); ?></td>
                                    <td><?php echo htmlspecialchars($venta['total'], ENT_QUOTES); ?></td>
                                    <!-- Agrega más celdas según sea necesario -->
                                    <td>
                                        <a href="compra_detalle.php?id=<?php echo $venta['id'] ?>" class="btn btn-warning btn-sm"><i
                                                class="bi bi-info-circle"></i></a>
                                        <!-- Botón para abrir el modal de confirmación de borrado -->
                                        <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#borrarVentaModal<?php echo $venta['id']; ?>"><i
                                                class="bi bi-trash-fill"></i></button>
                                    </td>
                                </tr>

                                <!-- Modal de confirmación de borrado -->
                                <div class="modal fade" id="borrarVentaModal<?php echo $venta['id']; ?>" tabindex="-1" role="dialog"
                                    aria-labelledby="borrarVentaModalLabel<?php echo $venta['id']; ?>" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="borrarVentaModalLabel<?php echo $venta['id']; ?>">
                                                    Confirmar Eliminación</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                    aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                ¿Estás seguro de que deseas eliminar esta venta?
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Cancelar</button>
                                                <!-- Enlace para eliminar la venta -->
                                                <a href="eliminar_venta.php?id=<?php echo $venta['id']; ?>"
                                                    class="btn btn-danger">Eliminar</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>

        <?php
    } else {
        // Si no hay ventas, mostrar un mensaje
        echo "<p>No se encontraron ventas asociadas al ID de personal.</p>";
    }
} catch (PDOException $e) {
    // Manejar el error
    echo "Error al ejecutar la consulta SQL: " . $e->getMessage();
}

// Incluir el archivo de pie de página
require '../footer.php';
?>