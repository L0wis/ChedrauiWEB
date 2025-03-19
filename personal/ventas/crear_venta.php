<?php
// Incluir archivo de configuración y encabezado
require '../config/config.php';
require '../header.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

// Función para generar una ID de transacción alfanumérica
function generarIDTransaccion()
{
    $caracteres = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'; // Caracteres permitidos
    $longitud = 17; // Longitud de la ID de transacción
    $id_transaccion = '';

    // Generar una cadena aleatoria con los caracteres permitidos
    for ($i = 0; $i < $longitud; $i++) {
        $id_transaccion .= $caracteres[rand(0, strlen($caracteres) - 1)];
    }

    return $id_transaccion;
}

// Obtener la fecha y hora actual en la zona horaria de México
date_default_timezone_set('America/Mexico_City');
$fecha_actual = date('Y-m-d H:i:s');

// Inicializar las variables para evitar los errores de "Undefined variable"
$correo_cliente = '';
$nombre_cliente = '';

// Obtener el ID del cliente si está presente en la URL
$id_cliente = isset($_GET['id_cliente']) ? $_GET['id_cliente'] : '';

// Obtener el nombre del cliente si el ID del cliente está presente
if (!empty($id_cliente)) {
    $db = new Database();
    $con = $db->conectar();

    // Consulta SQL para obtener el correo electrónico y nombre del cliente
    $sql = $con->prepare("SELECT email, nombres FROM clientes WHERE id = ? LIMIT 1");
    $sql->execute([$id_cliente]);
    $cliente = $sql->fetch(PDO::FETCH_ASSOC);

    // Verificar si se encontraron resultados
    if ($cliente) {
        // Asignar el correo electrónico y nombre del cliente a las variables correspondientes
        $correo_cliente = isset($cliente['email']) ? $cliente['email'] : '';
        $nombre_cliente = isset($cliente['nombres']) ? $cliente['nombres'] : '';
    }
}

// Obtener nombre de usuario
$nombre_usuario = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : "Usuario";

// Consultar la base de datos para obtener el personal
$db = new Database();
$con = $db->conectar();
$sql_personal = $con->prepare("SELECT id, nombre FROM personal WHERE activo = 1");
$sql_personal->execute();

// Verificar si se envió el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id_transaccion = generarIDTransaccion();
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $id_cliente = isset($_POST['id_cliente']) ? $_POST['id_cliente'] : '';
    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : '';
    $id_personal = isset($_POST['id_personal']) ? $_POST['id_personal'] : ''; // Nuevo

    // Verificar si los datos requeridos están presentes
    if (!empty($email) && !empty($id_cliente)) {
        // Insertar los datos en la base de datos
        $sql = $con->prepare("INSERT INTO compra_personal (id_transaccion, email, id_cliente, id_personal, fecha) VALUES (?, ?, ?, ?, ?)"); // Modificado
        $sql->execute([$id_transaccion, $email, $id_cliente, $id_personal, $fecha_actual]); // Modificado
        // Redireccionar a una página de éxito o hacer alguna otra acción
        header('Location: terminar_compra.php');
        exit;
    } else {
        // Manejar el caso en que los datos requeridos estén ausentes
    }
}
?>


<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Crear Nueva Venta</h2>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="mb-3">
                <div class="mb-3">
                    <label for="id_transaccion" class="form-label">ID Transacción</label>
                    <!-- Mostrar el ID de transacción generado -->
                    <input type="text" class="form-control" id="id_transaccion" name="id_transaccion"
                           value="<?php echo generarIDTransaccion(); ?>" required readonly>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <!-- Utilizamos el correo electrónico del cliente como valor predeterminado -->
                    <input type="email" class="form-control" id="email" name="email"
                           value="<?php echo $correo_cliente; ?>"
                           required>
                </div>
                <div class="mb-3">
                    <label for="id_cliente" class="form-label">ID Cliente</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="id_cliente" name="id_cliente"
                               value="<?php echo isset($_GET['id_cliente']) ? htmlspecialchars($_GET['id_cliente']) : ''; ?>"
                               readonly>
                        <a href="buscar_cliente.php" class="btn btn-outline-secondary">Buscar Cliente</a>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre</label>
                    <!-- Utilizamos el nombre del cliente como valor predeterminado -->
                    <input type="text" class="form-control" id="nombre" name="nombre"
                           value="<?php echo $nombre_cliente; ?>"
                           required>
                </div>

                <div class="mb-3">
                    <label for="id_personal" class="form-label">Personal</label>
                    <!-- Agregar el select para seleccionar el personal -->
                    <select class="form-select" name="id_personal" id="id_personal" required>
                        <option value="">Seleccionar personal...</option>
                        <?php
                        // Iterar sobre los resultados y generar las opciones del select
                        while ($row = $sql_personal->fetch(PDO::FETCH_ASSOC)) {
                            echo "<option value=\"{$row['id']}\">{$row['nombre']}</option>"; // Modificado
                        }
                        ?>
                    </select>
                </div>

                <!-- Botón para enviar el formulario -->
                <button type="submit" class="btn btn-primary">Crear Entrega Siguiente</button>
        </form>

    </div>
</main>


<script>
    // Obtener el elemento de ID cliente
    const idClienteInput = document.getElementById('id_cliente');
    // Obtener el elemento de nombre
    const nombreInput = document.getElementById('nombre');

    // Escuchar el evento de cambio en el ID cliente
    idClienteInput.addEventListener('input', () => {
        const idCliente = idClienteInput.value;
        // Realizar una solicitud AJAX para obtener el nombre del cliente
        fetch(`obtener_nombre_cliente.php?id=${idCliente}`)
            .then(response => response.text())
            .then(nombre => {
                // Asignar el nombre obtenido al campo de nombre
                nombreInput.value = nombre;
            })
            .catch(error => console.error('Error al obtener el nombre del cliente:', error));
    });
</script>

<?php require '../footer.php'; ?>
