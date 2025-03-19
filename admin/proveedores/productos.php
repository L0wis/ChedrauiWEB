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

// Obtener lista de productos
$sql = "SELECT id, nombre, precio, id_proveedor FROM productos WHERE activo = 1";
$resultado = $con->query($sql);
$productos = $resultado->fetchAll(PDO::FETCH_ASSOC);

// Obtener lista de proveedores
$sql_proveedores = "SELECT id, nombre FROM proveedores WHERE activo = 1";
$resultado_proveedores = $con->query($sql_proveedores);
$proveedores = $resultado_proveedores->fetchAll(PDO::FETCH_ASSOC);

// Crear un array de ID -> Nombre de proveedores
$proveedores_map = [];
foreach ($proveedores as $proveedor) {
    $proveedores_map[$proveedor['id']] = $proveedor['nombre'];
}

// Función para convertir IDs a nombres
function convertirProveedores($ids, $map)
{
    if (empty($ids)) {
        return "Sin proveedor"; // Retorna este mensaje si no hay IDs
    }

    $ids_array = explode(',', $ids);
    $nombres = [];
    foreach ($ids_array as $id) {
        if (isset($map[$id])) {
            $nombres[] = $map[$id];
        }
    }

    return empty($nombres) ? "Sin proveedor" : implode(', ', $nombres);
}

