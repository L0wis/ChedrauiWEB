<?php
// search.php

require_once 'config/config.php';
$db = new Database();
$con = $db->conectar();

// Verifica si se proporcionó un término de búsqueda
if (isset($_GET['query'])) {
    // Recupera el término de búsqueda y realiza la consulta
    $query = $_GET['query'];
    $stmt = $con->prepare("SELECT id, nombre, precio, descuento, stock FROM productos WHERE nombre LIKE :query");
    $stmt->bindValue(':query', '%' . $query . '%', PDO::PARAM_STR);
    $stmt->execute();
    $productos = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Si no se proporciona un término de búsqueda, muestra un mensaje
    echo 'Por favor, ingrese un término de búsqueda válido.';
    exit();  // Termina la ejecución del script si no hay un término de búsqueda
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chedraui - Resultados de Búsqueda</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="css/estilos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>

<?php include 'menu.php'; ?>

<main>
    <div class="container">
    <h2>Resultados de la búsqueda: "<?php echo $query; ?>"</h2>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
            <?php
            if (!empty($productos)) {
                foreach ($productos as $producto) {
                    echo '<div class="col">';
                    echo '<div class="card shadow-sm">';
                    
                    $id = $producto['id'];
                    $imagen = "images/productos/" . $id . "/principal.jpg";

                    if (!file_exists($imagen)) {
                        $imagen = "images/no-photo.jpeg";
                    }

                    echo '<img src="' . $imagen . '" class="d-block w-100">';
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
            } else {
                echo '<p>No se encontraron resultados.</p>';
            }
            ?>
        </div>
    </div>
</main>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>

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
                if (data.ok) {
                    let elemento = document.getElementById("num_cart")
                    elemento.innerHTML = data.numero
                } else {
                    alert("No hay suficientes existencias")
                }
            })
    }
</script>

</body>
</html>
