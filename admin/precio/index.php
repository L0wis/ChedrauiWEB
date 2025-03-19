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

// Obtener productos
$sql = "SELECT p.id, p.nombre, p.precio, p.stock, c.nombre AS nombre_categoria 
        FROM productos p
        INNER JOIN categorias c ON p.id_categoria = c.id
        WHERE p.activo = :activo";
$stmt = $con->prepare($sql);
$stmt->execute([':activo' => 1]);
$productos = $stmt->fetchAll(PDO::FETCH_ASSOC);


// Obtener ajustes de precios activos
$sql_ajustes = "SELECT a.id, 
                       p.nombre AS producto, 
                       a.tipo_ajuste, 
                       a.valor, 
                       a.motivo, 
                       a.fecha_inicio, 
                       a.fecha_fin, 
                       a.tipo_valor, 
                       a.activo
                FROM ajustes_precios a
                INNER JOIN productos p ON a.producto_id = p.id
                WHERE a.activo = 1"; // Filtrar solo ajustes activos
$resultado_ajustes = $con->query($sql_ajustes);
$ajustes_precios = $resultado_ajustes->fetchAll(PDO::FETCH_ASSOC);

?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Ajustes de Precios de Productos Clientes</h2>

        <!-- Botón para agregar nuevo ajuste de precio -->
        <a href="nuevo_ajuste.php" class="btn btn-primary mb-3"><i class="bi bi-plus-circle-fill"></i> Agregar nuevo
            ajuste</a>

        <!-- Botón para ver el historial de precios -->
        <a href="historial_precios.php" class="btn btn-secondary mb-3"><i class="bi bi-clock-history"></i> Historial de
            precios</a>

        <?php if (isset($_GET['status'])): ?>
            <?php if ($_GET['status'] == 'success'): ?>
                <div class="alert alert-success">Ajuste eliminado y precio restaurado correctamente.</div>
            <?php elseif ($_GET['status'] == 'error'): ?>
                <div class="alert alert-danger">Ocurrió un error al intentar eliminar el ajuste.</div>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Tabla de ajustes de precios -->
        <div class="table-responsive mt-3">
            <table class="table table-hover" id="tablaAjustes">
                <thead>
                    <tr>
                        <th scope="col">Producto</th>
                        <th scope="col">Tipo de ajuste</th>
                        <th scope="col">Valor</th>
                        <th scope="col">Motivo</th>
                        <th scope="col">Fecha inicio</th>
                        <th scope="col">Fecha fin</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($ajustes_precios as $ajuste) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($ajuste['producto'], ENT_QUOTES); ?></td>
                            <td><?php echo htmlspecialchars($ajuste['tipo_ajuste'], ENT_QUOTES); ?></td>
                            <td>
                                <?php
                                if ($ajuste['tipo_valor'] == 'porcentaje') {
                                    echo $ajuste['valor'] . '%';
                                } else {
                                    echo '$' . number_format($ajuste['valor'], 2);
                                }
                                ?>
                            </td>
                            <td><?php echo htmlspecialchars($ajuste['motivo'], ENT_QUOTES); ?></td>
                            <td><?php echo $ajuste['fecha_inicio']; ?></td>
                            <td><?php echo $ajuste['fecha_fin'] ? $ajuste['fecha_fin'] : 'Indefinido'; ?></td>
                            <td>
                                <a href="editar_ajuste.php?id=<?php echo $ajuste['id']; ?>"
                                    class="btn btn-warning btn-sm"><i class="bi bi-pencil-fill"></i> Editar</a>
                            </td>
                            <td>
                                <!-- Modal trigger button -->
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modalEliminar" data-bs-id="<?php echo $ajuste['id']; ?>"><i
                                        class="bi bi-trash3"></i> Eliminar</button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- Modal para eliminar ajuste de precio -->
<div class="modal fade" id="modalEliminar" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false"
    role="dialog" aria-labelledby="modalTitleId" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-sm" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitleId">Confirmar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                ¿Desea eliminar este ajuste de precio?
            </div>
            <div class="modal-footer">
                <form action="eliminar_ajuste.php" method="post">
                    <input type="hidden" name="id">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-danger">Eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    let eliminaModal = document.getElementById('modalEliminar')
    eliminaModal.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget
        let id = button.getAttribute('data-bs-id')

        let modalInput = eliminaModal.querySelector('.modal-footer input')
        modalInput.value = id
    });
</script>

<?php require '../footer.php'; ?>