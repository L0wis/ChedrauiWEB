<?php

ob_start();

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

$sql = "SELECT c.id, c.nombres, c.apellidos, c.email, c.dni, c.telefono, c.fecha_alta, 
               CASE c.estatus WHEN 1 THEN 'ACTIVO' ELSE 'DESACTIVADO' END as estatus, u.usuario 
        FROM clientes c
        INNER JOIN usuarios u ON c.id = u.id
        WHERE c.estatus = 0 AND u.activacion = 0";
$resultado = $con->query($sql);
$clientesDesactivados = $resultado->fetchAll(PDO::FETCH_ASSOC);

?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Clientes Desactivados</h2>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Apellidos</th>
                        <th scope="col">Email</th>
                        <th scope="col">DNI</th>
                        <th scope="col">Teléfono</th>
                        <th scope="col">Fecha de Alta</th>
                        <th scope="col">Estatus</th>
                        <th scope="col">Acción</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($clientesDesactivados)) { ?>
                        <tr>
                            <td colspan="10" class="text-center">Sin clientes desactivados</td>
                        </tr>
                    <?php } else { ?>
                        <?php foreach ($clientesDesactivados as $cliente) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($cliente['id'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($cliente['usuario'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($cliente['nombres'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($cliente['apellidos'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($cliente['email'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($cliente['dni'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($cliente['telefono'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($cliente['fecha_alta'], ENT_QUOTES); ?></td>
                                <td><?php echo htmlspecialchars($cliente['estatus'], ENT_QUOTES); ?></td>
                                <td>
                                    <form action="activar_cliente.php" method="post">
                                        <input type="hidden" name="id" value="<?php echo $cliente['id']; ?>">
                                        <button type="submit" class="btn btn-success btn-sm">Activar</button>
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
