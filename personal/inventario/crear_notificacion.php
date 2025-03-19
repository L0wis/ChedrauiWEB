<?php
// Encabezado de la página
require '../config/config.php';
require '../header.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Crear una instancia de la clase Database y conectar a la base de datos
$db = new Database();
$con = $db->conectar();

// Verificar si la conexión se ha establecido correctamente
if (!$con) {
    echo "Error al conectar a la base de datos";
    exit;
}

// Resto de tu código aquí
// Consultar los productos con stock menor a 50
$sql_stock_bajo = "SELECT id, nombre, stock FROM productos WHERE stock < 50";
$stmt_stock_bajo = $con->query($sql_stock_bajo); // Utiliza $con en lugar de $pdo
$productos_stock_bajo = $stmt_stock_bajo->fetchAll(PDO::FETCH_ASSOC);

// Consultar los productos con stock igual o mayor a 50
$sql_stock_suficiente = "SELECT id, nombre, stock FROM productos WHERE stock >= 50";
$stmt_stock_suficiente = $con->query($sql_stock_suficiente); // Utiliza $con en lugar de $pdo
$productos_stock_suficiente = $stmt_stock_suficiente->fetchAll(PDO::FETCH_ASSOC);

// Si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Procesar los datos del formulario

    // Inicializar el mensaje con una cadena vacía
    $mensaje = '';

    // Verificar si se han seleccionado productos con stock menor a 50
    if (isset($_POST['productos_bajo']) && is_array($_POST['productos_bajo'])) {
        // Obtener los IDs y nombres de los productos seleccionados
        $productos_bajo = $_POST['productos_bajo'];
        foreach ($productos_bajo as $producto_id) {
            // Consultar el nombre y stock del producto
            $sql_producto = "SELECT nombre, stock FROM productos WHERE id = :producto_id";
            $stmt_producto = $con->prepare($sql_producto);
            $stmt_producto->execute(['producto_id' => $producto_id]);
            $producto = $stmt_producto->fetch(PDO::FETCH_ASSOC);

            // Verificar si se encontró el producto y si tiene el campo 'stock' definido
            if ($producto && isset($producto['stock'])) {
                // Agregar el nombre y stock del producto al mensaje
                $mensaje .= $producto['nombre'] . ' (' . $producto['stock'] . '), ';
            }
        }
    }

    // Verificar si se han seleccionado productos con stock igual o mayor a 50
    if (isset($_POST['productos_suficiente']) && is_array($_POST['productos_suficiente'])) {
        // Obtener los IDs y nombres de los productos seleccionados
        $productos_suficiente = $_POST['productos_suficiente'];
        foreach ($productos_suficiente as $producto_id) {
            // Consultar el nombre y stock del producto
            $sql_producto = "SELECT nombre, stock FROM productos WHERE id = :producto_id";
            $stmt_producto = $con->prepare($sql_producto);
            $stmt_producto->execute(['producto_id' => $producto_id]);
            $producto = $stmt_producto->fetch(PDO::FETCH_ASSOC);

            // Verificar si se encontró el producto y si tiene el campo 'stock' definido
            if ($producto && isset($producto['stock'])) {
                // Agregar el nombre y stock del producto al mensaje
                $mensaje .= $producto['nombre'] . ' (' . $producto['stock'] . '), ';
            }
        }
    }

    // Quitar la coma y el espacio final del mensaje si es necesario
    $mensaje = rtrim($mensaje, ', ');

    // Insertar la notificación en la base de datos
    $mensaje_notificacion = $_POST['mensaje'];
    $prioridad = $_POST['prioridad'];

    // ID del usuario que inició sesión
    $user_id = $_SESSION['user_id'];

    // Obtener los IDs de los productos seleccionados
    $productos_seleccionados = isset($_POST['productos_bajo']) && is_array($_POST['productos_bajo']) ? $_POST['productos_bajo'] : [];

    if (isset($_POST['productos_suficiente']) && is_array($_POST['productos_suficiente'])) {
        $productos_seleccionados = array_merge($productos_seleccionados, $_POST['productos_suficiente']);
    }

    // Preparar la consulta SQL para insertar la notificación
$sql_insert_notificacion = "INSERT INTO notificacion (id_personal, mensaje, prioridad) VALUES (:user_id, :mensaje, :prioridad)";
$stmt_insert_notificacion = $con->prepare($sql_insert_notificacion);

// Ejecutar la consulta SQL para insertar la notificación
$stmt_insert_notificacion->execute(['user_id' => $user_id, 'mensaje' => $mensaje_notificacion, 'prioridad' => $prioridad]);

