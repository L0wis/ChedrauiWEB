<?php
// Incluir archivo de configuración
require '../config/config.php';
require '../header.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

// Verificar si se recibió el ID de la venta
if (isset($_GET['id'])) {
    // Obtener el ID de la venta
    $id_venta = $_GET['id'];

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
        // Consultar los detalles de la venta desde la base de datos
        $sql_venta = "SELECT cp.*, c.nombres, c.apellidos, c.email, cp.total,
                CASE
                    WHEN cp.direccion IS NOT NULL AND cp.direccion != '' THEN CONCAT(cp.direccion, ', ', cp.referencias)
                    ELSE 'Entregado en caja'
                END AS direccion_entrega
                FROM compra_personal cp
                INNER JOIN clientes c ON cp.id_cliente = c.id
                WHERE cp.id = ?";
        $stmt_venta = $con->prepare($sql_venta);
        $stmt_venta->execute([$id_venta]);
        $venta = $stmt_venta->fetch(PDO::FETCH_ASSOC);

        if ($venta) {
            // Calcular el total de la compra
            $total_compra = $venta['total'];

            // Consultar los productos asociados a la venta
            $sql_productos = "SELECT p.nombre AS nombre_producto, p.precio, cpp.cantidad
                              FROM compra_personal_productos cpp
                              INNER JOIN productos p ON cpp.id_producto = p.id
                              WHERE cpp.id_venta = ?";
            $stmt_productos = $con->prepare($sql_productos);
            $stmt_productos->execute([$id_venta]);

            // Mostrar los detalles de la compra
            ?>
            <main>
                <div class="container-fluid px-4">
                    <h2 class="mt-3">Detalles de la Compra</h2>
                    <div class="row">
                        <div class="col">
                            <h3>Información de la Compra</h3>
                            <ul class="list-group">
                                <li class="list-group-item">ID Transacción: <?php echo $venta['id_transaccion']; ?></li>
                                <li class="list-group-item">Nombre del Cliente:
                                    <?php echo $venta['nombres'] . ' ' . $venta['apellidos']; ?></li>
                                <li class="list-group-item">Email del Cliente: <?php echo $venta['email']; ?></li>
                                <li class="list-group-item">Dirección de Entrega: <?php echo $venta['direccion_entrega']; ?></li>
                                <li class="list-group-item">Fecha: <?php echo $venta['fecha']; ?></li>
                                <li class="list-group-item">Status: <?php echo $venta['status']; ?></li>
                                <li class="list-group-item">Tipo de Pago: <?php echo $venta['tipo_pago']; ?></li>
                                <li class="list-group-item">Total de la Compra: $<?php echo number_format($total_compra, 2); ?></li>
                            </ul>
                        </div>
                        <div class="col">
                            <h3>Productos Comprados</h3>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Recorrer todos los productos y mostrarlos en la tabla
                                    while ($producto = $stmt_productos->fetch(PDO::FETCH_ASSOC)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $producto['nombre_producto']; ?></td>
                                            <td><?php echo $producto['cantidad']; ?></td>
                                            <td>$<?php echo number_format($producto['precio'], 2); ?></td>
                                        </tr>
                                        <?php
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Agrega el botón de Comprobante -->
                <div class="container-fluid mt-3">
                    <div class="row justify-content">
                        <div class="col-auto">
                            <a href="generar_comprobante.php?id=<?php echo $id_venta; ?>" class="btn btn-success">Generar
                                Comprobante</a>
                        </div>
                    </div>
                </div>
            </main>

            <?php
        } else {
            // Si no se encontraron detalles de la venta, mostrar un mensaje
            echo "<p>No se encontraron detalles de la compra.</p>";
        }
    } catch (PDOException $e) {
        // Manejar el error
        echo "Error al obtener los detalles de la compra: " . $e->getMessage();
    }
} else {
    // Si no se recibió el ID de la venta, redirigir al usuario
    header('Location: index.php');
    exit;
}

// Incluir el archivo de pie de página
require '../footer.php';
?>