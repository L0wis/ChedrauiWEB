<?php
// Incluir archivo de configuración y encabezado
require '../config/config.php';
require '../header.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

// Crear una instancia de la clase Database
$db = new Database();

// Obtener la conexión a la base de datos
$con = $db->conectar();

// Verificar si la conexión se estableció correctamente
if (!$con) {
    // Manejar el error de conexión
    echo "Error de conexión a la base de datos.";
    exit;
}

// Variable para almacenar los resultados de la búsqueda
$resultados = [];

// Verificar si se ha enviado un término de búsqueda
if (isset($_GET['buscar'])) {
    $buscar = $_GET['buscar'];
    // Realizar la consulta SQL para buscar clientes por nombre
    $sql = "SELECT id, nombres FROM clientes WHERE nombres LIKE :buscar";
    // Preparar la consulta
    $stmt = $con->prepare($sql);
    // Bind de parámetros
    $stmt->bindValue(':buscar', '%' . $buscar . '%', PDO::PARAM_STR);
    // Ejecutar la consulta
    $stmt->execute();
    // Obtener resultados como un array asociativo
    $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Buscar Cliente</h2>

        <!-- Formulario para buscar cliente -->
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="get">
            <div class="mb-3">
                <label for="buscar" class="form-label">Buscar Cliente por Nombre</label>
                <input type="text" class="form-control" id="buscar" name="buscar" required>
            </div>

            <button type="submit" class="btn btn-primary">Buscar</button>
        </form>

        <!-- Resultados de la búsqueda -->
        <?php if (!empty($resultados)) { ?>
            <div class="mt-3">
                <h3>Resultados de la Búsqueda</h3>
                <!-- Formulario para seleccionar un cliente -->
                <form action="crear_venta.php" method="get">
                    <input type="hidden" name="id_cliente" id="id_cliente_seleccionado" value="">
                    <ul>
                        <?php foreach ($resultados as $cliente) { ?>
                            <li>
                                <!-- Mostrar el nombre del cliente y un botón "Seleccionar" -->
                                <?php echo htmlspecialchars($cliente['nombres'], ENT_QUOTES); ?>
                                <button type="submit" class="btn btn-outline-secondary btn-seleccionar" name="id_cliente" value="<?php echo $cliente['id']; ?>">Seleccionar</button>
                            </li>
                        <?php } ?>
                    </ul>
                </form>
            </div>
        <?php } else if (isset($_GET['buscar'])) { ?>
            <p>No se encontraron clientes con el término de búsqueda "<?php echo htmlspecialchars($_GET['buscar'], ENT_QUOTES); ?>".</p>
        <?php } ?>
    </div>
</main>

<?php require '../footer.php'; ?>

<script>
    // Script para manejar la selección de un cliente y habilitar el botón de guardar
    document.addEventListener('DOMContentLoaded', function() {
        const btnSeleccionar = document.querySelectorAll('.btn-seleccionar');
        const idClienteSeleccionado = document.getElementById('id_cliente_seleccionado');

        btnSeleccionar.forEach(function(btn) {
            btn.addEventListener('click', function() {
                const clienteId = this.value;
                idClienteSeleccionado.value = clienteId;
            });
        });
    });
</script>
