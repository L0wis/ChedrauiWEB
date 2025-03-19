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

$sql = "SELECT usuario, nombre, email, fecha_alta
        FROM admin
        WHERE activo = 0"; // Filtrar administradores desactivados
$resultado = $con->query($sql);
$adminsDesactivados = $resultado->fetchAll(PDO::FETCH_ASSOC);

?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Administradores Desactivados</h2>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Usuario</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Email</th>
                        <th scope="col">Fecha de Alta</th>
                        <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($adminsDesactivados)) { ?>
                        <tr>
                            <td colspan="5" class="text-center">No hay administradores desactivados</td>
                        </tr>
                    <?php } else { ?>
                        <?php foreach ($adminsDesactivados as $admin) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($admin['usuario'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($admin['nombre'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($admin['email'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($admin['fecha_alta'], ENT_QUOTES); ?></td>
                                <td>
                                    <form action="restaurar_admin.php" method="post">
                                        <input type="hidden" name="usuario" value="<?php echo $admin['usuario']; ?>">
                                        <button type="submit" class="btn btn-success btn-sm">Restaurar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require '../footer.php'; ?>
