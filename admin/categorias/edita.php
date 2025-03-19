<?php

ob_start(); // Iniciar el búfer de salida

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

$id = $_GET['id'];

// Obtener la información de la categoría
$sql = $con->prepare("SELECT id, nombre, descripcion FROM categorias WHERE id=? LIMIT 1");
$sql->execute([$id]);
$categoria = $sql->fetch(PDO::FETCH_ASSOC);

// Ruta de la carpeta de imágenes de categorías
$rutaImagenes = '../../images/categorias/' . $id . '/';
$imagenCategoria = $rutaImagenes . 'categoria.jpg';

// Crear la carpeta si no existe
if (!file_exists($rutaImagenes)) {
    mkdir($rutaImagenes, 0777, true);
}

// Procesar la eliminación de la imagen
if (isset($_POST['eliminar_imagen'])) {
    if (file_exists($imagenCategoria)) {
        unlink($imagenCategoria);
    }
    header("Location: edita.php?id={$id}");
    exit;
}

// Procesar el formulario al enviar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Actualizar información de la categoría
    $nombre = $_POST['nombre'];
    $descripcion = $_POST['descripcion'];

    $sqlActualizar = $con->prepare("UPDATE categorias SET nombre=?, descripcion=? WHERE id=?");
    $sqlActualizar->execute([$nombre, $descripcion, $id]);

    // Procesar la subida de la imagen
    if ($_FILES['imagen_categoria']['name']) {
        // Ruta de la imagen principal
        $imagenCategoria = $rutaImagenes . 'categoria.jpg';

        // Mover la imagen subida a la carpeta
        move_uploaded_file($_FILES['imagen_categoria']['tmp_name'], $imagenCategoria);
    }

    ob_end_clean(); // Limpiar el búfer de salida

    // Redireccionar después de la actualización
    header("Location: index.php");
    exit;
}
?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Edita categoría</h2>

        <form action="edita.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="id" value="<?php echo $categoria['id']; ?>">

            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" id="nombre" value="<?php echo $categoria['nombre']; ?>" required autofocus>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" name="descripcion" id="descripcion"><?php echo $categoria['descripcion']; ?></textarea>
            </div>

            <div class="mb-3">
                <label for="imagen_categoria" class="form-label">Imagen de la categoría</label>
                <input type="file" class="form-control" name="imagen_categoria" id="imagen_categoria" accept="image/jpeg">
            </div>

            <?php if (file_exists($imagenCategoria)) { ?>
    <img src="<?php echo $imagenCategoria . '?id=' . time(); ?>" class="img-thumbnail my-3" alt="Imagen de la categoría" style="max-width: 300px; max-height: 300px;"><br>
    <button type="submit" class="btn btn-danger" name="eliminar_imagen">Eliminar Imagen</button>
<?php } ?>


            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>

    </div>
</main>

<script>
    ClassicEditor
        .create(document.querySelector('#descripcion'))
        .catch(error => {
            console.error(error);
        });
</script>

<?php require '../footer.php'; ?>
