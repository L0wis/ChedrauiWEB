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
    if (isset($_POST['usuario']) && isset($_POST['password']) && isset($_POST['nombre']) && isset($_POST['email']) && isset($_POST['puesto']) && isset($_POST['activo'])) {
        $usuario = $_POST['usuario'];
        $password = $_POST['password'];
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $puesto = $_POST['puesto'];
        $activo = $_POST['activo'];

        // Hash de la contraseña
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        // Insertar los datos en la base de datos
        $db = new Database();
        $con = $db->conectar();

        $sql = "INSERT INTO personal (usuario, password, nombre, email, puesto, activo, fecha_alta) VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $con->prepare($sql);
        $stmt->execute([$usuario, $hashed_password, $nombre, $email, $puesto, $activo]);

        // Redireccionar a la página principal después de la inserción
        header('Location: personal.php');
        exit;
    }
}
?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Agregar Personal</h2>

        <div class="row">
            <div class="col-md-6">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="mb-3">
                        <label for="usuario" class="form-label">Usuario</label>
                        <input type="text" class="form-control" id="usuario" name="usuario" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Contraseña</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <div class="mb-3">
                        <label for="nombre" class="form-label">Nombre</label>
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="puesto" class="form-label">Puesto</label>
                        <select class="form-select" id="puesto" name="puesto" required>
                            <option value="BEBIDAS">Bebidas</option>
                            <option value="COMIDA">Comida</option>
                            <option value="LIMPIEZA">Limpieza</option>
                            <option value="ROPA PARA CABALLERO">Ropa para Caballero</option>
                            <option value="ROPA PARA DAMA">Ropa para Dama</option>
                            <option value="ELECTRONICA">Electrónica</option>
                            <option value="HIGIENE">Higiene</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="activo" class="form-label">Activo</label>
                        <select class="form-select" id="activo" name="activo" required>
                            <option value="1">Sí</option>
                            <option value="0">No</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-info">Guardar Cambios</button>
                </form>
            </div>
        </div>
    </div>
</main>

<?php require '../footer.php'; ?>
