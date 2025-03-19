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

$sql = "SELECT id, usuario, nombre, email, fecha_alta
        FROM admin
        WHERE activo = 1";

$resultado = $con->query($sql);
$administradores = $resultado->fetchAll(PDO::FETCH_ASSOC);

?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Administradores</h2>

        <a href="agregar_administrador.php" class="btn btn-info"><i class="fas fa-user-plus"></i> Agregar
            Administrador</a>
        <a href="generarPDF_admin.php" class="btn btn-primary" onclick="generarInformePDFAdmin()"><i
                class="fas fa-file-pdf"></i> Descargar Reporte Administradores PDF</a>
        <a href="generarExcel_admin.php" class="btn btn-success" onclick="generarInformeExcelAdmin()"><i
                class="fas fa-file-excel"></i> Descargar Reporte Administradores Excel</a>
        <a href="admin_desactivados.php" class="btn btn-warning"><i class="fas fa-user-times"></i> Administradores
            Desactivados</a>


        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Email</th>
                        <th scope="col">Fecha de Alta</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($administradores as $admin) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($admin['id'], ENT_QUOTES); ?></td>
                            <td><?php echo htmlspecialchars($admin['usuario'], ENT_QUOTES); ?></td>
                            <td><?php echo htmlspecialchars($admin['nombre'], ENT_QUOTES); ?></td>
                            <td><?php echo htmlspecialchars($admin['email'], ENT_QUOTES); ?></td>
                            <td><?php echo htmlspecialchars($admin['fecha_alta'], ENT_QUOTES); ?></td>
                            <td>
                                <a href="editar_administrador.php?id=<?php echo $admin['id'] ?>"
                                    class="btn btn-warning btn-sm"><i class="bi bi-pencil-fill"></i> Editar</a>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modalElimina" data-bs-id="<?php echo $admin['id']; ?>"><i
                                        class="bi bi-trash3"></i>
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<div class="modal fade" id="modalElimina" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog"
    aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">Confirmar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Desea eliminar el administrador?
            </div>
            <div class="modal-footer">
                <form action="eliminar_administrador.php" method="post">
                    <input type="hidden" name="id">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let eliminaModal = document.getElementById('modalElimina');
    eliminaModal.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;
        let id = button.getAttribute('data-bs-id');
        let modalInput = eliminaModal.querySelector('.modal-footer input');
        modalInput.value = id;
    });
</script>

<?php require '../footer.php'; ?>