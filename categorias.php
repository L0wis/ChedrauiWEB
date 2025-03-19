<?php
require_once 'config/config.php';

// Verifica si ya tienes una instancia de la conexión a la base de datos
if (!isset($con)) {
    $db = new Database();
    $con = $db->conectar();
}

if (isset($_GET['categoria'])) {
    $categoriaId = $_GET['categoria'];

    // Obtener el nombre de la categoría
    $stmtCategoria = $con->prepare("SELECT nombre FROM categorias WHERE id = :categoriaId");
    $stmtCategoria->bindValue(':categoriaId', $categoriaId, PDO::PARAM_INT);

    try {
        $stmtCategoria->execute();
        $categoria = $stmtCategoria->fetch(PDO::FETCH_ASSOC);

        if ($categoria) {
            // Verifica si se ha enviado el formulario y el parámetro 'orden' está presente
            if(isset($_GET['orden'])) {
                $orden = $_GET['orden'];
                switch($orden) {
                    case 'precio_alto':
                        $sqlProductos = $con->prepare("SELECT id, nombre, precio FROM productos WHERE id_categoria = :categoriaId ORDER BY precio DESC");
                        break;
                    case 'precio_bajo':
                        $sqlProductos = $con->prepare("SELECT id, nombre, precio FROM productos WHERE id_categoria = :categoriaId ORDER BY precio ASC");
                        break;
                    case 'asc':
                        $sqlProductos = $con->prepare("SELECT id, nombre, precio FROM productos WHERE id_categoria = :categoriaId ORDER BY nombre ASC");
                        break;
                    case 'desc':
                        $sqlProductos = $con->prepare("SELECT id, nombre, precio FROM productos WHERE id_categoria = :categoriaId ORDER BY nombre DESC");
                        break;
                    default:
                        // Si el valor del parámetro 'orden' no es válido, ejecutar la consulta SQL predeterminada
                        $sqlProductos = $con->prepare("SELECT id, nombre, precio FROM productos WHERE id_categoria = :categoriaId");
                        break;
                }
                $sqlProductos->bindValue(':categoriaId', $categoriaId, PDO::PARAM_INT);
                $sqlProductos->execute();
            } else {
                // Si no se ha seleccionado ningún orden, ejecutar la consulta SQL predeterminada
                $sqlProductos = $con->prepare("SELECT id, nombre, precio FROM productos WHERE id_categoria = :categoriaId");
                $sqlProductos->bindValue(':categoriaId', $categoriaId, PDO::PARAM_INT);
                $sqlProductos->execute();
            }
            ?>
            <!DOCTYPE html>
            <html lang="es">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Chedraui - Resultados de Categoría</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
                <link href="css/estilos.css" rel="stylesheet">
                <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
                <style>
                    .categoria-header {
                        margin-bottom: 10px; /* Ajusta el espacio entre el título y las tarjetas de productos */
                    }
                </style>
            </head>
            <body>
                <?php include 'menu.php'; ?>
<main>
    <div class="container">
        <div class="row">
            <div class="col-md-6">
                <h1>Productos de la categoría: <?php echo $categoria['nombre']; ?></h1>
            </div>
            <div class="col-md-6">
                <!-- Menú para ordenar los productos -->
                <form id="ordenForm" action="" method="GET" class="mb-4">
                    <div class="row">
                        <div class="col-md-8 d-flex align-items-center">
                            <label for="orden" class="form-label me-2">Ordenar por:</label>
                            <select class="form-select mb-0" name="orden" id="orden" onchange="document.getElementById('ordenForm').submit()">
                                <option value="precio_alto" <?php echo isset($_GET['orden']) && $_GET['orden'] == 'precio_alto' ? 'selected' : ''; ?>>Precio más alto</option>
                                <option value="precio_bajo" <?php echo isset($_GET['orden']) && $_GET['orden'] == 'precio_bajo' ? 'selected' : ''; ?>>Precio más bajo</option>
                                <option value="asc" <?php echo isset($_GET['orden']) && $_GET['orden'] == 'asc' ? 'selected' : ''; ?>>Nombre A-Z</option>
                                <option value="desc" <?php echo isset($_GET['orden']) && $_GET['orden'] == 'desc' ? 'selected' : ''; ?>>Nombre Z-A</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <input type="hidden" name="categoria" value="<?php echo $categoriaId; ?>">
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Línea negra como separador -->
        <hr class="mb-4 border-bottom">


                        <?php
                        if ($sqlProductos) {
                            ?>
                            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                                <?php
                                while ($producto = $sqlProductos->fetch(PDO::FETCH_ASSOC)) {
                                    echo '<div class="col">';
                                    echo '<div class="card shadow-sm">';
                                    // Imprime la imagen del producto si tienes una
                                    $imagen = "images/productos/" . $producto['id'] . "/principal.jpg";
                                    if (file_exists($imagen)) {
                                        echo '<img src="' . $imagen . '" class="d-block w-100" alt="' . $producto['nombre'] . '">';
                                    } else {
                                        echo '<img src="images/no-photo.jpeg" class="d-block w-100" alt="' . $producto['nombre'] . '">';
                                    }
                                    echo '<div class="card-body">';
                                    echo '<h5 class="card-title">' . $producto['nombre'] . '</h5>';
                                    echo '<p class="card-text">$' . number_format($producto['precio'], 2, '.', ',') . '</p>';
                                    echo '<div class="d-flex justify-content-between align-items-center">';
                                    echo '<div class="btn-group">';
                                    echo '<a href="details.php?id=' . $producto['id'] . '&token=' . hash_hmac('sha1', $producto['id'], KEY_TOKEN) . '" class="btn btn-primary">Detalles</a>';
                                    echo '</div>';
                                    echo '<a class="btn btn-outline-success" onClick="addProducto(' . $producto['id'] . ', \'' . hash_hmac('sha1', $producto['id'], KEY_TOKEN) . '\')">Agregar al Carrito</a>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                                ?>
                            </div>
                            <?php
                        } else {
                            // Capturar y mostrar errores de la consulta
                            $errorInfo = $con->errorInfo();
                            echo 'Error en la ejecución de la consulta de productos: ' . $errorInfo[2];
                        }
                        ?>
                    </div>
                </main>
                <?php include 'footer.php'; ?>
                
                <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
            </body> 
            </html>
            <?php
        } else {
            echo 'Error: No se encontró la categoría.';
        }
    } catch (PDOException $e) {
        echo 'Error en la ejecución de la consulta de categoría: ' . $e->getMessage();
    }
} else {
    // Manejo de error si no se proporciona un ID de categoría
    echo 'Error: Categoría no especificada.';
}
?>

