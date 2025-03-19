<?php
// ver_categoria.php

require_once 'config/config.php';

if (isset($_GET['id'])) {
    $idCategoria = $_GET['id'];

    $sqlCategoria = "SELECT id, nombre FROM categorias WHERE id = :id";
    $stmtCategoria = $con->prepare($sqlCategoria);
    $stmtCategoria->bindParam(':id', $idCategoria, PDO::PARAM_INT);
    $stmtCategoria->execute();

    $categoria = $stmtCategoria->fetch(PDO::FETCH_ASSOC);

    if ($categoria) {
        // Consulta para obtener los productos de la categoría seleccionada
        $sqlProductosCategoria = "SELECT id, nombre, precio FROM productos WHERE id_categoria = :idCategoria";
        $stmtProductosCategoria = $con->prepare($sqlProductosCategoria);
        $stmtProductosCategoria->bindParam(':idCategoria', $idCategoria, PDO::PARAM_INT);
        $stmtProductosCategoria->execute();
        $productosCategoria = $stmtProductosCategoria->fetchAll(PDO::FETCH_ASSOC);
    } else {
        echo 'Categoría no encontrada';
        exit;
    }
} else {
    echo 'ID de categoría no proporcionado';
    exit;
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chedraui - Categoría</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="css/estilos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>

<?php include 'menu.php'; ?>

<main>
    <div class="container mt-.5">
        <h2 class="text-center"><?php echo $categoria['nombre']; ?></h2>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            <?php foreach ($productosCategoria as $producto) { ?>
                <div class="col">
                    <div class="card shadow-sm">
                        <?php
                            // Imprime la imagen del producto si tienes una
                            $imagen = "images/productos/" . $producto['id'] . "/principal.jpg";
                            if (file_exists($imagen)) {
                                echo '<img src="' . $imagen . '" class="card-img-top" alt="' . $producto['nombre'] . '">';
                            } else {
                                echo '<img src="images/no-photo.jpeg" class="card-img-top" alt="' . $producto['nombre'] . '">';
                            }
                        ?>
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $producto['nombre']; ?></h5>
                            <p class="card-text">$<?php echo number_format($producto['precio'], 2, '.', ','); ?></p>
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="btn-group">
                                    <a href="details.php?id=<?php echo $producto['id']; ?>&token=<?php echo hash_hmac('sha1', $producto['id'], KEY_TOKEN); ?>" class="btn btn-primary">Detalles</a>
                                </div>
                                <a class="btn btn-outline-success" onClick="addProducto(<?php echo $producto['id']; ?>, '<?php echo hash_hmac('sha1', $producto['id'], KEY_TOKEN); ?>')">Agregar al Carrito</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
