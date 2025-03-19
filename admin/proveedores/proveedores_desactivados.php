<?php
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

$sql = "SELECT id, nombre, inicio_contrato, fin_contrato, ciudad, telefono,
               DATE_FORMAT(inicio_contrato, '%d-%m-%Y') AS inicio_contrato_format,
               DATE_FORMAT(fin_contrato, '%d-%m-%Y') AS fin_contrato_format
        FROM proveedores 
        WHERE activo = 0
        ORDER BY fin_contrato DESC"; // Ordenar por fecha de inicio de contrato en orden descendente
$resultado = $con->query($sql);
$proveedoresDesactivados = $resultado->fetchAll(PDO::FETCH_ASSOC);
?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Proveedores Desactivados</h2>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Inicio de Contrato</th>
                        <th scope="col">Fin de Contrato</th>
                        <th scope="col">Ciudad</th>
                        <th scope="col">Telefono</th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($proveedoresDesactivados)) { ?>
                        <tr>
                            <td colspan="6" class="text-center"><b>No hay proveedores desactivados</b></td>
                        </tr>
                    <?php } else { ?>
                        <?php foreach ($proveedoresDesactivados as $proveedor) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($proveedor['nombre'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($proveedor['inicio_contrato_format'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($proveedor['fin_contrato_format'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($proveedor['ciudad'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($proveedor['telefono'], ENT_QUOTES); ?></td>
                                <td>
                                    <!-- Agrega aquí cualquier acción adicional para restaurar el proveedor -->
                                    <a href="restaurar.php?id=<?php echo $proveedor['id'] ?>" class="btn btn-success btn-sm">Restaurar</a>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- ... (código posterior) ... -->

<?php require '../footer.php'; ?>
