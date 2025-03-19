<?php
// Incluir archivo de configuración
require '../config/config.php';
require '../header.php';

// Verificar si se recibieron los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id']) && isset($_POST['productos']) && isset($_POST['cantidades'])) {
    // Obtener el ID de venta, los productos seleccionados y sus cantidades
    $id_venta = $_POST['id'];
    $productos_ids = $_POST['productos'];
    $cantidades = $_POST['cantidades'];

    // Eliminar elementos vacíos del array de IDs de productos y cantidades
    $productos_ids = array_filter($productos_ids);
    $cantidades = array_filter($cantidades);

    // Insertar las cantidades de los productos en la tabla de relación compra_personal_productos
    $db = new Database();
    $con = $db->conectar();
    $sql_insert = $con->prepare("INSERT INTO compra_personal_productos (id_venta, id_producto, cantidad) VALUES (?, ?, ?)");

    // Iterar sobre los productos y sus cantidades para insertarlos en la tabla de relación
    foreach ($productos_ids as $key => $producto_id) {
        if (!empty($producto_id) && isset($cantidades[$key])) {
            // Ejecutar la consulta SQL para insertar la cantidad del producto en la tabla de relación
            $sql_insert->execute([$id_venta, $producto_id, $cantidades[$key]]);
        }
    }

    // Consulta SQL para obtener el nombre y email del cliente
    $sql_cliente = $con->prepare("SELECT nombres, email FROM clientes WHERE id = (SELECT id_cliente FROM compra_personal WHERE id = ?)");
    $sql_cliente->execute([$id_venta]);
    $cliente = $sql_cliente->fetch(PDO::FETCH_ASSOC);

    // Verificar que se estén recibiendo los nombres de los productos
    $productos_nombres = [];
    $total = 0;
    foreach ($productos_ids as $key => $producto_id) {
        if (!empty($producto_id) && isset($cantidades[$key])) {
            $sql_producto = $con->prepare("SELECT nombre, precio, descuento FROM productos WHERE id = ?");
            $sql_producto->execute([$producto_id]);
            $producto = $sql_producto->fetch(PDO::FETCH_ASSOC);
            if ($producto) {
                // Aplicar el descuento al precio del producto si existe
                $precio_descuento = $producto['precio'];
                if ($producto['descuento'] > 0) {
                    $precio_descuento = $producto['precio'] * (1 - $producto['descuento'] / 100);
                }

                // Sumar el precio del producto al total multiplicado por la cantidad
                $total += $precio_descuento * $cantidades[$key];

                // Guardar el nombre del producto
                $productos_nombres[] = $producto['nombre'];

                // Imprimir el producto con su precio individual en la tabla de lista de productos
            } else {
                echo "Error: No se encontró el producto con el ID $producto_id";
            }
        }
    }

    // Actualizar el precio total de la compra en la tabla compra_personal
    $sql_update_total = $con->prepare("UPDATE compra_personal SET total = ? WHERE id = ?");
    $sql_update_total->execute([$total, $id_venta]);

    // Ahora, después de procesar la compra, puedes mostrar la información de la compra
    // Consulta SQL para obtener la información de la compra
    $sql_info_compra = $con->prepare("SELECT * FROM compra_personal WHERE id = ?");
    $sql_info_compra->execute([$id_venta]);
    $info_compra = $sql_info_compra->fetch(PDO::FETCH_ASSOC);

    // Imprimir la información de la compra
    ?>
    <main>
        <div class="container-fluid px-4">
            <h2 class="mt-3">Detalles de la compra</h2>
            <div class="row">
                <div class="col">
                    <h3>Información de la compra</h3>
                    <ul class="list-group">
                        <li class="list-group-item">ID Transacción: <?php echo $info_compra['id_transaccion']; ?></li>
                        <li class="list-group-item">Email del Cliente: <?php echo $cliente['email']; ?></li>
                        <li class="list-group-item">Nombre del Cliente: <?php echo $cliente['nombres']; ?></li>
                        <li class="list-group-item">Fecha: <?php echo $info_compra['fecha']; ?></li>
                        <li class="list-group-item">Total: $<?php echo number_format($total, 2); ?></li>
                    </ul>

                    <a href="pago_compra.php?id_venta=<?php echo $id_venta; ?>" class="btn btn-success mt-3" style="width: 100%;">Realizar Pago</a>


                </div>
                <div class="col">
                    <h3>Lista de productos</h3>
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Precio Individual</th>
                                <th>Cantidad</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($productos_ids as $key => $producto_id) {
                                if (!empty($producto_id) && isset($cantidades[$key])) {
                                    $sql_producto = $con->prepare("SELECT nombre, precio FROM productos WHERE id = ?");
                                    $sql_producto->execute([$producto_id]);
                                    $producto = $sql_producto->fetch(PDO::FETCH_ASSOC);
                                    if ($producto) {
                                        echo "<tr><td>{$producto_id}</td><td>{$producto['nombre']}</td><td>{$producto['precio']}</td><td>{$cantidades[$key]}</td></tr>";
                                    } else {
                                        echo "Error: No se encontró el producto con el ID $producto_id";
                                    }
                                }
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>
    <?php
} else {
    // Manejar el caso en que no se hayan recibido los datos del formulario
    echo "Error: No se recibieron los datos del formulario correctamente.";
}
?>

<?php require '../footer.php'; ?>
