<?php

ob_start();  // Agrega esta línea al inicio

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

// Obtener datos del formulario
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];

// Insertar la nueva categoría en la base de datos
$sql = $con->prepare("INSERT INTO categorias (nombre, descripcion, activo) VALUES (?, ?, 1)");
$sql->execute([$nombre, $descripcion]);

// Obtener el ID de la categoría recién insertada
$categoriaId = $con->lastInsertId();

// Procesar la imagen
if (!empty($_FILES['imagen_categoria']['name'])) {
    // Ruta donde se guardará la imagen
    $rutaImagenes = "../../images/categorias/{$categoriaId}/";
    $rutaImagen = $rutaImagenes . 'categoria.jpg';

    // Crear la carpeta si no existe
    if (!file_exists($rutaImagenes)) {
        mkdir($rutaImagenes, 0777, true);
    }

    // Mover la imagen cargada al destino final
    if (move_uploaded_file($_FILES['imagen_categoria']['tmp_name'], $rutaImagen)) {
        // Imagen guardada exitosamente
    } else {
        // Manejar el error si la imagen no se pudo mover
        echo "Error al subir la imagen.";
    }
}

// Redirigir a la lista de categorías
header('Location: index.php');
exit;

?>
