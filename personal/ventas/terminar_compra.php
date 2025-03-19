<?php
// Incluir archivo de configuración y encabezado
require '../config/config.php';
require '../header.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

// Obtener nombre de usuario
$nombre_usuario = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "Usuario";

// Obtener el último ID de compra_personal
$db = new Database();
$con = $db->conectar();
$sql = $con->query("SELECT MAX(id) AS ultimo_id FROM compra_personal");
$ultimo_id = $sql->fetch(PDO::FETCH_ASSOC)['ultimo_id'];
?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Terminar Compra</h2>

        <!-- Formulario para terminar la compra -->
        <form action="procesar_compra.php" method="post" onsubmit="return validarCantidad()">
            <!-- Campo oculto para enviar el id_venta -->
            <input type="hidden" name="id" value="<?php echo $ultimo_id; ?>">

            <!-- Contenido dinámico dentro del bucle -->
            <?php for ($i = 1; $i <= 5; $i++) { ?>
                <div class="mb-3">
                    <label for="producto<?php echo $i; ?>" class="form-label">Elegir Producto <?php echo $i; ?></label>
                    <select class="form-select" id="producto<?php echo $i; ?>" name="productos[]" onchange="verificarStock(<?php echo $i; ?>)">
                        <option value="">Seleccione un producto...</option>
                        <?php
                        // Consulta SQL para obtener todos los productos ordenados alfabéticamente por nombre
                        $sql = $con->query("SELECT id, nombre, stock FROM productos ORDER BY nombre ASC");
                        
                        // Iterar sobre los resultados y crear las opciones del select
                        while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {
                            echo '<option value="' . $row['id'] . '" data-stock="' . $row['stock'] . '">' . $row['nombre'] . '</option>';
                        }
                        ?>
                    </select>
                    <label for="cantidad<?php echo $i; ?>" class="form-label">Cantidad</label>
                    <input type="number" class="form-control" id="cantidad<?php echo $i; ?>" name="cantidades[]" value="1" min="1">
                    <span id="mensajeCantidad<?php echo $i; ?>" style="color: red;"></span>
                </div>
            <?php } ?>
            
            <!-- Campo oculto para enviar los IDs de los productos -->
            <input type="hidden" id="ids_productos" name="ids_productos" value="">

            <button type="submit" class="btn btn-primary">Procesar Compra</button>
        </form>
    </div>
</main>

<script>
    function verificarStock(numeroProducto) {
        var producto = document.getElementById('producto' + numeroProducto);
        var cantidad = document.getElementById('cantidad' + numeroProducto).value;
        var stock = producto.options[producto.selectedIndex].getAttribute('data-stock');

        if (parseInt(cantidad) > parseInt(stock)) {
            document.getElementById('mensajeCantidad' + numeroProducto).innerText = 'No hay suficiente stock';
            return false;
        } else {
            document.getElementById('mensajeCantidad' + numeroProducto).innerText = '';
            return true;
        }
    }

    function validarCantidad() {
        // Verificar cada producto antes de enviar el formulario
        for (var i = 1; i <= 5; i++) {
            if (!verificarStock(i)) return false;
        }
        
        // Recopilar los IDs de los productos seleccionados y asignarlos al campo oculto
        var productosSeleccionados = [];
        for (var i = 1; i <= 5; i++) {
            var producto = document.getElementById('producto' + i);
            if (producto.value !== '') {
                productosSeleccionados.push(producto.value);
            }
        }
        document.getElementById('ids_productos').value = productosSeleccionados.join(',');
        
        return true;
    }
</script>

<?php require '../footer.php'; ?>
