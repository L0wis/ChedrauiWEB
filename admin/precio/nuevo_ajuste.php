<?php

ob_start(); // Inicia el búfer de salida

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

$db = new Database();
$con = $db->conectar();

// Obtener lista de productos activos
$sql = "SELECT id, nombre, precio FROM productos WHERE activo = 1";
$resultado = $con->query($sql);
$productos = $resultado->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $producto_id = $_POST['producto_id'];
    $tipo_ajuste = $_POST['tipo_ajuste'];
    $valor = $_POST['valor'];
    $motivo = $_POST['motivo'];
    $fecha_inicio = $_POST['fecha_inicio'];
    $fecha_fin = $_POST['fecha_fin'] ? $_POST['fecha_fin'] : null;
    $tipo_valor = $_POST['tipo_valor']; // Nuevo campo para el tipo de valor (porcentaje o peso)

    // Validar datos
    if ($producto_id && $tipo_ajuste && $valor && $motivo && $fecha_inicio && $tipo_valor) {// Insertar el ajuste en la base de datos
        $sql_insert = "INSERT INTO ajustes_precios (producto_id, tipo_ajuste, valor, motivo, fecha_inicio, fecha_fin, tipo_valor, activo)
                       VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $con->prepare($sql_insert);
        $stmt->execute([$producto_id, $tipo_ajuste, $valor, $motivo, $fecha_inicio, $fecha_fin, $tipo_valor, 1]); // Activo = 1


        // Obtener el precio actual del producto
        $sql_select = "SELECT precio FROM productos WHERE id = ?";
        $stmt_select = $con->prepare($sql_select);
        $stmt_select->execute([$producto_id]);
        $producto = $stmt_select->fetch(PDO::FETCH_ASSOC);

        if ($producto) {
            $precio_actual = $producto['precio'];

            // Calcular el nuevo precio según el tipo de ajuste y el tipo de valor
            if ($tipo_valor == 'porcentaje') {
                if ($tipo_ajuste == 'incremento') {
                    $nuevo_precio = $precio_actual + ($precio_actual * ($valor / 100));
                    $tipo_ajuste_db = 'aumento'; // Para la tabla historial_precios
                } elseif ($tipo_ajuste == 'descuento') {
                    $nuevo_precio = $precio_actual - ($precio_actual * ($valor / 100));
                    $tipo_ajuste_db = 'disminucion';
                }
            } else {
                if ($tipo_ajuste == 'incremento') {
                    $nuevo_precio = $precio_actual + $valor;
                    $tipo_ajuste_db = 'aumento';
                } elseif ($tipo_ajuste == 'descuento') {
                    $nuevo_precio = $precio_actual - $valor;
                    $tipo_ajuste_db = 'disminucion';
                }
            }

            // Insertar en la tabla historial_precios
            $sql_historial = "INSERT INTO historial_precios 
                (producto_id, precio_anteriores, precio_nuevo, fecha_ajuste, tipo_ajuste, motivo, activo) 
                VALUES (?, ?, ?, CURDATE(), ?, ?, ?)";
            $stmt_historial = $con->prepare($sql_historial);
            $stmt_historial->execute([$producto_id, $precio_actual, $nuevo_precio, $tipo_ajuste_db, $motivo, 1]);

            // Actualizar el precio del producto
            $sql_update = "UPDATE productos SET precio = ? WHERE id = ?";
            $stmt_update = $con->prepare($sql_update);
            $stmt_update->execute([$nuevo_precio, $producto_id]);
        }

        // Redirigir después de guardar el ajuste
        header('Location: index.php');
        exit;
    } else {
        $error = "Todos los campos son obligatorios.";
    }
}

ob_end_flush(); // Finaliza el búfer de salida

?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3 text-center">Nuevo Ajuste de Precio</h2>

        <!-- Mensaje de error (si lo hay) -->
        <?php if (isset($error)) { ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error, ENT_QUOTES); ?></div>
        <?php } ?>

        <div class="card shadow-sm p-4">
            <form method="POST" action="nuevo_ajuste.php">
                <div class="row">
                    <!-- Producto -->
                    <div class="col-md-6 mb-3">
                        <label for="producto_id" class="form-label">Producto</label>
                        <select name="producto_id" id="producto_id" class="form-select" required>
                            <option value="">Seleccione un producto</option>
                            <?php foreach ($productos as $producto) { ?>
                                <option value="<?php echo $producto['id']; ?>">
                                    <?php echo htmlspecialchars($producto['nombre'], ENT_QUOTES); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>

                    <!-- Tipo de ajuste -->
                    <div class="col-md-6 mb-3">
                        <label for="tipo_ajuste" class="form-label">Tipo de ajuste</label>
                        <select name="tipo_ajuste" id="tipo_ajuste" class="form-select" required>
                            <option value="">Seleccione el tipo de ajuste</option>
                            <option value="incremento">Incremento</option>
                            <option value="descuento">Descuento</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <!-- Valor del ajuste -->
                    <div class="col-md-6 mb-3">
                        <label for="valor" class="form-label">Valor del ajuste (% o $)</label>
                        <input type="number" name="valor" id="valor" class="form-control" required min="0" step="0.01">
                    </div>

                    <!-- Tipo de valor -->
                    <div class="col-md-6 mb-3">
                        <label for="tipo_valor" class="form-label">Tipo de valor</label>
                        <select name="tipo_valor" id="tipo_valor" class="form-select" required>
                            <option value="">Seleccione el tipo de valor</option>
                            <option value="porcentaje">Porcentaje</option>
                            <option value="peso">Peso</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <!-- Motivo del ajuste -->
                    <div class="col-md-12 mb-3">
                        <label for="motivo" class="form-label">Motivo del ajuste</label>
                        <textarea name="motivo" id="motivo" class="form-control" required></textarea>
                    </div>
                </div>

                <div class="row">
                    <!-- Fecha inicio -->
                    <div class="col-md-6 mb-3">
                        <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required>
                    </div>

                    <!-- Fecha fin -->
                    <div class="col-md-6 mb-3">
                        <label for="fecha_fin" class="form-label">Fecha de fin (opcional)</label>
                        <input type="date" name="fecha_fin" id="fecha_fin" class="form-control">
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary px-4">Guardar ajuste</button>
                    <a href="ajustes_precios.php" class="btn btn-secondary px-4">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require '../footer.php'; ?>