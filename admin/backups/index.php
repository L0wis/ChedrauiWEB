<?php

require '../config/database.php';
require '../config/config.php';
require '../header.php';

if (!isset($_SESSION['user_type'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SESSION['user_type'] != 'admin' ){
    header('Location: ../../index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

$orden = isset($_GET['orden']) ? $_GET['orden'] : 'recientes';
$order_by = $orden == 'antiguos' ? 'ASC' : 'DESC';

try {
    $sql = "SELECT * FROM backups WHERE activo = 1 ORDER BY fecha_hora $order_by";
    $resultado = $con->query($sql);
    if ($resultado) {
        $respaldos = $resultado->fetchAll(PDO::FETCH_ASSOC);
    } else {
        $respaldos = [];
    }
} catch (PDOException $e) {
    echo "Error en la consulta: " . $e->getMessage();
    $respaldos = [];
}

?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Lista de Respaldos de Base de Datos</h2>

        <div class="d-flex justify-content-between align-items-center mt-3">
            <a href="crear_respaldo_bd.php" class="btn btn-warning"><i class="fas fa-save"></i> Crear Respaldo de BD</a>
            <form method="GET" id="orderForm" class="d-flex align-items-center">
                <label for="orden" class="form-label me-2 mb-0">Ordenar por fecha:</label>
                <select name="orden" id="orden" class="form-select" onchange="document.getElementById('orderForm').submit();">
                    <option value="recientes" <?php echo isset($_GET['orden']) && $_GET['orden'] == 'recientes' ? 'selected' : ''; ?>>Más recientes</option>
                    <option value="antiguos" <?php echo isset($_GET['orden']) && $_GET['orden'] == 'antiguos' ? 'selected' : ''; ?>>Más antiguos</option>
                </select>
            </form>
        </div>

        <div class="table-responsive mt-3">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Fecha y Hora</th>
                        <th scope="col">Nombre Archivo</th>
                        <th scope="col">Ubicación</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Comentarios</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($respaldos)) { ?>
                        <tr>
                            <td colspan="9" class="text-center">No se ha hecho ningún respaldo</td>
                        </tr>
                    <?php } else { ?>
                        <?php foreach ($respaldos as $respaldo) { ?>
                            <tr>
                                <td><?php echo isset($respaldo['id']) ? htmlspecialchars($respaldo['id']) : ''; ?></td>
                                <td><?php echo isset($respaldo['fecha_hora']) ? htmlspecialchars($respaldo['fecha_hora']) : ''; ?></td>
                                <td><?php echo isset($respaldo['nombre_archivo']) ? htmlspecialchars($respaldo['nombre_archivo']) : ''; ?></td>
                                <td><?php echo isset($respaldo['ubicacion']) ? htmlspecialchars($respaldo['ubicacion']) : ''; ?></td>
                                <td><?php echo isset($respaldo['usuario']) ? htmlspecialchars($respaldo['usuario']) : ''; ?></td>
                                <td><?php echo isset($respaldo['descripcion']) ? htmlspecialchars($respaldo['descripcion']) : ''; ?></td>
                                <td><?php echo isset($respaldo['comentarios']) ? htmlspecialchars($respaldo['comentarios']) : ''; ?></td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>             
    </div>
</main>

<?php require '../footer.php'; ?>
