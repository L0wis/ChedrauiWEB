<?php
// Incluir archivo de configuración y encabezado
require '../config/database.php';
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
if (!isset($_SESSION['user_type'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SESSION['user_type'] != 'admin'){
    header('Location: ../../index.php');
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

// Consulta SQL para obtener los datos de ventas de la tabla "compra"
$sql_compra = "SELECT id, id_transaccion, fecha, status, email, id_cliente, total, 'cliente' AS realizo FROM compra WHERE activo = 1 ORDER BY fecha DESC";

// Consulta SQL para obtener los datos de ventas de la tabla "compra_personal"
$sql_compra_personal = "SELECT id, id_transaccion, fecha, status, email, id_cliente, total, 'personal' AS realizo FROM compra_personal WHERE activo = 1 AND status IN ('COMPLETED', 'APPROVED', 'DISABLED') ORDER BY fecha DESC";

// Obtener la opción seleccionada del filtro de ventas
$opcion = isset($_POST['opcion']) ? $_POST['opcion'] : 'todas';

// Obtener la opción seleccionada del filtro de tiempo
$tiempo = isset($_POST['tiempo']) ? $_POST['tiempo'] : 'todas';

// Consulta SQL predeterminada
$sql = '';

// Consulta SQL dinámica para obtener las ventas según la opción seleccionada
if ($opcion === 'todas') {
    // Combinar ambas consultas SQL
    $sql = "(SELECT id, id_transaccion, fecha, status, email, id_cliente, total, 'cliente' AS realizo FROM compra WHERE activo = 1) UNION (SELECT id, id_transaccion, fecha, status, email, id_cliente, total, 'personal' AS realizo FROM compra_personal WHERE activo = 1)";
} elseif ($opcion === 'clientes') {
    $sql = "SELECT id, id_transaccion, fecha, status, email, id_cliente, total, 'cliente' AS realizo FROM compra WHERE activo = 1";
} elseif ($opcion === 'personal') {
    $sql = "SELECT id, id_transaccion, fecha, status, email, id_cliente, total, 'personal' AS realizo FROM compra_personal WHERE activo = 1";
}

// Agregar la condición de tiempo si no es "todas"
if ($tiempo !== 'todas') {
    // Construir la condición de tiempo
    if ($tiempo === '12h') {
        // Restar 12 horas a la fecha actual
        $fecha_limite = date('Y-m-d H:i:s', strtotime('-12 hours'));
    } elseif ($tiempo === '24h') {
        // Restar 24 horas a la fecha actual
        $fecha_limite = date('Y-m-d H:i:s', strtotime('-24 hours'));
    } elseif ($tiempo === '1w') {
        // Restar 1 semana a la fecha actual
        $fecha_limite = date('Y-m-d H:i:s', strtotime('-1 week'));
    } elseif ($tiempo === '1m') {
        // Restar 1 mes a la fecha actual
        $fecha_limite = date('Y-m-d H:i:s', strtotime('-1 month'));
    } elseif ($tiempo === '4d') {
        // Restar 4 días a la fecha actual
        $fecha_limite = date('Y-m-d H:i:s', strtotime('-4 days'));
    } elseif ($tiempo === '6m') {
        // Restar 6 meses a la fecha actual
        $fecha_limite = date('Y-m-d H:i:s', strtotime('-6 months'));
    } elseif ($tiempo === '1y') {
        // Restar 1 año a la fecha actual
        $fecha_limite = date('Y-m-d H:i:s', strtotime('-1 year'));
    } else {
        // Si la opción es "cualquier tiempo", establecer una fecha muy antigua
        $fecha_limite = '1900-01-01 00:00:00';
    }

    // Agregar la condición de tiempo a cada subconsulta
    $sql = "SELECT * FROM ($sql) AS ventas WHERE fecha >= '$fecha_limite'";
}

try {
    // Preparar la consulta SQL según la opción seleccionada
    $stmt_ventas = $con->prepare($sql);

    // Ejecutar la consulta SQL
    $stmt_ventas->execute();

    // Obtener los resultados de la consulta
    $ventas = $stmt_ventas->fetchAll(PDO::FETCH_ASSOC);

        // Contar el número de resultados
        $num_resultados = count($ventas);

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

        <div class="mb-3">
            <form method="post">
                <label for="filtroVentas" class="form-label">Filtrar Ventas:</label>
                <select class="form-select" id="filtroVentas" name="opcion">
                    <option value="todas" <?php if ($opcion === 'todas') echo 'selected'; ?>>Mostrar Todas las Ventas</option>
                    <option value="clientes" <?php if ($opcion === 'clientes') echo 'selected'; ?>>Mostrar Ventas de Clientes</option>
                    <option value="personal" <?php if ($opcion === 'personal') echo 'selected'; ?>>Mostrar Ventas del Personal</option>
                </select>
                
                <label for="filtroTiempo" class="form-label">Filtrar por Tiempo:</label>
                <select class="form-select" id="filtroTiempo" name="tiempo">
                <option value="cualquier" <?php if ($tiempo === 'cualquier') echo 'selected'; ?>>Cualquier Tiempo</option>
                    <option value="12h" <?php if ($tiempo === '12h') echo 'selected'; ?>>Últimas 12 Horas</option>
                    <option value="24h" <?php if ($tiempo === '24h') echo 'selected'; ?>>Últimas 24 Horas</option>
                    <option value="4d" <?php if ($tiempo === '4d') echo 'selected'; ?>>Últimos 4 Días</option>
                    <option value="1w" <?php if ($tiempo === '1w') echo 'selected'; ?>>Última Semana</option>
                    <option value="1m" <?php if ($tiempo === '1m') echo 'selected'; ?>>Último Mes</option>
                    <option value="6m" <?php if ($tiempo === '6m') echo 'selected'; ?>>Últimos 6 Meses</option>
                    <option value="1y" <?php if ($tiempo === '1y') echo 'selected'; ?>>Último Año</option>
                </select>
                
                <button type="submit" class="btn btn-primary mt-2">Filtrar</button>
            </form>
        </div>

        <div class="mb-3">
            <p>Resultados encontrados: <?php echo $num_resultados; ?></p>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">ID Transacción</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Status</th>
                        <th scope="col">Email</th>
                        <th scope="col">ID Cliente</th>
                        <th scope="col">Total</th>
                        <th scope="col">Realizó</th>
                        <!-- Añadir más columnas según necesites -->
                        <th scope="col">Detalles</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ventas as $venta) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($venta['id_transaccion'], ENT_QUOTES); ?></td>
                            <td><?php echo htmlspecialchars($venta['fecha'], ENT_QUOTES); ?></td>
                            <td><?php echo htmlspecialchars($venta['status'], ENT_QUOTES); ?></td>
                            <td><?php echo htmlspecialchars($venta['email'], ENT_QUOTES); ?></td>
                            <td><?php echo htmlspecialchars($venta['id_cliente'], ENT_QUOTES); ?></td>
                            <td><?php echo htmlspecialchars($venta['total'], ENT_QUOTES); ?></td>
                            <td><?php echo htmlspecialchars($venta['realizo'], ENT_QUOTES); ?></td>
                            <!-- Agrega más celdas según sea necesario -->
                            <td>
                                <a href="compra_detalle.php?id=<?php echo $venta['id_transaccion'] ?>" class="btn btn-warning btn-sm"><i
                                        class="bi bi-info-circle"></i></a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php
        if (empty($ventas)) {
            // Si no hay ventas, mostrar un mensaje
            echo "<div class='text-center'><p>Sin resultados.</p></div>";
        }
        ?>
    </div>
</main>

<?php
} catch (PDOException $e) {
    // Manejar el error
    echo "Error al ejecutar la consulta SQL: " . $e->getMessage();
}

// Incluir el archivo de pie de página
require '../footer.php';
?>
