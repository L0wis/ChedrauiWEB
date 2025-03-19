<?php

// Evitar el almacenamiento en caché del navegador
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // Compatibilidad con IE
header("Pragma: no-cache"); // HTTP/1.0

require_once 'config/config.php';

// Verifica si ya tienes una instancia de la conexión a la base de datos
if (!isset($con)) {
    $db = new Database();
    $con = $db->conectar();
}

// Definición de la función para obtener la descripción desde la base de datos
function obtenerDescripcionDesdeLaBaseDeDatos($categoriaId, $con)
{
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="css/estilos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <style>
        /* Estilos para los textos en el carrusel */
        .carousel-caption h5,
        .carousel-caption p {
            color: black;
            font-size: 18px;
            font-family: 'Arial', sans-serif;
            font-weight: bold;
            text-shadow: 2px 2px 4px rgba(255, 255, 255, 0.7);
        }

        /* Agrega el contorno negro a las imágenes del carrusel */
        .img-contorno {
            border: 10px solid black;
        }

        .circle-img {
            border-radius: 50%;
            width: 300px;
            height: 300px;
            object-fit: cover;
            border: 10px solid black;
        }

        .circle-container {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }
    </style>
</head>
<body>
<?php include 'menu.php'; ?>
<main>
    <div class="container">
        <div class="my-4 text-center">
            <h2 class="carousel-header">Categorías Principales</h2>
            <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <?php
                    foreach ($categorias as $categoria) {
                        $idCategoria = $categoria['id'];
                        $imagenCategoria = "images/categorias/" . $idCategoria . "/categoria.jpg";
                        $activeClass = ($idCategoria == $categorias[0]['id']) ? 'active' : '';
                        $descripcionImagen = obtenerDescripcionDesdeLaBaseDeDatos($idCategoria, $con);

                        echo '<div class="carousel-item ' . $activeClass . '">';
                        echo '<a href="categoria.php?id=' . $idCategoria . '">';
                        echo '<img src="' . $imagenCategoria . '" class="d-block w-100" alt="Slide ' . $idCategoria . '" style="height: 300px; object-fit: cover;">';
                        echo '</a>';
                        echo '<div class="carousel-caption d-flex flex-column align-items-center justify-content-center">';
                        echo '<h5 class="mb-0">' . $categoria['nombre'] . '</h5>';
                        echo '<p class="mb-0">' . $descripcionImagen . '</p>';
                        echo '</div>';
                        echo '</div>';
                    }
                    ?>
                </div>

                <!-- Controles del carrusel -->
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions"
                        data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>

                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions"
                        data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>

        <!-- Insertamos el espacio -->
        <div class="row my-4"></div>

        <!-- Ratios con imágenes -->
        <div class="row text-center">
            <div class="col-md-3">
                <div class="circle-container">
                    <a href="index.php">
                        <img src="images/xmenu/catalogo.jpg" alt="Imagen 1" class="circle-img">
                    </a>
                    <div class="mt-2">
                        <a href="index.php" class="btn btn-success text-decoration-none">VAMOS AL CATALOGO!</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="circle-container">
                    <a href="ofertas.php">
                        <img src="images/xmenu/ofertas.jpg" alt="Imagen 2" class="circle-img">
                    </a>
                    <div class="mt-2">
                        <a href="ofertas.php" class="btn btn-success text-decoration-none">VENGAN ESAS OFERTAS!</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="circle-container">
                    <a href="mas_categorias.php">
                        <img src="images/xmenu/categorias.png" alt="Imagen 3" class="circle-img">
                    </a>
                    <div class="mt-2">
                        <a href="mas_categorias.php" class="btn btn-success text-decoration-none">MAS CATEGORIAS!</a>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="circle-container">
                    <a href="http://localhost/chedraui/categoria.php?id=30">
                        <img src="images/xmenu/telefonia.jpg" alt="Imagen 4" class="circle-img">
                    </a>
                    <div class="mt-2">
                        <a href="http://localhost/chedraui/atencion_cliente.php" class="btn btn-success text-decoration-none">Atencion al Cliente</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Productos Aleatorios -->
        <div class="my-2 text-center">
            <h2>Productos Mas Populares</h2>

            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <?php
                $sqlProductosAleatorios = "SELECT id, nombre, precio FROM productos ORDER BY RAND() LIMIT 6";

                try {
                    $resultProductosAleatorios = $con->query($sqlProductosAleatorios);

                    if ($resultProductosAleatorios) {
                        while ($productoAleatorio = $resultProductosAleatorios->fetch(PDO::FETCH_ASSOC)) {
                            echo '<div class="col">';
                            echo '<div class="card shadow-sm">';
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
        interval: 2000 // Configura el intervalo en 2 segundos (2000 milisegundos)
    });
</script>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
            crossorigin="anonymous"></script>

    </body>

    </html>

    <?php
} else {
    // Manejo de error si no se pueden obtener las categorías
    $errorInfo = $con->errorInfo();
    echo 'Error en la ejecución de la consulta de categorías: ' . $errorInfo[2];
}
?>