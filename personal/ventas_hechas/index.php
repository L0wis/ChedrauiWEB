<?php
// Incluir archivo de configuración y encabezado
require '../config/config.php';
require '../header.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

// Obtener el ID del personal
$id_personal = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

// Definir los filtros por defecto
$filtro_id = '';
$filtro_fecha = '';
$filtro_status = '';
$filtro_total = '';
$filtro_tipo_pago = '';
$filtro_activo = '';

// Verificar si se enviaron los filtros
if (isset($_GET['filtro_id'])) {
    $filtro_id = $_GET['filtro_id'];
}
if (isset($_GET['filtro_fecha'])) {
    $filtro_fecha = $_GET['filtro_fecha'];
}
if (isset($_GET['filtro_status'])) {
    $filtro_status = $_GET['filtro_status'];
}
if (isset($_GET['filtro_total'])) {
    $filtro_total = $_GET['filtro_total'];
}
if (isset($_GET['filtro_tipo_pago'])) {
    $filtro_tipo_pago = $_GET['filtro_tipo_pago'];
}
if (isset($_GET['filtro_activo'])) {
    $filtro_activo = $_GET['filtro_activo'];
}

// Obtener todas las ventas del personal de la tabla compra_personal según el filtro seleccionado
try {
    // Crear una instancia de la clase Database
    $db = new Database();

    // Obtener la conexión a la base de datos
    $con = $db->conectar();

    // Construir la consulta SQL base
    $sql_ventas = "SELECT * FROM compra_personal WHERE id_personal = ?";

    // Arreglo para almacenar las condiciones de filtrado
    $conditions = array();

    // Verificar qué filtro se está aplicando y agregar la condición correspondiente a la consulta SQL
    if ($filtro_id !== '') {
        if ($filtro_id === 'mayor') {
            $conditions[] = "id IN (SELECT MAX(id) FROM compra_personal GROUP BY id_personal)";
        } elseif ($filtro_id === 'menor') {
            $conditions[] = "id IN (SELECT MIN(id) FROM compra_personal GROUP BY id_personal)";
        }
    }

    if ($filtro_fecha !== '') {
        if ($filtro_fecha === '12h') {
            // Obtener la fecha hace 12 horas
            $fecha_12h = date('Y-m-d H:i:s', strtotime('-12 hours'));
            $conditions[] = "fecha >= '{$fecha_12h}'";
        } elseif ($filtro_fecha === '24h') {
            // Obtener la fecha hace 24 horas
            $fecha_24h = date('Y-m-d H:i:s', strtotime('-24 hours'));
            $conditions[] = "fecha >= '{$fecha_24h}'";
        } elseif ($filtro_fecha === '3d') {
            // Obtener la fecha hace 3 días
            $fecha_3d = date('Y-m-d H:i:s', strtotime('-3 days'));
            $conditions[] = "fecha >= '{$fecha_3d}'";
        } elseif ($filtro_fecha === 'semana') {
            // Obtener la fecha hace 1 semana
            $fecha_semana = date('Y-m-d H:i:s', strtotime('-1 week'));
            $conditions[] = "fecha >= '{$fecha_semana}'";
        } elseif ($filtro_fecha === 'mes') {
            // Obtener la fecha hace 1 mes
            $fecha_mes = date('Y-m-d H:i:s', strtotime('-1 month'));
            $conditions[] = "fecha >= '{$fecha_mes}'";
        }

        if ($filtro_status !== '') {
            $conditions[] = "status = '{$filtro_status}'";
        }

        if ($filtro_tipo_pago !== '') {
            $conditions[] = "tipo_pago = '{$filtro_tipo_pago}'";
        }

        if ($filtro_activo !== '') {
            $conditions[] = "activo = '{$filtro_activo}'";
        }

        if ($filtro_total !== '') {
            if ($filtro_total === 'mayor') {
                $sql_ventas .= " ORDER BY total DESC";
            } elseif ($filtro_total === 'menor') {
                $sql_ventas .= " ORDER BY total ASC";
            }
        }

    }


    // Si hay condiciones, agregarlas a la consulta SQL
    if (!empty($conditions)) {
        $sql_ventas .= " AND " . implode(" AND ", $conditions);

        // Resto del código permanece igual
    } elseif ($filtro_status !== '') {
        $sql_ventas .= " AND status = '{$filtro_status}'";
    } elseif ($filtro_total !== '') {
        if ($filtro_total === 'mayor') {
            $sql_ventas .= " ORDER BY total DESC";
        } elseif ($filtro_total === 'menor') {
            $sql_ventas .= " ORDER BY total ASC";
        }
    } elseif ($filtro_tipo_pago !== '') {
        $sql_ventas .= " AND tipo_pago = '{$filtro_tipo_pago}'";
    } elseif ($filtro_activo !== '') {
        $sql_ventas .= " AND activo = '{$filtro_activo}'";
    }

    // Preparar la consulta
    $stmt_ventas = $con->prepare($sql_ventas);

    // Ejecutar la consulta
    $stmt_ventas->execute([$id_personal]);

    // Obtener los resultados de la consulta
    $ventas = $stmt_ventas->fetchAll(PDO::FETCH_ASSOC);

    // Cerrar la conexión
    $con = null;
} catch (PDOException $e) {
    // Manejar errores de base de datos
    echo "Error al obtener las ventas: " . $e->getMessage();
}

// Contenido del index de ventas
?>


