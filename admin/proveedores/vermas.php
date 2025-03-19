<?php

ob_start();

require '../config/database.php';
require '../config/config.php';
require '../header.php';

if (!isset($_SESSION['user_type'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SESSION['user_type'] != 'admin') {
    header('Location: ../../index.php');
    exit;
}

// Obtener el ID del proveedor
if (isset($_GET['id'])) {
    $proveedor_id = $_GET['id'];

    $db = new Database();
    $con = $db->conectar();

    // Consulta SQL para obtener las transacciones del proveedor
    $sql = "SELECT * FROM transaccion_prov WHERE nombre_proveedor = :nombre_proveedor";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':nombre_proveedor', $proveedor_id);
    $stmt->execute();
    $transacciones = $stmt->fetchAll(PDO::FETCH_ASSOC);



    // Consulta para obtener los datos del proveedor por su ID
    $sql = "SELECT * FROM proveedores WHERE id = :id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':id', $proveedor_id);
    $stmt->execute();
    $proveedor = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verificar si el proveedor existe
    if (!$proveedor) {
        // Si el proveedor no existe, redirigir a la página de lista de proveedores
        header('Location: index.php');
        exit;
    }

    // Definir una lista de extensiones permitidas
    $extensiones_permitidas = ['jpg', 'jpeg', 'png', 'gif'];

    // Iterar sobre las extensiones permitidas y verificar si alguna de ellas existe
    foreach ($extensiones_permitidas as $extension) {
        $rutaImagenProveedor = '../../images/proveedores/' . $proveedor_id . '/proveedor.' . $extension;
        if (file_exists($rutaImagenProveedor)) {
            // Si se encuentra una imagen con una de las extensiones permitidas, se establece la ruta y se rompe el bucle
            break;
        }
    }

    // Verificar si la imagen del proveedor existe, de lo contrario, utilizar una imagen predeterminada
    if (!file_exists($rutaImagenProveedor)) {
        $rutaImagenProveedor = '../../images/no-photo.jpeg';
    }    // Obtener el ID del proveedor anterior
    $sql_anterior = "SELECT id FROM proveedores WHERE id < :id ORDER BY id DESC LIMIT 1";
    $stmt_anterior = $con->prepare($sql_anterior);
    $stmt_anterior->bindParam(':id', $proveedor_id);
    $stmt_anterior->execute();
    $proveedor_anterior = $stmt_anterior->fetch(PDO::FETCH_ASSOC);

    // Obtener el ID del proveedor siguiente
// Obtener el ID del proveedor siguiente activo
    $sql_siguiente = "SELECT id FROM proveedores WHERE id > :id AND activo = 1 ORDER BY id ASC LIMIT 1";
    $stmt_siguiente = $con->prepare($sql_siguiente);
    $stmt_siguiente->bindParam(':id', $proveedor_id);
    $stmt_siguiente->execute();
    $proveedor_siguiente = $stmt_siguiente->fetch(PDO::FETCH_ASSOC);

} else {
    // Si no se proporciona un ID de proveedor, redirigir a la página de lista de proveedores
    header('Location: index.php');
    exit;
}
?>


<main>
    <div class="container-fluid px-4">
        <!-- Botones para mostrar el modal de reporte de suministros y para añadir entrega -->
        <div class="row mt-3">
            <div class="col-md-6">
                <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#reporteModal"><i
                        class="bi bi-file-earmark-bar-graph"></i> Reporte de Suministros</button>
                <a href="agregar_entrega.php?id=<?php echo $proveedor['id']; ?>" class="btn btn-success"><i
                        class="bi bi-plus-circle"></i> Añadir Entrega</a>
            </div>
            <div class="col-md-6">
                <a href="edita.php?id=<?php echo $proveedor['id']; ?>" class="btn btn-warning"><i
                        class="bi bi-pencil-fill"></i> Editar</a>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productosModal"><i
                        class="bi bi-box-arrow-up-right"></i> Productos Suministrados</button>
            </div>
        </div>


        <div class="container-fluid px-4">
            <h2 class="mt-3">Proveedor: <?php echo htmlspecialchars($proveedor['nombre'], ENT_QUOTES); ?></h2>

            <div class="row">
                <div class="col-md-6">
                    <!-- Contenedor de la imagen y los botones -->
                    <div class="d-flex flex-column align-items-start">
                        <!-- Mostrar la imagen del proveedor -->
                        <img src="<?php echo $rutaImagenProveedor . '?t=' . time(); ?>" alt="Logo del proveedor"
                            style="max-width: 80%;">
                    </div>
                </div>
                <div class="col-md-6">
                    <!-- Mostrar la información del proveedor -->
                    <h4>Contacto:</h4>
                    <p><?php echo htmlspecialchars($proveedor['nombre_contacto'], ENT_QUOTES); ?></p>
                    <h4>Dirección:</h4>
                    <p><?php echo htmlspecialchars($proveedor['direccion'], ENT_QUOTES); ?></p>
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Ciudad:</h4>
                            <p><?php echo htmlspecialchars($proveedor['ciudad'], ENT_QUOTES); ?></p>
                        </div>
                        <div class="col-md-6">
                            <h4>Teléfono:</h4>
                            <p><?php echo htmlspecialchars($proveedor['telefono'], ENT_QUOTES); ?></p>
                        </div>
                    </div>
                    <h4>Contrato:</h4>
                    <p><?php echo date('d-m-Y', strtotime($proveedor['inicio_contrato'])); ?> a
                        <?php echo date('d-m-Y', strtotime($proveedor['fin_contrato'])); ?></p>

                    <h4>Tiempo de Suministro:</h4>
                    <p>
                        <?php
                        // Mostrar el tiempo de suministro según la opción seleccionada
                        switch ($proveedor['tiempo_suministro']) {
                            case 'Opción 1':
                                echo 'Cada Lunes';
                                break;
                            case 'Opción 2':
                                echo 'Cada Viernes';
                                break;
                            case 'Opción 3':
                                echo 'Cada Domingo';
                                break;
                            case 'personalizado':
                                if (isset($proveedor['tiempo_suministro_personalizado'])) {
                                    echo 'Personalizado: ' . htmlspecialchars($proveedor['tiempo_suministro_personalizado'], ENT_QUOTES);
                                } else {
                                    echo 'Personalizado'; // Otra opción si no se ha definido personalizado
                                }
                                break;
                            default:
                                echo htmlspecialchars($proveedor['tiempo_suministro'], ENT_QUOTES);
                                break;
                        }
                        ?>



                    <h4>Cantidad de Suministro(Por Contrato):</h4>
                    <p><?php echo htmlspecialchars($proveedor['cantidad_suministro'], ENT_QUOTES); ?></p>

                    <!-- Botones de proveedor anterior y siguiente -->
                    <div class="mt-3">
                        <?php if ($proveedor_anterior): ?>
                            <a href="vermas.php?id=<?php echo $proveedor_anterior['id']; ?>"
                                class="btn btn-secondary me-3"><i class="bi bi-arrow-left"></i> Proveedor Anterior</a>
                        <?php endif; ?>
                        <?php if ($proveedor_siguiente): ?>
                            <a href="vermas.php?id=<?php echo $proveedor_siguiente['id']; ?>" class="btn btn-secondary"><i
                                    class="bi bi-arrow-right"></i> Proveedor Siguiente</a>
                        <?php endif; ?>


                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-md-6">
                        <!-- <a href="edita.php?id=<?php echo $proveedor['id']; ?>" class="btn btn-warning"><i class="bi bi-pencil-fill"></i> Editar</a> --->
                    </div>
                    <div class="col-md-6">
                        <!--   <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#productosModal"><i class="bi bi-box-arrow-up-right"></i> Productos Suministrados</button> --->
                        <!-- Modal de productos suministrados -->
<div class="modal fade" id="productosModal" tabindex="-1" aria-labelledby="productosModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productosModalLabel">Productos Suministrados</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nombre del Producto</th>
                            <th>Precio</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Obtener el ID del proveedor desde la URL
                        if (isset($_GET['id'])) {
                            $proveedor_id = $_GET['id'];

                            // Conectar a la base de datos
                            $db = new Database();
                            $con = $db->conectar();

                            // Consulta para buscar productos donde id_proveedor contenga el ID del proveedor (separado por comas)
                            $sql = "SELECT * FROM productos WHERE id_proveedor LIKE :id LIMIT 10";
                            $stmt = $con->prepare($sql);

                            // Usar comodines para buscar el ID en cualquier posición
                            $stmt->bindValue(':id', "%{$proveedor_id}%", PDO::PARAM_STR);
                            $stmt->execute();

                            // Obtener los resultados
                            $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);

                            // Verificar si se encontraron productos
                            if ($productos) {
                                foreach ($productos as $producto) {
                                    echo '<tr>';
                                    echo '<td>' . htmlspecialchars($producto['nombre'], ENT_QUOTES) . '</td>';
                                    echo '<td>' . htmlspecialchars($producto['precio'], ENT_QUOTES) . '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="4">No se encontraron productos suministrados para este proveedor.</td></tr>';
                            }
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<!-- Botón para mostrar el modal de reporte de suministros -->
                        <!--   <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#reporteModal"><i class="bi bi-file-earmark-bar-graph"></i> Reporte de Suministros</button> -->

                        <!-- Botón para redirigir a la página para añadir entregas -->
                        <!----   <a href="agregar_entrega.php?id=<?php echo $proveedor['id']; ?>" class="btn btn-success"><i class="bi bi-plus-circle"></i> Añadir Entrega</a> -->
                    </div>
                </div>
            </div>
</main>

<?php

// Define una función para obtener la etiqueta del estado
function getStatusLabel($status) {
    switch ($status) {
        case 0:
            return "Pendiente";
        case 1:
            return "Aprobada";
        case 2:
            return "Cancelada";
        default:
            return "Desconocido";
    }
}

?>

<!-- Modal de reporte de suministros -->
<div class="modal fade" id="reporteModal" tabindex="-1" aria-labelledby="reporteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reporteModalLabel">Reporte de Suministros</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Aquí se muestra el contenido del reporte de suministros -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha de Entrega</th>
                            <th>Nombre del Proveedor</th>
                            <th>Personal</th>
                            <th>Lista de Productos</th>
                            <th>Cantidad</th>
                            <th>Número de Orden</th>
                            <th>Condición</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Consulta SQL para obtener las transacciones del proveedor por su nombre
                        $sql = "SELECT * FROM transaccion_prov WHERE nombre_proveedor = :nombre_proveedor";
                        $stmt = $con->prepare($sql);
                        $stmt->bindParam(':nombre_proveedor', $proveedor['nombre']);
                        $stmt->execute();
                        $transacciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

                        // Si hay transacciones, mostrarlas en la tabla
                        if ($transacciones) {
                            foreach ($transacciones as $transaccion) {
                                echo "<tr>";
                                // Formatear la fecha de entrega
                                $fecha_entrega = new DateTime($transaccion['fecha_entrega']);
                                echo "<td>" . $fecha_entrega->format('Y-m-d') . "</td>";
                                echo "<td>" . htmlspecialchars($transaccion['nombre_proveedor']) . "</td>";
                                echo "<td>" . htmlspecialchars($transaccion['personal']) . "</td>";
                                // Agrega un botón para ver los productos de esta transacción
                                echo "<td><a href='obtener_productos.php?id=" . $transaccion['id'] . "' class='btn btn-primary'>Ver Productos</a></td>";
                                echo "<td>" . htmlspecialchars($transaccion['cantidad']) . "</td>";
                                echo "<td>" . htmlspecialchars($transaccion['numero_orden']) . "</td>";
                                echo "<td>" . htmlspecialchars($transaccion['condicion']) . "</td>";
                                echo "<td>" . getStatusLabel($transaccion['status']) . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            // Si no hay transacciones para este proveedor, mostrar un mensaje
                            echo "<tr><td colspan='8'>No hay transacciones disponibles para este proveedor.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<?php require '../footer.php'; ?>


<script>
    // Cuando el modal se muestre
    $('#productosModal').on('show.bs.modal', function (event) {
        var modal = $(this);
        var modalBody = modal.find('.modal-body');

        // Realiza una petición AJAX para obtener los productos del proveedor
        $.ajax({
            url: 'obtener_productos.php',
            type: 'GET',
            data: { proveedor_id: <?php echo $proveedor['id']; ?> },
            dataType: 'html',
            success: function (data) {
                // Agrega los productos al cuerpo del modal
                modalBody.html(data);
            }
        });
    });
</script>