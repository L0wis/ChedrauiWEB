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

$sql = "SELECT p.id, p.nombre, p.descripcion, p.precio, p.descuento, p.stock, p.id_categoria, c.nombre as nombre_categoria 
        FROM productos p
        INNER JOIN categorias c ON p.id_categoria = c.id
        WHERE p.activo = 1";
$resultado = $con->query($sql);
$productos = $resultado->fetchAll(PDO::FETCH_ASSOC);

// Crear una lista de productos con stock bajo
$productos_stock_bajo = array();
foreach ($productos as $producto) {
    if ($producto['stock'] < 50) {
        $productos_stock_bajo[] = $producto;
    }
}

?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Inventario de Productos</h2>

        <!-- Botones de acción -->
        <a href="nuevo.php" class="btn btn-primary"><i class="bi bi-cart-plus-fill"></i> Agregar nuevo producto</a>
        <a href="descargar_reporte.php" class="btn btn-success"><i class="bi bi-filetype-pdf"></i> Descargar reporte productos PDF</a>
        <a href="descargar_reporte_excel.php" class="btn btn-primary"><i class="bi bi-file-spreadsheet-fill"></i> Descargar Reporte de Productos Excel</a>
        <a href="productos_desactivados.php" class="btn btn-danger"><i class="bi bi-cart-x-fill"></i> Productos Desactivados</a>

        <!-- Buscador en tiempo real -->
        <div class="mt-3">
            <input type="text" id="buscador" class="form-control" placeholder="Buscar producto por nombre, categoría o stock...">
        </div>

        <!-- Tabla de productos -->
        <div class="table-responsive mt-3">
            <table class="table table-hover" id="tablaProductos">
                <thead>
                    <tr>
                        <th scope="col">Nombre</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Stock</th>
                        <th scope="col">Categoría</th>
                        <th scope="col"></th>
                        <th scope="col"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($producto['nombre'], ENT_QUOTES); ?></td>
                            <td><?php echo $producto['precio']; ?></td>
                            <td><?php echo $producto['stock']; ?></td>
                            <td><?php echo htmlspecialchars($producto['nombre_categoria'], ENT_QUOTES); ?></td>
                            <td>
                                <a href="edita.php?id=<?php echo $producto['id'] ?>" class="btn btn-warning btn-sm"><i class="bi bi-pencil-fill"></i> Editar</a>
                            </td>
                            <td>
                                <!-- Modal trigger button -->
                                <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalElimina" data-bs-id="<?php echo $producto['id']; ?>"><i class="bi bi-trash3"></i>
                                </button>
                            </td>
                        </tr>
                   <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- JavaScript para el buscador en tiempo real -->
<script>
    document.getElementById('buscador').addEventListener('input', function() {
        const filtro = this.value.toLowerCase();
        const filas = document.querySelectorAll('#tablaProductos tbody tr');

        filas.forEach(fila => {
            const textoFila = fila.textContent.toLowerCase();
            if (textoFila.includes(filtro)) {
                fila.style.display = '';
            } else {
                fila.style.display = 'none';
            }
        });
    });
</script>

<!-- Modal para mostrar productos con stock bajo -->
<div class="modal fade" id="modalAlertaStockBajo" tabindex="-1" role="dialog" aria-labelledby="modalAlertaStockBajoTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAlertaStockBajoTitle">Alerta: Stock Bajo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Los siguientes productos tienen un stock menor a 50:</p>
                <ul>
                    <?php foreach ($productos_stock_bajo as $producto_bajo) { ?>
                        <li><?php echo htmlspecialchars($producto_bajo['nombre'], ENT_QUOTES); ?> - Stock: <?php echo $producto_bajo['stock']; ?></li>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Mostrar modal si hay productos con stock bajo -->
<?php if (!empty($productos_stock_bajo)) { ?>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var modalAlertaStockBajo = new bootstrap.Modal(document.getElementById('modalAlertaStockBajo'));
            modalAlertaStockBajo.show();
        });
    </script>
<?php } ?>

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
