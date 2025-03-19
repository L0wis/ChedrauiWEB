<?php 

require '../config/database.php';
require '../config/config.php';
require '../header.php';

if (!isset($_SESSION['user_type'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SESSION['user_type'] != 'admin'){
    header('Location: ../../index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Nueva categoría</h2>

        <form action="guarda.php" method="post" enctype="multipart/form-data" autocomplete="off">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" id="nombre" required autofocus>
            </div>

            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <textarea class="form-control" name="descripcion" id="descripcion" required></textarea>
            </div>

            <div class="mb-3">
                <label for="imagen_categoria" class="form-label">Imagen de la categoría</label>
                <input type="file" class="form-control" name="imagen_categoria" id="imagen_categoria" accept="image/jpeg" required>
                <small class="form-text text-muted">Solo se permiten imágenes en formato JPEG.</small>
            </div>

            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
</main>

<?php require '../footer.php'; ?>