<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Lista de Ventas</h2>
        <p>Total de Ventas Encontradas: <?php echo count($ventas); ?></p>

        <!-- Botones de Reporte PDF y Excel -->
        <div class="row mb-3">
            <div class="col">
                <a href="reporte_pdf.php" class="btn btn-primary">
                    <i class="bi bi-file-pdf"></i> Reporte PDF
                </a>
                <a href="reporte_excel.php" class="btn btn-success">
                    <i class="bi bi-file-excel"></i> Reporte Excel
                </a>
            </div>
            <!-- Formulario de filtros -->
            <form action="" method="GET">
                <div class="row">
                    <div class="col">
                        <label for="filtro_id">Filtrar por ID:</label>
                        <select class="form-select" name="filtro_id" id="filtro_id">
                            <option value="">Seleccionar</option>
                            <option value="mayor" <?php echo ($filtro_id === 'mayor') ? 'selected' : ''; ?>>ID Mayor
                            </option>
                            <option value="menor" <?php echo ($filtro_id === 'menor') ? 'selected' : ''; ?>>ID Menor
                            </option>
                        </select>
                    </div>
                    <div class="col">
                        <label for="filtro_fecha">Por Fecha:</label>
                        <select class="form-select" name="filtro_fecha" id="filtro_fecha">
                            <option value="">Seleccionar</option>
                            <option value="12h" <?php echo ($filtro_fecha === '12h') ? 'selected' : ''; ?>>Últimas 12
                                Horas</option>
                            <option value="24h" <?php echo ($filtro_fecha === '24h') ? 'selected' : ''; ?>>Últimas 24
                                Horas</option>
                            <option value="3d" <?php echo ($filtro_fecha === '3d') ? 'selected' : ''; ?>>Últimos 3 Días
                            </option>
                            <option value="semana" <?php echo ($filtro_fecha === 'semana') ? 'selected' : ''; ?>>Última
                                Semana</option>
                            <option value="mes" <?php echo ($filtro_fecha === 'mes') ? 'selected' : ''; ?>>Último Mes
                            </option>
                        </select>
                    </div>
                    <div class="col">
                        <label for="filtro_status">Por Status:</label>
                        <select class="form-select" name="filtro_status" id="filtro_status">
                            <option value="">Seleccionar</option>
                            <option value="disabled" <?php echo ($filtro_status === 'disabled') ? 'selected' : ''; ?>>
                                Disabled</option>
                            <option value="completed" <?php echo ($filtro_status === 'completed') ? 'selected' : ''; ?>>
                                Completed</option>
                            <option value="approved" <?php echo ($filtro_status === 'approved') ? 'selected' : ''; ?>>
                                Approved</option>
                        </select>
                    </div>
                    <div class="col">
                        <label for="filtro_total">Por Total:</label>
                        <select class="form-select" name="filtro_total" id="filtro_total">
                            <option value="">Seleccionar</option>
                            <option value="mayor" <?php echo ($filtro_total === 'mayor') ? 'selected' : ''; ?>>Mayor
                                Cantidad</option>
                            <option value="menor" <?php echo ($filtro_total === 'menor') ? 'selected' : ''; ?>>Menor
                                Cantidad</option>
                        </select>
                    </div>
                    <div class="col">
                        <label for="filtro_tipo_pago">Por Tipo de Pago:</label>
                        <select class="form-select" name="filtro_tipo_pago" id="filtro_tipo_pago">
                            <option value="">Seleccionar</option>
                            <option value="paypal" <?php echo ($filtro_tipo_pago === 'paypal') ? 'selected' : ''; ?>>
                                Paypal</option>
                            <option value="efectivo" <?php echo ($filtro_tipo_pago === 'efectivo') ? 'selected' : ''; ?>>
                                Efectivo</option>
                        </select>
                    </div>
                    <div class="col">
                        <label for="filtro_activo">Por Activo:</label>
                        <select class="form-select" name="filtro_activo" id="filtro_activo">
                            <option value="">Seleccionar</option>
                            <option value="1" <?php echo ($filtro_activo === '1') ? 'selected' : ''; ?>>Activo (1)
                            </option>
                            <option value="0" <?php echo ($filtro_activo === '0') ? 'selected' : ''; ?>>No Activo (0)
                            </option>
                        </select>
                    </div>
                    <div class="col-auto align-self-end">
                        <button type="submit" class="btn btn-primary">Aplicar Filtro</button>
                    </div>
                </div>
            </form>

            <!-- Tabla de Ventas -->
            <div class="table-responsive mt-3">
                <table class="table table-hover">
                    <!-- Encabezados de Columnas -->
                    <thead>
                        <tr>
                            <th scope="col">ID</th>
                            <th scope="col">ID Transacción</th>
                            <th scope="col">Fecha</th>
                            <th scope="col">Status</th>
                            <th scope="col">Email</th>
                            <th scope="col">ID Cliente</th>
                            <th scope="col">Dirección</th>
                            <th scope="col">Referencias</th>
                            <th scope="col">Total</th>
                            <th scope="col">Tipo de Pago</th>
                            <th scope="col">Activo</th>
                            <th scope="col">Accion</th>
                        </tr>
                    </thead>
                    <!-- Cuerpo de la Tabla -->
                    <tbody>
                        <?php foreach ($ventas as $venta) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($venta['id'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($venta['id_transaccion'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($venta['fecha'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($venta['status'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($venta['email'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($venta['id_cliente'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($venta['direccion'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($venta['referencias'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($venta['total'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($venta['tipo_pago'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($venta['activo'], ENT_QUOTES); ?></td>
                                <td>
                                    <a href="vermas.php?id=<?php echo $venta['id'] ?>" class="btn btn-warning btn-sm"><i
                                            class="bi bi-info-circle"></i> Detalle</a>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
</main>

<?php require '../footer.php'; ?>