?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Gestión de Productos</h2>
        <div class="d-flex justify-content-between align-items-center mb-3">
            <button id="btnAsignarMasa" class="btn btn-warning btn-sm">
                <i class="bi bi-check-square"></i> Asignar en Masa
            </button>
            <div class="d-flex align-items-center">
                <input type="text" id="buscarProducto" class="form-control form-control-sm"
                    placeholder="Buscar producto..." style="max-width: 300px;">
            </div>
            <form id="formAsignarMasa" method="POST" action="asignar_masa.php" style="display: none;">
                <input type="hidden" name="productos_seleccionados" id="productosSeleccionados">
                <div class="d-flex align-items-center">
                    <select name="proveedor" class="form-select form-select-sm me-2" required>
                        <option value="" disabled selected>Selecciona un proveedor</option>
                        <?php foreach ($proveedores as $proveedor) { ?>
                            <option value="<?php echo $proveedor['id']; ?>">
                                <?php echo htmlspecialchars($proveedor['nombre'], ENT_QUOTES); ?>
                            </option>
                        <?php } ?>
                    </select>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="bi bi-box-arrow-in-right"></i> Asignar a Proveedor
                    </button>
                </div>
            </form>
        </div>

        <script>
            document.getElementById('buscarProducto').addEventListener('input', function () {
                const searchTerm = this.value.toLowerCase();
                const filasProductos = document.querySelectorAll('tbody tr');

                filasProductos.forEach(fila => {
                    const nombreProducto = fila.querySelector('td:nth-child(2)').textContent.toLowerCase();
                    if (nombreProducto.includes(searchTerm)) {
                        fila.style.display = ''; // Mostrar fila si coincide
                    } else {
                        fila.style.display = 'none'; // Ocultar fila si no coincide
                    }
                });
            });
        </script>


        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th scope="col" id="headerCheckbox" style="display: none;">
                            <input type="checkbox" id="selectTodos">
                        </th>
                        <th scope="col">Nombre</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Proveedores Actuales</th>
                        <th scope="col" id="headerAcciones">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($productos as $producto) { ?>
                        <tr>
                            <td class="checkbox-column" style="display: none;">
                                <input type="checkbox" class="checkboxProducto" value="<?php echo $producto['id']; ?>">
                            </td>
                            <td><?php echo htmlspecialchars($producto['nombre'], ENT_QUOTES); ?></td>
                            <td><?php echo number_format($producto['precio'], 2); ?> MXN</td>
                            <td>
                                <?php
                                echo convertirProveedores($producto['id_proveedor'], $proveedores_map);
                                ?>
                            </td>
                            <td class="acciones-column">
                                <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal"
                                    data-bs-target="#modalAsociar" data-producto-id="<?php echo $producto['id']; ?>"
                                    data-proveedores-actuales="<?php echo $producto['id_proveedor']; ?>">
                                    <i class="bi bi-box-arrow-in-right"></i> Asociar Proveedores
                                </button>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<script>
    const btnAsignarMasa = document.getElementById('btnAsignarMasa');
    const formAsignarMasa = document.getElementById('formAsignarMasa');
    const checkboxesColumn = document.querySelectorAll('.checkbox-column');
    const accionesColumn = document.querySelectorAll('.acciones-column');
    const headerCheckbox = document.getElementById('headerCheckbox');
    const headerAcciones = document.getElementById('headerAcciones');
    const selectTodos = document.getElementById('selectTodos');
    const productosSeleccionadosInput = document.getElementById('productosSeleccionados');

    let asignarEnMasaActivo = false; // Variable para controlar el estado

    btnAsignarMasa.addEventListener('click', () => {
        if (asignarEnMasaActivo) {
            // Regresar al estado inicial
            checkboxesColumn.forEach(col => col.style.display = 'none');
            accionesColumn.forEach(col => col.style.display = 'table-cell');
            headerCheckbox.style.display = 'none';
            headerAcciones.style.display = 'table-cell';
            formAsignarMasa.style.display = 'none';
            btnAsignarMasa.textContent = 'Asignar en Masa'; // Cambiar texto del botón
            btnAsignarMasa.classList.remove('btn-danger'); // Cambiar color a original
            btnAsignarMasa.classList.add('btn-warning');
        } else {
            // Cambiar al modo de asignación en masa
            checkboxesColumn.forEach(col => col.style.display = 'table-cell');
            accionesColumn.forEach(col => col.style.display = 'none');
            headerCheckbox.style.display = 'table-cell';
            headerAcciones.style.display = 'none';
            formAsignarMasa.style.display = 'flex';
            btnAsignarMasa.textContent = 'Cancelar'; // Cambiar texto del botón
            btnAsignarMasa.classList.remove('btn-warning'); // Cambiar color a cancelar
            btnAsignarMasa.classList.add('btn-danger');
        }
        asignarEnMasaActivo = !asignarEnMasaActivo; // Alternar estado
    });

    selectTodos.addEventListener('change', () => {
        const checkboxes = document.querySelectorAll('.checkboxProducto');
        checkboxes.forEach(checkbox => {
            checkbox.checked = selectTodos.checked;
        });
    });

    formAsignarMasa.addEventListener('submit', (e) => {
        e.preventDefault();

        const selectedCheckboxes = Array.from(document.querySelectorAll('.checkboxProducto:checked'));
        if (selectedCheckboxes.length === 0) {
            alert('Por favor selecciona al menos un producto.');
            return;
        }

        const selectedIds = selectedCheckboxes.map(checkbox => checkbox.value);
        productosSeleccionadosInput.value = selectedIds.join(',');

        formAsignarMasa.submit();
    });
</script>

<!-- Modal para asociar proveedores -->
<div class="modal fade" id="modalAsociar" tabindex="-1" aria-labelledby="modalAsociarLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalAsociarLabel">Asociar Proveedores</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formAsociar" method="POST" action="asociar_proveedor.php">
                <div class="modal-body">
                    <input type="hidden" name="producto_id" id="productoId">
                    <p>Selecciona los proveedores que suministran este producto:</p>
                    <?php foreach ($proveedores as $proveedor) { ?>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="proveedores[]"
                                value="<?php echo $proveedor['id']; ?>" id="proveedor_<?php echo $proveedor['id']; ?>">
                            <label class="form-check-label" for="proveedor_<?php echo $proveedor['id']; ?>">
                                <?php echo htmlspecialchars($proveedor['nombre'], ENT_QUOTES); ?>
                            </label>
                        </div>
                    <?php } ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    let modalAsociar = document.getElementById('modalAsociar');
    modalAsociar.addEventListener('show.bs.modal', function (event) {
        let button = event.relatedTarget;
        let productoId = button.getAttribute('data-producto-id');
        let proveedoresActuales = button.getAttribute('data-proveedores-actuales');

        // Asignar ID del producto al formulario
        document.getElementById('productoId').value = productoId;

        // Marcar los checkboxes correspondientes
        let checkboxes = modalAsociar.querySelectorAll('.form-check-input');
        checkboxes.forEach(checkbox => {
            checkbox.checked = proveedoresActuales.split(',').includes(checkbox.value);
        });
    });
</script>

<?php require '../footer.php'; ?>