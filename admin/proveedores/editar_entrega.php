<?php

ob_start();

require '../config/database.php';
require '../config/config.php';
require '../header.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

// Verificar si se ha proporcionado el parámetro 'id' en la URL
if (!isset($_GET['id'])) {
    echo "Error: No se proporcionó el parámetro 'id'";
    exit;
}

$id = $_GET['id'];

// Consultar la información de la entrega
$sql = $con->prepare("SELECT id, fecha_entrega, nombre_proveedor, personal, lista_productos, cantidad, numero_orden, condicion FROM transaccion_prov WHERE id = ? LIMIT 1");
$sql->execute([$id]);
$entrega = $sql->fetch(PDO::FETCH_ASSOC);

// Verificar si se encontró una entrega con el ID proporcionado
if (!$entrega) {
    echo "Error: No se encontró ninguna entrega con el ID proporcionado: $id";
    exit;
}

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Procesar la actualización de la base de datos
    $fecha_entrega = $_POST['fecha_entrega'];
    $nombre_proveedor = $_POST['nombre_proveedor'];
    $personal = $_POST['personal'];
    $lista_productos = implode(',', $_POST['lista_productos']); // Convertir el array de productos seleccionados en una cadena separada por comas
    $numero_orden = $_POST['numero_orden'];
    $condicion = $_POST['condicion'];

    // Obtener la cantidad de productos seleccionados y sus respectivas cantidades
    $cantidad_productos = $_POST['cantidad_producto'];

    // Inicializar una variable para la cantidad total
    $cantidad_total = 0;

    // Crear un array para almacenar las cantidades de productos
    $cantidades_actualizadas = [];

    foreach ($cantidad_productos as $index => $cantidad) {
        $nombre_campo = 'cantidad_producto' . ($index + 1); // Nombre del campo de cantidad
        $cantidades_actualizadas[$nombre_campo] = $cantidad; // Almacenar la cantidad en el array
        $cantidad_total += $cantidad; // Sumar la cantidad al total
    }

    // Actualizar la cantidad total en la tabla transaccion_prov
    $sqlUpdate = $con->prepare("UPDATE transaccion_prov SET fecha_entrega=?, nombre_proveedor=?, personal=?, lista_productos=?, cantidad=?, numero_orden=?, condicion=?, " . implode('=?, ', array_keys($cantidades_actualizadas)) . "=? WHERE id=?");
    $sqlUpdate->execute([$fecha_entrega, $nombre_proveedor, $personal, $lista_productos, $cantidad_total, $numero_orden, $condicion, ...array_values($cantidades_actualizadas), $id]);

    // Redireccionar después de la actualización
    header("Location: obtener_productos.php?id=$id");
    exit;
}

// Obtener el ID del proveedor usando su nombre
$nombre_proveedor = $entrega['nombre_proveedor'];
$sqlProveedorId = $con->prepare("SELECT id FROM proveedores WHERE nombre = ?");
$sqlProveedorId->execute([$nombre_proveedor]);
$proveedor_id = $sqlProveedorId->fetchColumn();

// Obtener la lista de productos asociados al proveedor usando su ID
$sqlProductos = $con->prepare("SELECT id, nombre FROM productos WHERE id_proveedor = ?");
$sqlProductos->execute([$proveedor_id]);
$productosDisponibles = $sqlProductos->fetchAll(PDO::FETCH_ASSOC);
?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Editar Entrega</h2>

        <form action="" method="post" enctype="multipart/form-data" autocomplete="off">
            <div class="mb-3">
                <label for="fecha_entrega" class="form-label">Fecha de Entrega</label>
                <input type="date" class="form-control" name="fecha_entrega" id="fecha_entrega" value="<?php echo $entrega['fecha_entrega']; ?>" required autofocus>
            </div>

            <div class="mb-3">
                <label for="nombre_proveedor" class="form-label">Nombre del Proveedor</label>
                <input type="text" class="form-control" name="nombre_proveedor" id="nombre_proveedor" value="<?php echo $entrega['nombre_proveedor']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="personal" class="form-label">Personal</label>
                <input type="text" class="form-control" name="personal" id="personal" value="<?php echo $entrega['personal']; ?>" required>
            </div>

            <div class="form-group">
                <label for="lista_productos">Productos:</label>
                <?php foreach ($productosDisponibles as $index => $producto) : ?>
                    <div class="form-check">
                        <input class="form-check-input producto" type="checkbox" name="lista_productos[]" value="<?php echo $producto['nombre']; ?>" id="producto_<?php echo $producto['id']; ?>" <?php echo (strpos($entrega['lista_productos'], $producto['nombre']) !== false) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="producto_<?php echo $producto['id']; ?>">
                            <?php echo $producto['nombre']; ?>
                        </label>
                        <input type="hidden" name="producto_index[]" value="<?php echo $index; ?>">
                    </div>
                <?php endforeach; ?>
            </div>

            <div id="cantidad_form" style="display:none;">
                <h3>Cantidad de Productos Seleccionados</h3>
                <div id="cantidad_inputs">
                    <!-- Aquí se generarán los campos de cantidad dinámicamente -->
                </div>
            </div>

            <div class="mb-3">
                <label for="numero_orden" class="form-label">Número de Orden</label>
                <input type="text" class="form-control" name="numero_orden" id="numero_orden" value="<?php echo $entrega['numero_orden']; ?>" required>
            </div>

            <div class="mb-3">
                <label for="condicion" class="form-label">Condición</label>
                <input type="text" class="form-control" name="condicion" id="condicion" value="<?php echo $entrega['condicion']; ?>" required>
            </div>

            <button type="button" id="siguiente" class="btn btn-primary">Siguiente</button>
            <button type="submit" id="guardar" class="btn btn-primary" style="display:none;">Guardar</button>
        </form>
    </div>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const productosSeleccionados = document.querySelectorAll('.producto');

        // Función para generar campos de cantidad dinámicamente
        function generarCamposCantidad(cantidad) {
            const cantidadInputs = document.getElementById('cantidad_inputs');
            cantidadInputs.innerHTML = ''; // Limpiar los campos anteriores

            for (let i = 0; i < cantidad; i++) {
                const inputCantidad = document.createElement('input');
                inputCantidad.type = 'number';
                inputCantidad.name = 'cantidad_producto[]';
                inputCantidad.placeholder = 'Cantidad de producto ' + (i + 1);
                inputCantidad.required = true;
                cantidadInputs.appendChild(inputCantidad);

                const inputHidden = document.createElement('input');
                inputHidden.type = 'hidden';
                inputHidden.name = 'producto_index[]';
                inputHidden.value = i;
                cantidadInputs.appendChild(inputHidden);
            }
        }

        // Evento al hacer clic en el botón "Siguiente"
        document.getElementById('siguiente').addEventListener('click', function() {
            const cantidadSeleccionada = [...productosSeleccionados].filter(producto => producto.checked).length;
            if (cantidadSeleccionada > 0) {
                generarCamposCantidad(cantidadSeleccionada);
                document.getElementById('cantidad_form').style.display = 'block';
                document.getElementById('siguiente').style.display = 'none';
                document.getElementById('guardar').style.display = 'block';
            } else {
                alert('Por favor, seleccione al menos un producto.');
            }
        });
    });
</script>
