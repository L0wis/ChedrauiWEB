tengo este codigo: tengo este codigo: <?php
// Incluir archivo de configuración y encabezado
require '../config/database.php';
require '../config/config.php';

// Verificar si el ID de la transacción está presente en la URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    // Redirigir si no se proporciona un ID válido
    header('Location: index.php');
    exit;
}

// Obtener el ID de la transacción de la URL
$id_transaccion = $_GET['id'];

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

try {
    // Consulta SQL para obtener los detalles de la venta del personal
    $sql_personal = "SELECT compra_personal.*, clientes.nombres, clientes.apellidos FROM compra_personal INNER JOIN clientes ON compra_personal.id_cliente = clientes.id WHERE compra_personal.id_transaccion = :id_transaccion";

    // Preparar la consulta SQL
    $stmt_personal = $con->prepare($sql_personal);

    // Vincular el parámetro ID de transacción
    $stmt_personal->bindParam(':id_transaccion', $id_transaccion, PDO::PARAM_STR);

    // Ejecutar la consulta SQL
    $stmt_personal->execute();

    // Obtener los detalles de la venta del personal
    $venta_personal = $stmt_personal->fetch(PDO::FETCH_ASSOC);

    // Verificar si se encontró la venta del personal
    if ($venta_personal) {
        // Mostrar los detalles de la venta del personal
        require '../header.php'; // Incluir el encabezado después de la verificación exitosa
        ?>
        <main>
            <div class="container-fluid px-4">
                <h2 class="mt-3">Detalles de la Venta del Personal</h2>

                <!-- Mostrar los detalles de la venta del personal en una tabla -->
                <div class="row mt-4">
                    <div class="col-md-8">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>ID Transacción</th>
                                    <td><?php echo $venta_personal['id_transaccion']; ?></td>
                                </tr>
                                <tr>
                                    <th>Fecha</th>
                                    <td><?php echo $venta_personal['fecha']; ?></td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td><?php echo $venta_personal['status']; ?></td>
                                </tr>
                                <tr>
                                    <th>Email</th>
                                    <td><?php echo $venta_personal['email']; ?></td>
                                </tr>
                                <tr>
                                    <th>ID Cliente</th>
                                    <td><?php echo $venta_personal['id_cliente']; ?></td>
                                </tr>
                                <tr>
                                    <th>Nombre Cliente</th>
                                    <td><?php echo $venta_personal['nombres'] . ' ' . $venta_personal['apellidos']; ?></td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <td><?php echo $venta_personal['total']; ?></td>
                                </tr>
                                <!-- Agregar más detalles si es necesario -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
        <div class="container-fluid px-4">
            <div class="row mt-4">
                <div class="col-md-8">
                    <a href="generar_pdf_personal.php?id=<?php echo $id_transaccion; ?>" class="btn btn-primary">Imprimir
                        Comprobante</a>

                    <!-- Botón para desactivar venta -->
                    <form action="borrar_venta.php" method="post" style="display: inline;">
                        <input type="hidden" name="id_transaccion" value="<?php echo $id_transaccion; ?>">
                        <button type="submit" class="btn btn-danger">Desactivar Venta</button>
                    </form>
                </div>
            </div>
        </div>
        <?php
        require '../footer.php'; // Incluir el pie de página después de mostrar los detalles
        exit; // Salir después de mostrar los detalles
    }

    // Si no se encontró la venta del personal, continuar para verificar la venta del cliente
} catch (PDOException $e) {
    // Manejar el error
    echo "Error al ejecutar la consulta SQL para el personal: " . $e->getMessage();
    exit;
}

