<?php

// Incluir los archivos necesarios y empezar la sesión
require '../config/database.php';
require '../config/config.php';
require '../header.php';

// Verificar si el usuario tiene permisos de administrador
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../../index.php');
    exit;
}

// Procesar el formulario si se ha enviado
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $usuario = $_POST['usuario'];
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Cifrar la contraseña

    // Crear una instancia de la base de datos y conectar
    $db = new Database();
    $con = $db->conectar();

    // Consulta SQL para insertar un nuevo administrador en la base de datos
    $sql = "INSERT INTO admin (usuario, password, nombre, email, activo, fecha_alta)
            VALUES (:usuario, :password, :nombre, :email, '1', NOW())";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);
    $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);

    // Ejecutar la consulta SQL
    if ($stmt->execute()) {
        echo "Nuevo administrador agregado correctamente.";
    } else {
        echo "Error al agregar el nuevo administrador.";
    }
}
?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Agregar Nuevo Administrador</h2>
        <form method="post">
            <div class="mb-3">
                <label for="usuario" class="form-label">Usuario</label>
                <input type="text" class="form-control" id="usuario" name="usuario" required>
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
                <label for="password" class="form-label">Contraseña</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Agregar Administrador</button>
            <a href="administradores.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</main>

<?php require '../footer.php'; ?>
