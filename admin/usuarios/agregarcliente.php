<?php

ob_start();

require '../config/database.php';
require '../config/config.php';
require '../header.php';

// Verificar si el usuario tiene permisos de administrador
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

// Manejo del envío del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Verificar que se han enviado los datos del formulario
    if (isset($_POST['nombres']) && isset($_POST['apellidos']) && isset($_POST['email']) && isset($_POST['dni']) && isset($_POST['telefono']) && isset($_POST['estatus'])) {
        $nombres = $_POST['nombres'];
        $apellidos = $_POST['apellidos'];
        $email = $_POST['email'];
        $dni = $_POST['dni'];
        $telefono = $_POST['telefono'];
        $estatus = $_POST['estatus'];

        // Insertar los datos del cliente en la base de datos
        $db = new Database();
        $con = $db->conectar();

        $sql = "INSERT INTO clientes (nombres, apellidos, email, dni, telefono, estatus, fecha_alta) VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $con->prepare($sql);
        $stmt->execute([$nombres, $apellidos, $email, $dni, $telefono, $estatus]);

        // Obtener el ID del cliente recién insertado
        $cliente_id = $con->lastInsertId();

        // Generar una contraseña para el nuevo usuario
        $password = password_hash('pedro', PASSWORD_DEFAULT);

        // Insertar el usuario en la base de datos
        $sql_usuario = "INSERT INTO usuarios (usuario, password, activacion, id_cliente) VALUES (?, ?, ?, ?)";
        $stmt_usuario = $con->prepare($sql_usuario);
        $stmt_usuario->execute(['pedro', $password, 'pedro', $cliente_id]);

        // Redireccionar a la página principal después de la inserción
        header('Location: clientes.php');
        exit;
    }
}

?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Agregar Cliente</h2>

        <div class="row">
            <div class="col-md-6">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="mb-3">
                        <label for="nombres" class="form-label">Nombres</label>
                        <input type="text" class="form-control" id="nombres" name="nombres" required>
                    </div>
                    <div class="mb-3">
                        <label for="apellidos" class="form-label">Apellidos</label>
                        <input type="text" class="form-control" id="apellidos" name="apellidos" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="dni" class="form-label">DNI</label>
                        <input type="text" class="form-control" id="dni" name="dni" required>
                    </div>
                    <div class="mb-3">
                        <label for="telefono" class="form-label">Teléfono</label>
                        <input type="text" class="form-control" id="telefono" name="telefono" required>
                    </div>
                    <div class="mb-3">
                        <label for="estatus" class="form-label">Estatus</label>
                        <select class="form-select" id="estatus" name="estatus" required>
                            <option value="1">Activado</option>
                            <option value="0">Desactivado</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-info">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php require '../footer.php'; ?>
