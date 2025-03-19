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

$sql = "SELECT id, nombre FROM categorias WHERE activo = 1";
$resultado = $con->query($sql);
$categorias = $resultado->fetchAll(PDO::FETCH_ASSOC);

?>
    
<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Categorías</h2>

        <!-- Botones de acción -->
        <div class="mb-3">
            <a href="nuevo.php" class="btn btn-primary me-2">
                <i class="bi bi-plus-square-fill"></i> Agregar categoría
            </a>
            <a href="categorias_desactivadas.php" class="btn btn-secondary">
                <i class="bi bi-slash-circle-fill"></i> Desactivadas
            </a>
        </div>

        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col">Id</th>
                        <th scope="col">Nombre</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($categorias as $categoria){ ?>
                        <tr>
                            <td><?php echo htmlspecialchars($categoria['id'], ENT_QUOTES); ?></td>
                            <td><?php echo htmlspecialchars($categoria['nombre'], ENT_QUOTES); ?></td>

                            <td>
                                <!-- Botón "Productos" -->
                                <a class="btn btn-success btn-sm" href="mostrar_productos.php?categoria_id=<?php echo $categoria['id']; ?>">
                                    <i class="bi bi-cart-check-fill"></i> Productos
                                </a>
                            </td>
                            <td>
                                <a class="btn btn-warning btn-sm" href="edita.php?id=<?php echo 
                                $categoria['id']; ?>">
                                    <i class="bi bi-pencil-fill"></i> Editar
                                </a>
                            </td>
                            <td>
                                <!-- Modal trigger button -->
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalElimina" data-bs-id="<?php echo $categoria['id']; ?>">
                                    <i class="bi bi-trash3"></i>
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        
    </div>
</main>

<!-- Modal para eliminar categoría -->
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
    let eliminaModal = document.getElementById('modalElimina')
    eliminaModal.addEventListener('show.bs.modal', function(event) {
        let button = event.relatedTarget
        let id = button.getAttribute('data-bs-id')

        let modalInput = eliminaModal.querySelector('.modal-footer input')
        modalInput.value = id
    });
</script>

<?php require '../footer.php'; ?>
