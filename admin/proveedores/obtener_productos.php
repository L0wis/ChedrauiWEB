<?php
ob_start();

error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING);

function main()
{
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
    // Obtener el ID del proveedor desde la URL
    $proveedor_id = $_GET['id'];

    $db = new Database();
    $con = $db->conectar();

    $sql = "SELECT tp.id, tp.fecha_entrega, tp.nombre_proveedor, tp.personal, tp.lista_productos, tp.cantidad, tp.numero_orden, tp.condicion, tp.status, p.id as proveedor_id
            FROM transaccion_prov tp
            INNER JOIN proveedores p ON tp.nombre_proveedor = p.nombre
            WHERE tp.id = :proveedor_id";

    $stmt = $con->prepare($sql);
    $stmt->bindParam(':proveedor_id', $proveedor_id);
    $stmt->execute();
    $entregas = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <main>
        <div class="container-fluid px-4">
            <div class="container mt-3">
                <a href="vermas.php?id=<?php echo $entregas[0]['proveedor_id']; ?>" class="btn btn-primary"><i
                        class="bi bi-arrow-left"></i> Atrás</a>
            </div>

            <h2 class="mt-3">Entregas de Proveedores</h2>

            <!-- Botones de acción -->
            <div class="my-3">
                <?php if ($entregas[0]['status'] == 0): ?>
                    <!-- Si el status es Pendiente, mostrar los tres botones -->
                    <a href="editar_entrega.php?id=<?php echo $proveedor_id; ?>" class="btn btn-warning"><i
                            class="bi bi-pencil-square"></i> Editar Entrega</a>
                    <a href="aprobar_entrega.php?id=<?php echo $proveedor_id; ?>" class="btn btn-success"><i
                            class="bi bi-check-square"></i> Aprobar Entrega</a>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#confirmarCancelarModal"><i class="bi-x"></i> Cancelar Entrega</button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#modalEliminarEntrega"><i class="bi bi-trash"></i> Eliminar Entrega</button>
                <?php elseif ($entregas[0]['status'] == 1): ?>
                    <!-- Si el status es Aprobada, ocultar los botones de editar y aprobar -->
                    <!-- Aquí solo mostrarías el botón de eliminar -->
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal"
                        data-bs-target="#modalEliminarEntrega"><i class="bi bi-trash"></i> Eliminar</button>
                <?php elseif ($entregas[0]['status'] == 2): ?>
                    <!-- Si el status es Cancelada, ocultar todos los botones -->
                <?php endif; ?>
            </div>

            <div class="row">
                <div class="col">
                    <p><strong>Fecha de Entrega: </strong><?php echo $entregas[0]['fecha_entrega']; ?></p>
                    <p><strong>Nombre del Proveedor: </strong><?php echo $entregas[0]['nombre_proveedor']; ?></p>
                    <p><strong>Personal: </strong><?php echo $entregas[0]['personal']; ?></p>
                    <p><strong>Cantidad Total de Productos: </strong><?php echo $entregas[0]['cantidad']; ?></p>
                    <p><strong>Número de Orden: </strong><?php echo $entregas[0]['numero_orden']; ?></p>
                    <p><strong>Condición: </strong><?php echo $entregas[0]['condicion']; ?></p>
                    <p><strong>Status: </strong><?php echo getStatusLabel($entregas[0]['status']); ?></p>
                </div>
                <?php
                $id_compra = isset($_GET['id']) ? intval($_GET['id']) : 0;

                // Consulta de productos con cantidades para un ID específico
                $query = "SELECT lista_productos, cantidad_producto1, cantidad_producto2, cantidad_producto3, cantidad_producto4, cantidad_producto5, cantidad_producto6, cantidad_producto7, cantidad_producto8, cantidad_producto9, cantidad_producto10
          FROM transaccion_prov
          WHERE id = :id";
                $stmt = $con->prepare($query);
                $stmt->bindParam(':id', $id_compra, PDO::PARAM_INT);
                $stmt->execute();
                $entregas = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!$entregas) {
                    echo "<h3>No se encontraron datos para la compra con ID $id_compra</h3>";
                    exit;
                }

                $totalCompra = 0;
                ?>
                <div class="col">
                    <h3>Productos:</h3>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio Unitario</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($entregas as $entrega) {
                                $productos = explode(',', $entrega['lista_productos']); // Separar los productos por coma
                        
                                foreach ($productos as $index => $producto) {
                                    $producto = trim($producto); // Eliminar espacios
                        
                                    // Obtener la cantidad del producto desde la columna correspondiente
                                    $columnaCantidad = "cantidad_producto" . ($index + 1);
                                    $cantidad = isset($entrega[$columnaCantidad]) ? intval($entrega[$columnaCantidad]) : 0;

                                    // Consultar precio del producto
                                    $query = "SELECT precio FROM productos WHERE nombre = :nombre_producto";
                                    $stmtProducto = $con->prepare($query);
                                    $stmtProducto->bindParam(':nombre_producto', $producto);
                                    $stmtProducto->execute();
                                    $resultProducto = $stmtProducto->fetch(PDO::FETCH_ASSOC);

                                    $precio = $resultProducto ? floatval($resultProducto['precio']) : 0;
                                    $subtotal = $precio * $cantidad; // Calcular subtotal
                                    $totalCompra += $subtotal; // Sumar al total de la compra
                                    ?>
                                    <tr>
                                        <td style="word-break: break-word; max-width: 200px;">
                                            <strong><?php echo $producto; ?></strong></td>
                                        <td><?php echo $cantidad; ?></td>
                                        <td>$<?php echo number_format($precio, 2); ?></td>
                                        <td>$<?php echo number_format($subtotal, 2); ?></td>
                                    </tr>
                                    <?php
                                }
                            }
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total de la compra:</strong></td>
                                <td><strong>$<?php echo number_format($totalCompra, 2); ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

            </div>
        </div>
    </main>

    <!-- Modal de confirmación para cancelar entrega -->
    <div class="modal fade" id="confirmarCancelarModal" tabindex="-1" aria-labelledby="confirmarCancelarModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="confirmarCancelarModalLabel">Cancelar Entrega</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas cancelar esta entrega?
                </div>
                <div class="modal-footer">
                    <!-- Botón para cancelar la acción -->
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">No</button>
                    <!-- Botón para confirmar la cancelación -->
                    <a href="eliminar_entrega.php?id=<?php echo $proveedor_id; ?>" class="btn btn-danger">Sí, Cancelar <i
                            class="bi bi-trash"></i></a>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal de confirmación para eliminar entrega -->
    <div class="modal fade" id="modalEliminarEntrega" tabindex="-1" aria-labelledby="modalEliminarEntregaLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEliminarEntregaLabel">Eliminar Entrega</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Estás seguro de que deseas eliminar esta entrega?
                </div>
                <div class="modal-footer">
                    <!-- Botón para cancelar la acción -->
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No</button>
                    <!-- Botón para confirmar la eliminación -->
                    <a href="eliminar_entregas.php?id=<?php echo $proveedor_id; ?>" class="btn btn-danger">Sí, Eliminar</a>
                </div>
            </div>
        </div>
    </div>

    <?php require '../footer.php'; ?>

    <?php
}
function getStatusLabel($status)
{
    switch ($status) {
        case 0:
            return 'Pendiente';
        case 1:
            return 'Aprobada';
        case 2:
            return 'Cancelada';
        default:
            return 'Desconocida';
    }
}

main();
?>