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

$sql = "SELECT id, usuario, nombre, puesto, email, fecha_alta FROM personal WHERE activo = 0";
$resultado = $con->query($sql);
$personalDesactivado = $resultado->fetchAll(PDO::FETCH_ASSOC);

?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Personal Desactivado</h2>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Puesto</th>
                        <th scope="col">Email</th>
                        <th scope="col">Fecha de Alta</th>
                        <th scope="col">Accion</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($personalDesactivado)) { ?>
                        <tr>
                        <td colspan="6" class="text-center">Sin personal desactivado</td>
                        </tr>
                    <?php } else { ?>
                        <?php foreach ($personalDesactivado as $persona) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($persona['id'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($persona['usuario'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($persona['nombre'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($persona['puesto'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($persona['email'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($persona['fecha_alta'], ENT_QUOTES); ?></td>
                                <td>
                                    <form action="restaurar_personal.php" method="post">
                                        <input type="hidden" name="id" value="<?php echo $persona['id']; ?>">
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
