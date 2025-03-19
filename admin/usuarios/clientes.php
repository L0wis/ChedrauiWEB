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

$sql = "SELECT c.id, c.nombres, c.apellidos, c.email, c.dni, c.telefono, c.fecha_alta, 
               CASE c.estatus WHEN 1 THEN 'ACTIVO' ELSE 'DESACTIVADO' END as estatus, u.usuario 
        FROM clientes c
        INNER JOIN usuarios u ON c.id = u.id
        WHERE c.estatus = 1";
$resultado = $con->query($sql);
$clientes = $resultado->fetchAll(PDO::FETCH_ASSOC);

?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Clientes</h2>

        <div class="mb-3">

    <a href="reportePDF.php" class="btn btn-success ms-2">
        <i class="bi bi-file-earmark-pdf-fill"></i> Reporte Clientes PDF
    </a>
    <a href="reporteEXCEL.php" class="btn btn-success ms-2">
        <i class="bi bi-file-earmark-excel-fill"></i> Reporte Clientes Excel
    </a>
    <a href="desactivados_cliente.php" class="btn btn-danger ms-2">
        <i class="bi bi-person-x-fill"></i> Clientes Desactivados
    </a>
</div>


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
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clientes as $cliente) { ?>
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
                                <a href="editarcliente.php?id=<?php echo $cliente['id'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-fill"></i> Editar</a>
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalElimina" data-bs-id="<?php echo $cliente['id']; ?>"><i class="bi bi-trash3"></i></button>
                            </td>
                        </tr>
                   <?php } ?>
                </tbody>
            </table>
        </div>             
    </div>
</main>

<div class="modal fade" id="modalElimina" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false" role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">Confirmar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Desea eliminar el cliente?
            </div>
            <div class="modal-footer">
                <form action="eliminar_cliente.php" method="post">
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
    eliminaModal.addEventListener('show.bs.modal', function(event){
        let button = event.relatedTarget;
        let id = button.getAttribute('data-bs-id');
        let modalInput = eliminaModal.querySelector('.modal-footer input');
        modalInput.value = id;
    });
</script>

<?php require '../footer.php'; ?>
