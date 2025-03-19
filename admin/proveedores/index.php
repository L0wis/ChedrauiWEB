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

$sql = "SELECT id, nombre, direccion, ciudad, telefono 
        FROM proveedores 
        WHERE activo = 1";

$resultado = $con->query($sql);
$proveedores = $resultado->fetchAll(PDO::FETCH_ASSOC);

?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Lista de Proveedores</h2>

        <a href="nuevo.php" class="btn btn-primary"><i class="bi bi-cart-plus-fill"></i> Nuevo Proveedor</a>
        <a href="descargar_reporte.php" class="btn btn-success"><i class="bi bi-filetype-pdf"></i> Descargar reporte Proveedores PDF</a>
        <a href="descargar_reporte_excel.php" class="btn btn-primary"><i class="bi bi-file-spreadsheet-fill"></i> Descargar Reporte de Proveedores Excel</a>
        <a href="proveedores_desactivados.php" class="btn btn-danger"><i class="bi bi-cart-x-fill"></i> Proveedor terminado</a>
        <a href="productos.php" class="btn btn-info"><i class="bi bi-box-seam"></i> Productos</a> <!-- Botón añadido -->

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Direccion</th>
                        <th scope="col">Ciudad</th>
                        <th scope="col">Telefono</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($proveedores as $proveedor) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($proveedor['nombre'], ENT_QUOTES); ?></td>
                            <td><?php echo wordwrap(htmlspecialchars($proveedor['direccion'], ENT_QUOTES), 20, "<br>", true); ?></td>
                            <td><?php echo htmlspecialchars($proveedor['ciudad'], ENT_QUOTES); ?></td>
                            <td><?php echo htmlspecialchars($proveedor['telefono'], ENT_QUOTES); ?></td>
                            <td>
                                <a href="vermas.php?id=<?php echo $proveedor['id'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-fill"></i> Ver mas</a>
                            </td>
                            <td>
                                <!-- Modal trigger button -->
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalElimina" data-bs-id="<?php echo $proveedor['id']; ?>"><i class="bi bi-trash3"></i>
                                </button>
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
                ¿Desea desactivar el registro?
            </div>
            <div class="modal-footer">
                <form action="elimina.php" method="post">

                <input type="hidden" name="id">

                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-danger">Desactivar</button>
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