// Obtener el ID de la última notificación insertada
$id_notificacion = $con->lastInsertId();

// Insertar las relaciones en la tabla notificacion_producto
foreach ($productos_seleccionados as $producto_id) {
    $sql_insert_relacion = "INSERT INTO notificacion_producto (id_notificacion, id_producto) VALUES (:id_notificacion, :id_producto)";
    $stmt_insert_relacion = $con->prepare($sql_insert_relacion);
    $stmt_insert_relacion->execute(['id_notificacion' => $id_notificacion, 'id_producto' => $producto_id]);
}

    // Redirigir a alguna página después de procesar el formulario
    header('Location: notificacion.php');
    exit;
}
?>


<main>
    <div class="container">
        <h2>Crear Notificación</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="mb-3">
                <label class="form-label">Productos con Stock Menor a 50:</label>
                <button type="button" class="btn btn-sm btn-secondary toggle-list" data-target="#productos-bajo-list">Mostrar/Esconder</button>
                <ul id="productos-bajo-list" class="list-unstyled" style="display: none; column-count: 3;">
                    <?php foreach ($productos_stock_bajo as $producto) { ?>
                        <li class="form-check">
                            <input class="form-check-input" type="checkbox" name="productos_bajo[]" value="<?php echo $producto['id']; ?>" data-stock="<?php echo $producto['stock']; ?>">
                            <label class="form-check-label"><?php echo $producto['nombre']; ?></label>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="mb-3">
                <label class="form-label">Productos con Stock 50 o Más:</label>
                <button type="button" class="btn btn-sm btn-secondary toggle-list" data-target="#productos-suficiente-list">Mostrar/Esconder</button>
                <ul id="productos-suficiente-list" class="list-unstyled" style="display: none; column-count: 5;">
                    <?php foreach ($productos_stock_suficiente as $producto) { ?>
                        <li class="form-check">
                            <input class="form-check-input" type="checkbox" name="productos_suficiente[]" value="<?php echo $producto['id']; ?>" data-stock="<?php echo $producto['stock']; ?>">
                            <label class="form-check-label"><?php echo $producto['nombre']; ?></label>
                        </li>
                    <?php } ?>
                </ul>
            </div>
            <div class="mb-3">
                <label class="form-label">Mensaje de Notificación:</label>
                <textarea name="mensaje" class="form-control" placeholder="Escribe las observaciones aquí. Los productos seleccionados aparecerán automáticamente debajo"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Productos seleccionados:</label>
                <p id="mensaje-notificacion"></p>
            </div>
            <div class="mb-3">
                <label class="form-label">Prioridad:</label>
                <select name="prioridad" class="form-select">
                    <option value="Baja">Baja</option>
                    <option value="Media">Media</option>
                    <option value="Alta">Alta</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Crear Notificación</button>
        </form>
    </div>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Agregar evento de clic al botón de alternar lista
        document.querySelectorAll('.toggle-list').forEach(function(button) {
            button.addEventListener('click', function() {
                var target = document.querySelector(this.getAttribute('data-target'));
                if (target.style.display === 'none') {
                    target.style.display = 'block';
                } else {
                    target.style.display = 'none';
                }
            });
        });
    });

    document.addEventListener("DOMContentLoaded", function() {
        // Agregar evento de cambio a los checkboxes de productos con stock menor a 50
        document.querySelectorAll('input[name="productos_bajo[]"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                actualizarMensaje();
            });
        });

        // Agregar evento de cambio a los checkboxes de productos con stock igual o mayor a 50
        document.querySelectorAll('input[name="productos_suficiente[]"]').forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                actualizarMensaje();
            });
        });

        // Función para actualizar el mensaje
        function actualizarMensaje() {
            var mensaje = '';
            // Obtener los nombres y stock de los productos seleccionados
            document.querySelectorAll('input[name="productos_bajo[]"]:checked').forEach(function(checkbox) {
                mensaje += checkbox.nextElementSibling.textContent + ' (' + checkbox.getAttribute('data-stock') + '), ';
            });
            document.querySelectorAll('input[name="productos_suficiente[]"]:checked').forEach(function(checkbox) {
                mensaje += checkbox.nextElementSibling.textContent + ' (' + checkbox.getAttribute('data-stock') + '), ';
            });

            // Quitar la coma y el espacio final del mensaje si es necesario
            mensaje = mensaje.trim().slice(0, -1);
            // Mostrar el mensaje en el elemento deseado
            document.getElementById('mensaje-notificacion').textContent = mensaje;
        }
    });
</script>

<?php
// Pie de la página
require '../footer.php';
?>
