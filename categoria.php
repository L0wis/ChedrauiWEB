<?php
// categoria.php

// Asegúrate de incluir el archivo de configuración y la conexión a la base de datos
require_once 'config/config.php';

// Verifica si se proporciona el parámetro 'id' en la URL
if (isset($_GET['id'])) {
    $idCategoria = $_GET['id'];

    // Realiza la consulta para obtener la información de la categoría
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

<?php
// Ahora puedes mostrar los productos de la categoría en tarjetas Bootstrap
        echo '<div class="container mt-.5">';
        echo '<h2 class="text-center">' . $categoria['nombre'] . '</h2>';
        echo '<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">';

        foreach ($productosCategoria as $producto) {
            echo '<div class="col">';
            echo '<div class="card shadow-sm">';

            // Imprime la imagen del producto si tienes una
            $imagen = "images/productos/" . $producto['id'] . "/principal.jpg";
            if (file_exists($imagen)) {
                echo '<img src="' . $imagen . '" class="card-img-top" alt="' . $producto['nombre'] . '">';
            } else {
                echo '<img src="images/no-photo.jpeg" class="card-img-top" alt="' . $producto['nombre'] . '">';
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

        echo '</div>';
        echo '</div>';
    } else {
        echo 'Categoría no encontrada';
    }
} else {
    echo 'ID de categoría no proporcionado';
}

?>

</main>

    <?php include 'footer.php'; ?>

    <script>
              function addProducto(id, token) {
                let url = 'clases/carrito.php'
                let formData = new FormData()
                formData.append('id', id)
                formData.append('token', token)

                fetch(url, {
                  method: 'POST',
                  body: formData,
                  mode: 'cors'
                }).then(response => response.json())
                .then(data => {
                  if(data.ok){
                    let elemento = document.getElementById("num_cart")
                    elemento.innerHTML = data.numero
                  } else {
                    alert("No hay suficientes existencias")
                  }
                })
              }
            </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
