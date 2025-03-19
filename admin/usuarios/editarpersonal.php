<?php
require '../config/database.php';
require '../config/config.php';
require '../header.php';

// Verificar si se ha proporcionado un ID de usuario para editar
if (!isset($_GET['id'])) {
    header('Location: personal.php');
    exit;
}

// Obtener el ID del usuario de la URL
$user_id = $_GET['id'];

// Conexión a la base de datos
$db = new Database();
$con = $db->conectar();

// Consulta para obtener la información del usuario a editar
$sql = "SELECT id, usuario, nombre, email, puesto FROM personal WHERE id = :user_id";
$stmt = $con->prepare($sql);
$stmt->bindParam(':user_id', $user_id);
$stmt->execute();
$persona = $stmt->fetch(PDO::FETCH_ASSOC);

// Verificar si el usuario existe en la base de datos
if (!$persona) {
    header('Location: personal.php');
    exit;
}

// Procesar el formulario cuando se envíe
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos enviados por el formulario
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $puesto = $_POST['puesto'];
    
    // Consulta SQL para actualizar la información del usuario en la base de datos
    $sql_update = "UPDATE personal SET nombre = :nombre, email = :email, puesto = :puesto WHERE id = :user_id";
    $stmt_update = $con->prepare($sql_update);
    $stmt_update->bindParam(':nombre', $nombre);
    $stmt_update->bindParam(':email', $email);
    $stmt_update->bindParam(':puesto', $puesto);
    $stmt_update->bindParam(':user_id', $user_id);
    
    // Ejecutar la consulta de actualización
    if ($stmt_update->execute()) {
        // Si la actualización fue exitosa, redireccionar a la página de personal
        header('Location: personal.php');
        exit;
    } else {
        // Si la actualización falló, puedes manejar el error de alguna manera
        echo "Error al actualizar la información del usuario.";
    }
}

?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Editar Información de Personal</h2>
        <form action="" method="post">
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario:</label>
                <input type="text" class="form-control" id="usuario" name="usuario" value="<?php echo htmlspecialchars($persona['usuario'], ENT_QUOTES); ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre:</label>
                <input type="text" class="form-control" id="nombre" name="nombre" value="<?php echo htmlspecialchars($persona['nombre'], ENT_QUOTES); ?>">
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($persona['email'], ENT_QUOTES); ?>">
            </div>
            <div class="mb-3">
    <label for="puesto" class="form-label">Puesto:</label>
    <select class="form-select" id="puesto" name="puesto">
        <option value="BEBIDAS" <?php if ($persona['puesto'] === 'BEBIDAS') echo 'selected'; ?>>Bebidas</option>
        <option value="COMIDA" <?php if ($persona['puesto'] === 'COMIDA') echo 'selected'; ?>>Comida</option>
        <option value="LIMPIEZA" <?php if ($persona['puesto'] === 'LIMPIEZA') echo 'selected'; ?>>Limpieza</option>
        <option value="ROPA PARA CABALLERO" <?php if ($persona['puesto'] === 'ROPA PARA CABALLERO') echo 'selected'; ?>>Ropa para Caballero</option>
        <option value="ROPA PARA DAMA" <?php if ($persona['puesto'] === 'ROPA PARA DAMA') echo 'selected'; ?>>Ropa para Dama</option>
        <option value="ELECTRONICA" <?php if ($persona['puesto'] === 'ELECTRONICA') echo 'selected'; ?>>Electrónica</option>
        <option value="HIGIENE" <?php if ($persona['puesto'] === 'HIGIENE') echo 'selected'; ?>>Higiene</option>
    </select>
</div>

            <button type="submit" class="btn btn-primary">Guardar Cambios</button>
            <a href="personal.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</main>

<?php require '../footer.php'; ?>