try {
    // Consulta SQL para obtener los detalles de la venta del cliente
    $sql_cliente = "SELECT compra.*, clientes.nombres, clientes.apellidos FROM compra INNER JOIN clientes ON compra.id = clientes.id WHERE compra.id_transaccion = :id_transaccion";

    // Preparar la consulta SQL
    $stmt_cliente = $con->prepare($sql_cliente);

    // Vincular el parámetro ID de transacción
    $stmt_cliente->bindParam(':id_transaccion', $id_transaccion, PDO::PARAM_STR);

    // Ejecutar la consulta SQL
    $stmt_cliente->execute();

    // Obtener los detalles de la venta del cliente
    $venta_cliente = $stmt_cliente->fetch(PDO::FETCH_ASSOC);

    // Verificar si se encontró la venta del cliente
    if (!$venta_cliente) {
        // Redirigir si no se encontró la venta del cliente
        header('Location: index.php');
        exit;
    }

    // Mostrar los detalles de la venta del cliente
    require '../header.php'; // Incluir el encabezado después de la verificación exitosa
    ?>
    <main>
        <div class="container-fluid px-4">
            <h2 class="mt-3">Detalles de la Venta del Cliente</h2>

            <!-- Mostrar los detalles de la venta del cliente en una tabla -->
            <div class="row mt-4">
                <div class="col-md-8">
                    <table class="table">
                        <tbody>
                            <tr>
                                <th>ID Transacción</th>
                                <td><?php echo $venta_cliente['id_transaccion']; ?></td>
                            </tr>
                            <tr>
                                <th>Fecha</th>
                                <td><?php echo $venta_cliente['fecha']; ?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td><?php echo $venta_cliente['status']; ?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><?php echo $venta_cliente['email']; ?></td>
                            </tr>
                            <tr>
                                <th>Nombre Cliente</th>
                                <td><?php echo $venta_cliente['nombres'] . ' ' . $venta_cliente['apellidos']; ?></td>
                            </tr>
                            <tr>
                                <th>Total</th>
                                <td><?php echo $venta_cliente['total']; ?></td>
                            </tr>
                            <!-- Agregar más detalles si es necesario -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <div class="container-fluid px-4">
        <div class="row mt-4">
            <div class="col-md-8">
                <a href="generar_pdf_cliente.php?id=<?php echo $id_transaccion; ?>" class="btn btn-primary">Imprimir
                    Comprobante</a>
                <!-- Botón para desactivar venta -->
                <form action="borrar_venta2.php" method="post">
                    <input type="hidden" name="id_transaccion" value="<?php echo $id_transaccion; ?>">
                    <button type="submit" class="btn btn-danger">Desactivar Venta</button>
                </form>
            </div>
        </div>
    </div>
    <?php
    require '../footer.php'; // Incluir el pie de página después de mostrar los detalles
} catch (PDOException $e) {
    // Manejar el error
    echo "Error al ejecutar la consulta SQL para el cliente: " . $e->getMessage();
    exit;
}
?>
   <main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Detalles de la Venta del Cliente</h2>

        <!-- Mostrar los detalles de la venta del cliente en una tabla -->
        <div class="row mt-4">
            <div class="col-md-8">
                <table class="table">
                    <tbody>
                        <tr>
                            <th>ID Transacción</th>
                            <td><?php echo $venta_cliente['id_transaccion']; ?></td>
                        </tr>
                        <tr>
                            <th>Fecha</th>
                            <td><?php echo $venta_cliente['fecha']; ?></td>
                        </tr>
                        <tr>
                            <th>Status</th>
                            <td><?php echo $venta_cliente['status']; ?></td>
                        </tr>
                        <tr>
                            <th>Email</th>
                            <td><?php echo $venta_cliente['email']; ?></td>
                        </tr>
                        <tr>
                            <th>Nombre Cliente</th>
                            <td><?php echo $venta_cliente['nombres'] . ' ' . $venta_cliente['apellidos']; ?></td>
                        </tr>
                        <tr>
                            <th>Total</th>
                            <td><?php echo $venta_cliente['total']; ?></td>
                        </tr>
                        <!-- Agregar más detalles si es necesario -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>
<div class="container-fluid px-4">
    <div class="row mt-4">
        <div class="col-md-8">
            <a href="generar_pdf_cliente.php?id=<?php echo $id_transaccion; ?>" class="btn btn-primary">Imprimir Comprobante</a>
            <!-- Botón para desactivar venta -->
            <form action="borrar_venta2.php" method="post">
                <input type="hidden" name="id_transaccion" value="<?php echo $id_transaccion; ?>">
                <button type="submit" class="btn btn-danger">Desactivar Venta</button>
            </form>
        </div>
    </div>
</div>
<?php
require '../footer.php'; // Incluir el pie de página después de mostrar los detalles
?>
