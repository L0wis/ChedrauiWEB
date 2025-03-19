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

if (isset($_GET['id'])) {
    $ajuste_id = $_GET['id'];

    // Obtener datos del ajuste a editar
    $sql = "SELECT * FROM ajustes_precios WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->execute([$ajuste_id]);
    $ajuste = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$ajuste) {
        header('Location: index.php');
        exit;
    }

    // Obtener lista de productos activos
    $sql_productos = "SELECT id, nombre FROM productos WHERE activo = 1";
    $productos_resultado = $con->query($sql_productos);
    $productos = $productos_resultado->fetchAll(PDO::FETCH_ASSOC);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $producto_id = $_POST['producto_id'];
        $tipo_ajuste = $_POST['tipo_ajuste'];
        $valor = $_POST['valor'];
        $tipo_valor = $_POST['tipo_valor']; // Nuevo campo para el tipo de valor (porcentaje o peso)
        $motivo = $_POST['motivo'];
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_fin = $_POST['fecha_fin'] ? $_POST['fecha_fin'] : null;

        // Validar datos
        if ($producto_id && $tipo_ajuste && $valor && $tipo_valor && $motivo && $fecha_inicio) {
            $sql_update = "UPDATE ajustes_precios 
                           SET producto_id = ?, tipo_ajuste = ?, valor = ?, tipo_valor = ?, motivo = ?, fecha_inicio = ?, fecha_fin = ? 
                           WHERE id = ?";
            $stmt_update = $con->prepare($sql_update);
            $stmt_update->execute([$producto_id, $tipo_ajuste, $valor, $tipo_valor, $motivo, $fecha_inicio, $fecha_fin, $ajuste_id]);

            // Redirigir después de actualizar el ajuste
            header('Location: index.php');
            exit;
        } else {
            $error = "Todos los campos son obligatorios.";
        }
    }
} else {
    header('Location: index.php');
    exit;
}

ob_end_flush(); // Finaliza el búfer de salida

?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3 text-center">Editar Ajuste de Precio</h2>

        <!-- Mensaje de error (si lo hay) -->
        <?php if (isset($error)) { ?>
            <div class="alert alert-danger"><?php echo htmlspecialchars($error, ENT_QUOTES); ?></div>
        <?php } ?>

        <div class="card shadow-sm p-4">
            <form method="POST" action="editar_ajuste.php?id=<?php echo $ajuste_id; ?>">
                <div class="row">
                    <!-- Producto -->
                    <div class="col-md-6 mb-3">
                        <label for="producto_id" class="form-label">Producto</label>
                        <select name="producto_id" id="producto_id" class="form-select" required>
                            <option value="">Seleccione un producto</option>
                            <?php foreach ($productos as $producto) { ?>
                                <option value="<?php echo $producto['id']; ?>" <?php echo ($producto['id'] == $ajuste['producto_id']) ? 'selected' : ''; ?>>
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
                            <option value="incremento" <?php echo ($ajuste['tipo_ajuste'] == 'incremento') ? 'selected' : ''; ?>>Incremento</option>
                            <option value="descuento" <?php echo ($ajuste['tipo_ajuste'] == 'descuento') ? 'selected' : ''; ?>>Descuento</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <!-- Valor del ajuste -->
                    <div class="col-md-6 mb-3">
                        <label for="valor" class="form-label">Valor del ajuste</label>
                        <input type="number" name="valor" id="valor" class="form-control" required min="0" step="0.01" value="<?php echo htmlspecialchars($ajuste['valor'] ?? '', ENT_QUOTES); ?>">
                    </div>

                    <!-- Tipo de valor -->
                    <div class="col-md-6 mb-3">
                        <label for="tipo_valor" class="form-label">Tipo de valor</label>
                        <select name="tipo_valor" id="tipo_valor" class="form-select" required>
                            <option value="porcentaje" <?php echo ($ajuste['tipo_valor'] == 'porcentaje') ? 'selected' : ''; ?>>Porcentaje</option>
                            <option value="peso" <?php echo ($ajuste['tipo_valor'] == 'peso') ? 'selected' : ''; ?>>Peso</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <!-- Motivo del ajuste -->
                    <div class="col-md-12 mb-3">
                        <label for="motivo" class="form-label">Motivo del ajuste</label>
                        <textarea name="motivo" id="motivo" class="form-control" required><?php echo htmlspecialchars($ajuste['motivo'] ?? '', ENT_QUOTES); ?></textarea>
                    </div>
                </div>

                <div class="row">
                    <!-- Fecha inicio -->
                    <div class="col-md-6 mb-3">
                        <label for="fecha_inicio" class="form-label">Fecha de inicio</label>
                        <input type="date" name="fecha_inicio" id="fecha_inicio" class="form-control" required value="<?php echo htmlspecialchars($ajuste['fecha_inicio'] ?? '', ENT_QUOTES); ?>">
                    </div>

                    <!-- Fecha fin -->
                    <div class="col-md-6 mb-3">
                        <label for="fecha_fin" class="form-label">Fecha de fin (opcional)</label>
                        <input type="date" name="fecha_fin" id="fecha_fin" class="form-control" value="<?php echo htmlspecialchars($ajuste['fecha_fin'] ?? '', ENT_QUOTES); ?>">
                    </div>
                </div>

                <div class="text-center">
                    <button type="submit" class="btn btn-primary px-4">Guardar cambios</button>
                    <a href="ajustes_precios.php" class="btn btn-secondary px-4">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</main>

<?php require '../footer.php'; ?>
