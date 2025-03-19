<?php

ob_start();

require '../config/database.php';
require '../config/config.php';
require '../header.php';

// Inicializar variables para evitar errores de "undefined index"
$fecha_entrega = $nombre_proveedor = $personal = $lista_productos = $numero_orden = $condicion = '';

// Obtener el ID del proveedor desde la URL
if (isset($_GET['id'])) {
    $proveedor_id = $_GET['id'];

    // Conectar a la base de datos
    $db = new Database();
    $con = $db->conectar();

    // Consulta para obtener el nombre del proveedor basado en el ID
    $sql_nombre_proveedor = "SELECT nombre FROM proveedores WHERE id = :id AND activo = 1 LIMIT 1";
    $stmt_nombre = $con->prepare($sql_nombre_proveedor);
    $stmt_nombre->bindValue(':id', $proveedor_id, PDO::PARAM_INT);
    $stmt_nombre->execute();

    $nombre_proveedor = $stmt_nombre->fetchColumn(); // Obtener el nombre del proveedor

    // Consulta para buscar productos donde id_proveedor contenga el ID del proveedor (separado por comas)
    $sql = "SELECT * FROM productos WHERE id_proveedor LIKE :id LIMIT 10";
    $stmt = $con->prepare($sql);

    // Usar comodines para buscar el ID en cualquier posición
    $stmt->bindValue(':id', "%{$proveedor_id}%", PDO::PARAM_STR);
    $stmt->execute();

    // Obtener los resultados
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $fecha_entrega = $_POST['fecha_entrega'];
    $nombre_proveedor = $_POST['nombre_proveedor'];
    $personal = $_POST['personal'];
    $numero_orden = $_POST['numero_orden'];
    $condicion = $_POST['condicion'];
    $cantidad_total = $_POST['cantidad_total'];

    // Verificar si "lista_productos" está definido y no está vacío antes de acceder a él
    if (isset($_POST['lista_productos']) && !empty($_POST['lista_productos'])) {
        $lista_productos = implode(", ", $_POST['lista_productos']); // Convierte el array en una cadena separada por comas
    } else {
        $lista_productos = 'Ningún producto seleccionado';
    }

    try {
        $db = new Database();
        $con = $db->conectar();

        $sql = "INSERT INTO transaccion_prov (fecha_entrega, nombre_proveedor, personal, lista_productos, numero_orden, condicion, cantidad";

        for ($i = 1; $i <= 10; $i++) {
            $sql .= ", cantidad_producto{$i}";
        }

        $sql .= ") VALUES (:fecha_entrega, :nombre_proveedor, :personal, :lista_productos, :numero_orden, :condicion, :cantidad";

        for ($i = 1; $i <= 10; $i++) {
            $sql .= ", :cantidad_producto{$i}";
        }

        $sql .= ")";

        $stmt = $con->prepare($sql);

        $stmt->bindParam(':fecha_entrega', $fecha_entrega);
        $stmt->bindParam(':nombre_proveedor', $nombre_proveedor);
        $stmt->bindParam(':personal', $personal);
        $stmt->bindParam(':lista_productos', $lista_productos);
        $stmt->bindParam(':numero_orden', $numero_orden);
        $stmt->bindParam(':condicion', $condicion);
        $stmt->bindParam(':cantidad', $cantidad_total);

        for ($i = 1; $i <= 10; $i++) {
            $cantidad_parametro = ":cantidad_producto{$i}";
            $cantidad_valor = $_POST["cantidad_producto_{$i}"] ?? 0;
            $stmt->bindValue($cantidad_parametro, $cantidad_valor, PDO::PARAM_INT);
        }

        $stmt->execute();

        header("Location: vermas.php?id={$proveedor_id}");
        exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>

<!-- El resto del código HTML permanece sin cambios -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agregar Entrega</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>

<body>
    <div class="container mt-3">
        <h2 class="mb-4">Agregar Entrega</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="row g-3">
                <div class="col-md-6">
                    <label for="fecha_entrega" class="form-label">Fecha de Entrega:</label>
                    <input type="date" name="fecha_entrega" class="form-control" required
                        placeholder="Selecciona la fecha de entrega">
                </div>
                <div class="col-md-6">
                    <label for="nombre_proveedor" class="form-label">Nombre del Proveedor:</label>
                    <input type="text" name="nombre_proveedor" class="form-control"
                        value="<?php echo htmlspecialchars($nombre_proveedor); ?>" readonly>
                </div>
            </div>


            <div class="form-group">
                <label for="personal">Personal:</label>
                <input type="text" name="personal" class="form-control" required
                    placeholder="Nombre del personal quien autorizo">
            </div>
            <div class="form-group">
                <label for="lista_productos">Lista de Productos:</label>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="mostrarProductos">
                    <label class="form-check-label" for="mostrarProductos">
                        Seleccionar Productos
                    </label>
                </div>
                <div id="productos" style="display: none;">
                    <?php
                    $contador = 1;
                    foreach ($productos as $producto):
                        ?>
                        <div class="form-check">
                            <input class="form-check-input producto" type="checkbox" name="lista_productos[]"
                                value="<?php echo $producto['nombre']; ?>" id="producto_<?php echo $producto['id']; ?>">
                            <label class="form-check-label" for="producto_<?php echo $producto['id']; ?>">
                                <?php echo $producto['nombre']; ?>
                            </label><br>
                            <input type="number" name="cantidad_producto_<?php echo $contador; ?>"
                                class="form-control cantidad-producto" style="display:none;"
                                placeholder="Cantidad de <?php echo $producto['nombre']; ?>">
                        </div>
                        <?php
                        $contador++;
                        if ($contador > 10)
                            break;
                    endforeach;
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label for="cantidad_total">Cantidad Total de Productos:</label>
                <input type="number" name="cantidad_total" id="cantidad_total" class="form-control" readonly>
            </div>

            <?php
            // Obtener la fecha actual
            $fecha_hoy = date("Y-m-d");
            $numero_orden_dia = "001"; // Por defecto, el primer número de orden del día
            
            // Si se desea calcular el número de orden basado en la base de datos:
            if (isset($con)) {
                $sql_numero_orden = "SELECT COUNT(*) + 1 AS numero_orden FROM transaccion_prov WHERE DATE(fecha_entrega) = :fecha_hoy";
                $stmt_orden = $con->prepare($sql_numero_orden);
                $stmt_orden->bindValue(':fecha_hoy', $fecha_hoy, PDO::PARAM_STR);
                $stmt_orden->execute();

                $resultado_orden = $stmt_orden->fetch(PDO::FETCH_ASSOC);
                if ($resultado_orden && isset($resultado_orden['numero_orden'])) {
                    $numero_orden_dia = str_pad($resultado_orden['numero_orden'], 3, "0", STR_PAD_LEFT); // Asegura formato de tres dígitos
                }
            }

            // Generar el número de orden completo (por ejemplo: 20241129-001)
            $numero_orden_automatico = date("Ymd") . "-" . $numero_orden_dia;
            ?>

            <div class="row g-3">
                <div class="col-md-6">
                    <label for="numero_orden" class="form-label">Número de Orden:</label>
                    <input type="text" name="numero_orden" id="numero_orden" class="form-control"
                        value="<?php echo htmlspecialchars($numero_orden_automatico); ?>">
                </div>
                <div class="col-md-6">
                    <label for="condicion" class="form-label">Condición:</label>
                    <input type="text" name="condicion" class="form-control" required
                        placeholder="Condición de la entrega - Anotar observaciones en caso de existir">
                </div>
            </div>

            <!-- Salto de línea antes del botón -->
            <div class="mt-4">
                <button type="submit" id="guardar" class="btn btn-primary">Guardar</button>
            </div>

        </form>
    </div>
    <script>

        // Escuchar el cambio en la selección de productos
        var checkboxes = document.querySelectorAll('.producto');
        checkboxes.forEach(function (checkbox) {
            checkbox.addEventListener('change', function () {
                mostrarOcultarCantidadProducto(this);
                calcularCantidadTotal();
            });
        });

        // Función para mostrar u ocultar el formulario de cantidad de productos
        function mostrarOcultarCantidadProducto(checkbox) {
            var cantidadProductoInput = checkbox.parentNode.querySelector('.cantidad-producto');
            if (checkbox.checked) {
                cantidadProductoInput.style.display = 'block';
            } else {
                cantidadProductoInput.style.display = 'none';
            }
        }

        document.getElementById('mostrarProductos').addEventListener('change', function () {
            var productosDiv = document.getElementById('productos');
            if (this.checked) {
                productosDiv.style.display = 'block';
            } else {
                productosDiv.style.display = 'none';
            }
        });

        // Función para calcular la cantidad total de productos
        function calcularCantidadTotal() {
            var cantidadTotal = 0;
            var cantidadInputs = document.querySelectorAll('.cantidad-producto');
            cantidadInputs.forEach(function (input) {
                if (input.style.display !== 'none') {
                    cantidadTotal += parseInt(input.value) || 0;
                }
            });
            document.getElementById('cantidad_total').value = cantidadTotal;
        }

    </script>
</body>

</html>