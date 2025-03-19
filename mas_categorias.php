<?php
require_once 'config/config.php';

// Verifica si ya tienes una instancia de la conexión a la base de datos
if (!isset($con)) {
    $db = new Database();
    $con = $db->conectar();
}

// Definición de la función para obtener la descripción desde la base de datos
function obtenerDescripcionDesdeLaBaseDeDatos($categoriaId, $con) {
    // Implementa la lógica para obtener la descripción desde la base de datos
    $sql = "SELECT descripcion FROM categorias WHERE id = :categoriaId";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':categoriaId', $categoriaId, PDO::PARAM_INT);
    $stmt->execute();

    // Obtener la descripción
    $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
    $descripcion = $resultado ? $resultado['descripcion'] : '';

    return $descripcion;
}

// Realizar la consulta SQL para obtener todas las categorías
$sqlCategorias = "SELECT id, nombre FROM categorias WHERE activo = 1";
$resultCategorias = $con->query($sqlCategorias);

if ($resultCategorias) {
    // Creamos un array para almacenar las categorías
    $categorias = $resultCategorias->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chedraui - Más Categorías</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="css/estilos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

    <style>
                                /* Estilos para los textos en el carrusel */
                                .carousel-caption h5,
                                .carousel-caption p {
                                    color: black;
                                    /* Cambia el color de la letra a negro */
                                    font-size: 18px;
                                    /* Ajusta el tamaño de la letra */
                                    font-family: 'Arial', sans-serif;
                                    /* Cambia la fuente del texto */
                                    font-weight: bold;
                                    /* Hace que el texto sea negrita */
                                    text-shadow: 2px 2px 4px rgba(255, 255, 255, 0.7);
                                    /* Agrega un borde blanco al texto */
                                }

                                /* Otros estilos... */
                            </style>
</head>
<body>

<?php include 'menu.php'; ?>
<main>
    <div class="container">
        <div class="my-.001 text-center"> <!-- Agrega la clase text-center para centrar horizontalmente -->
            <h2 class="carousel-header">Más Categorías</h2>

            <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">

                <div class="carousel-inner">
                    <?php
                    foreach ($categorias as $categoria) {
                        $idCategoria = $categoria['id'];
                        $imagenCategoria = "images/categorias/" . $idCategoria . "/categoria.jpg"; // Ajusta la extensión de la imagen según el formato real

                        $activeClass = ($idCategoria == $categorias[0]['id']) ? 'active' : '';

                        // Obtener la descripción de la imagen desde la base de datos
                        $descripcionImagen = obtenerDescripcionDesdeLaBaseDeDatos($idCategoria, $con);

                        echo '<div class="carousel-item ' . $activeClass . '">';
                        // Agregar un enlace alrededor de la imagen
                        echo '<a href="categoria.php?id=' . $idCategoria . '">';
                        echo '<img src="' . $imagenCategoria . '" class="d-block w-100" alt="Slide ' . $idCategoria . '" style="height: 300px; object-fit: cover;">';
                        echo '</a>';
                        echo '<div class="carousel-caption d-flex flex-column align-items-center justify-content-center">';
                        echo '<h5>' . $categoria['nombre'] . '</h5>';
                        echo '<p>' . $descripcionImagen . '</p>';
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>

                <!-- Controles del carrusel -->
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>

                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>

        <!-- Productos Aleatorios -->
        <div class="my-2 text-center"> <!-- Agrega la clase text-center para centrar horizontalmente -->
            <h2>Productos Mas Populares</h2>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <?php
                // Consulta para obtener 6 productos aleatorios
                $sqlProductosAleatorios = "SELECT id, nombre, precio FROM productos ORDER BY RAND() LIMIT 6";

                try {
                    $resultProductosAleatorios = $con->query($sqlProductosAleatorios);

                    if ($resultProductosAleatorios) {
                        while ($productoAleatorio = $resultProductosAleatorios->fetch(PDO::FETCH_ASSOC)) {
                            // Aquí debes mostrar la información de cada producto
                            echo '<div class="col">';
                            echo '<div class="card shadow-sm">';

                            // Imprime la imagen del producto si tienes una
                            $imagenAleatoria = "images/productos/" . $productoAleatorio['id'] . "/principal.jpg";
                            if (file_exists($imagenAleatoria)) {
                                echo '<img src="' . $imagenAleatoria . '" class="d-block w-100" alt="' . $productoAleatorio['nombre'] . '">';
                            } else {
                                echo '<img src="images/no-photo.jpeg" class="d-block w-100" alt="' . $productoAleatorio['nombre'] . '">';
                            }

                            echo '<div class="card-body">';
                            echo '<h5 class="card-title">' . $productoAleatorio['nombre'] . '</h5>';
                            echo '<p class="card-text">$' . number_format($productoAleatorio['precio'], 2, '.', ',') . '</p>';
                            echo '<div class="d-flex justify-content-between align-items-center">';
                            echo '<div class="btn-group">';
                            echo '<a href="details.php?id=' . $productoAleatorio['id'] . '&token=' . hash_hmac('sha1', $productoAleatorio['id'], KEY_TOKEN) . '" class="btn btn-primary">Detalles</a>';
                            echo '</div>';
                            echo '<a class="btn btn-outline-success" onClick="addProducto(' . $productoAleatorio['id'] . ', \'' . hash_hmac('sha1', $productoAleatorio['id'], KEY_TOKEN) . '\')">Agregar al Carrito</a>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        $errorInfo = $con->errorInfo();
                        echo 'Error en la ejecución de la consulta de productos aleatorios: ' . $errorInfo[2];
                    }
                } catch (PDOException $e) {
                    echo 'Error de ejecución de la consulta de productos aleatorios: ' . $e->getMessage();
                }
                ?>
            </div>
        </div>
    </div>
</main>
<?php include 'footer.php'; ?>

<script>
    // Script para mover automáticamente el carrusel cada 5 segundos
    var myCarousel = document.getElementById('carouselExampleCaptions'); // Obtiene el carrusel por su id
    var carousel = new bootstrap.Carousel(myCarousel, {
        interval: 2000 // Configura el intervalo en 5 segundos (5000 milisegundos)
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

</body>
</html>

<?php
} else {
    // Manejo de error si no se pueden obtener las categorías
    $errorInfo = $con->errorInfo();
    echo 'Error en la ejecución de la consulta de categorías: ' . $errorInfo[2];
}
?>
