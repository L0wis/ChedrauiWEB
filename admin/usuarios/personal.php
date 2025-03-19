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

$sql = "SELECT id, usuario, nombre, email, puesto, fecha_alta FROM personal WHERE activo = 1";
$resultado = $con->query($sql);
$personal = $resultado->fetchAll(PDO::FETCH_ASSOC);

?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Personal</h2>
        <a href="agregarpersonal.php" class="btn btn-info"><i class="fas fa-user-plus"></i> Agregar Personal</a>
        <a href="generarPDF.php" class="btn btn-primary" onclick="generarInformePDF()"><i class="fas fa-file-pdf"></i>
            Descargar Reporte Personal PDF</a>
        <a href="generarExcel.php" class="btn btn-success" onclick="generarInformeExcel()"><i
                class="fas fa-file-excel"></i> Descargar Reporte Personal Excel</a>
        <a href="personal_desactivado.php" class="btn btn-danger btn-sm ms-2"><i class="bi bi-person-dash-fill"></i>
            Personal Desactivado</a>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">ID</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Email</th>
                        <th scope="col">Puesto</th>
                        <th scope="col">Fecha de Alta</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($personal as $persona) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($persona['id'], ENT_QUOTES); ?></td>
                            <td><?php echo htmlspecialchars($persona['usuario'], ENT_QUOTES); ?></td>
                            <td><?php echo htmlspecialchars($persona['nombre'], ENT_QUOTES); ?></td>
                            <td><?php echo htmlspecialchars($persona['email'], ENT_QUOTES); ?></td>
                            <td><?php echo htmlspecialchars($persona['puesto'], ENT_QUOTES); ?></td>
                            <td><?php echo htmlspecialchars($persona['fecha_alta'], ENT_QUOTES); ?></td>
                            <td>
                                <a href="editarpersonal.php?id=<?php echo $persona['id'] ?>"
                                    class="btn btn-warning btn-sm"><i class="bi bi-pencil-fill"></i> Editar</a>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modalElimina" data-bs-id="<?php echo $persona['id']; ?>"><i
                                        class="bi bi-trash3"></i></button>
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
                ¿Desea eliminar al personal?
            </div>
            <div class="modal-footer">
                <form action="eliminarpersonal.php" method="post">
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