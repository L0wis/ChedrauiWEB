<?php

ob_start();

error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_CORE_WARNING | E_COMPILE_ERROR | E_COMPILE_WARNING);
// Tu código aquí

require '../config/database.php';
require '../config/config.php';
require '../header.php';

// Verificar los permisos de usuario
if (!isset($_SESSION['user_type'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SESSION['user_type'] != 'admin') {
    header('Location: ../../index.php');
    exit;
}

if (isset($array['id'])) {
    // Acceder a la clave "id"
    $id = $array['id'];
} else {
    // La clave "id" no está definida
    // Realizar alguna acción alternativa o manejo de error
}


// Verificar si se proporcionó el parámetro 'id' en la URL
if (!isset($_GET['id'])) {
    echo "Error: No se proporcionó el parámetro 'id'";
    exit;
}

$proveedor_id = $_GET['id'];

// Crear una instancia de la base de datos y conectar
$db = new Database();
$con = $db->conectar();

// Procesamiento de la aprobación de la entrega
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    if (isset($_POST['cantidad_producto']) && isset($_POST['producto_nombre'])) {
        $cantidades_productos = $_POST['cantidad_producto'];
        $nombres_productos = $_POST['producto_nombre'];

        if (isset($array[0])) {
            // Acceder a la clave "0"
            $valor = $array[0];
        } else {
            // La clave "0" no está definida
            // Realizar alguna acción alternativa o manejo de error
        }


        // Actualizar el stock de productos
        foreach ($cantidades_productos as $entrega_index => $cantidades) {
            if (isset($nombres_productos[$entrega_index]) && isset($cantidades)) {
                foreach ($cantidades as $producto_index => $cantidad) {
                    // Verificar si el ID del producto y la cantidad están definidos
                    if (isset($nombres_productos[$entrega_index][$producto_index]) && isset($cantidad)) {
                        // Obtener el nombre del producto y la cantidad
                        $producto_nombre = $nombres_productos[$entrega_index][$producto_index];
                        $cantidad = intval($cantidad); // Convertir la cantidad a entero

                        // Consultar el producto en la base de datos
                        $sqlProducto = "SELECT id, stock FROM productos WHERE nombre = :nombre";
                        $stmtProducto = $con->prepare($sqlProducto);
                        $stmtProducto->bindParam(':nombre', $producto_nombre);
                        $stmtProducto->execute();
                        $producto = $stmtProducto->fetch(PDO::FETCH_ASSOC);

                        // Verificar si se encontró el producto en la base de datos
                        if ($producto) {
                            // Calcular el nuevo stock
                            $nuevo_stock = $producto['stock'] + $cantidad;

                            // Actualizar el stock en la base de datos
                            $sqlActualizarStock = "UPDATE productos SET stock = :nuevo_stock WHERE id = :id";
                            $stmtActualizarStock = $con->prepare($sqlActualizarStock);
                            $stmtActualizarStock->bindParam(':nuevo_stock', $nuevo_stock);
                            $stmtActualizarStock->bindParam(':id', $producto['id']);
                            $stmtActualizarStock->execute();
                        } else {
                            // Manejo de la situación donde el producto no se encuentra en la base de datos
                            // Por ejemplo, mostrar un mensaje de error o realizar alguna acción alternativa
                        }
                    } else {
                        // Manejo de la situación donde el ID del producto o la cantidad no están definidos
                        // Por ejemplo, mostrar un mensaje de error o realizar alguna acción alternativa
                    }
                }
            } else {
                // Manejo de la situación donde $nombres_productos[$entrega_index] o $cantidades no están definidos
                // Por ejemplo, mostrar un mensaje de error o realizar alguna acción alternativa
            }
        }

        // Actualizar el valor de la columna "status" a 1 en la tabla "transaccion_prov"
$sqlActualizarStatus = "UPDATE transaccion_prov SET status = 1 WHERE id = :proveedor_id";
$stmtActualizarStatus = $con->prepare($sqlActualizarStatus);
$stmtActualizarStatus->bindParam(':proveedor_id', $proveedor_id);
$stmtActualizarStatus->execute();

        // Redirigir al usuario a otra página o mostrar un mensaje de éxito
        header("Location: vermas.php?id={$proveedor_id}");
        exit;

    }
}

// Consulta SQL para obtener la información de la entrega del proveedor
$sql = "SELECT id, fecha_entrega, nombre_proveedor, personal, lista_productos, cantidad, numero_orden, condicion
        FROM transaccion_prov
        WHERE id = :proveedor_id";

$stmt = $con->prepare($sql);
$stmt->bindParam(':proveedor_id', $proveedor_id);
$stmt->execute();
$entregas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Aprobar Entrega</h2>

        <!-- Mostrar la información de la entrega -->
        <div class="row">
            <div class="col">
                <!-- Aquí va la información de la entrega -->
            </div>
        </div>

        <!-- Formulario para aprobar la entrega -->
        <form action="aprobar_entrega.php?id=<?php echo $proveedor_id; ?>" method="post">
            <div class="mb-3">
                <h3>Cantidad de Productos Seleccionados</h3>
                <div id="cantidad_inputs">
                    <?php foreach ($entregas as $index => $entrega): ?>
                        <?php
                        $productos = explode(',', $entrega['lista_productos']);
                        $cantidades = explode(',', $entrega['cantidad']);
                        foreach ($productos as $producto_index => $producto_nombre):
                            $cantidad = isset($cantidades[$producto_index]) ? $cantidades[$producto_index] : ''; // Verificar si la cantidad está definida
                            ?>
                            <div class="mb-3">
                                <label for="cantidad_producto_<?php echo $index; ?>_<?php echo $producto_index; ?>"
                                    class="form-label"><?php echo $producto_nombre; ?></label>
                                <input type="number" class="form-control"
                                    name="cantidad_producto[<?php echo $index; ?>][<?php echo $producto_index; ?>]"
                                    id="cantidad_producto_<?php echo $index; ?>_<?php echo $producto_index; ?>"
                                    value="<?php echo $cantidad; ?>" required>
                                <input type="hidden"
                                    name="producto_nombre[<?php echo $index; ?>][<?php echo $producto_index; ?>]"
                                    value="<?php echo $producto_nombre; ?>">
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Botón para aprobar la entrega -->
            <div class="my-3">
                <button type="submit" class="btn btn-success"><i class="bi bi-check-square"></i> Aprobar
                    Entrega</button>
            </div>
        </form>
    </div>
</main>

<?php require '../footer.php'; ?>