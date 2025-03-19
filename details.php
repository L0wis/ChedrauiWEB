<?php

// Evitar el almacenamiento en caché del navegador
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // Compatibilidad con IE
header("Pragma: no-cache"); // HTTP/1.0

require_once 'config/config.php';

$db = new Database();
$con = $db->conectar();

$id = isset($_GET['id']) ? $_GET['id'] : '';
$token = isset($_GET['token']) ? $_GET['token'] : '';

if ($id == '' || $token == '') {
    echo 'Error al procesar';
    exit;
} else {
    $token_tmp = hash_hmac('sha1', $id, KEY_TOKEN);
    if ($token == $token_tmp) {
        // Obtén el valor de $id después de validar la token
        $sql = $con->prepare("SELECT count(id) FROM productos WHERE id=? AND activo=1");
        $sql->execute([$id]);
        if ($sql->fetchColumn() > 0) {
            $idProducto = $id; // Asigna el valor de $id a $idProducto
            $sql = $con->prepare("SELECT nombre, descripcion, precio, descuento FROM productos WHERE id=? AND activo=1 LIMIT 1");
            $sql->execute([$id]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $nombre = $row['nombre'];
            $precio = $row['precio'];
            $descripcion = $row['descripcion'];
            $descuento = $row['descuento'];
            $precio_desc = $precio - (($precio * $descuento) / 100);
            $dir_images = 'images/productos/' . $id . '/';
            $rutaImg = $dir_images . 'principal.jpg';
            if (!file_exists($rutaImg)) {
                $rutaImg = 'images/no-photo.jpeg';
            }
            $imagenes = array();
            if (file_exists($dir_images)) {
                $dir = dir($dir_images);
                while (($archivo = $dir->read()) != false) {
                    if ($archivo != 'principal.jpg' && (strpos($archivo, 'jpg') || strpos($archivo, 'jpeg'))) {
                        $imagenes[] = $dir_images . $archivo;
                    }
                }
                $dir->close();
            }
        } else {
            echo 'Error al procesar la petición';
            exit;
        }
    }
}

// Obtener el id del producto anterior
$sql_anterior = $con->prepare("SELECT id FROM productos WHERE id < ? AND activo = 1 ORDER BY id DESC LIMIT 1");
$sql_anterior->execute([$id]);
$id_anterior = $sql_anterior->fetchColumn();

// Obtener el id del producto siguiente
$sql_siguiente = $con->prepare("SELECT id FROM productos WHERE id > ? AND activo = 1 ORDER BY id ASC LIMIT 1");
$sql_siguiente->execute([$id]);
$id_siguiente = $sql_siguiente->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chedraui</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link href="css/estilos.css" rel="stylesheet">
</head>

<body>

    <?php include 'menu.php'; ?>

    <main>
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <div id="carouselImages" class="carousel slide">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img src="<?php echo $rutaImg; ?>" class="d-block w-100"
                                    style="width: 300px; height: 600px;" alt="">
                            </div>
                            <?php foreach ($imagenes as $img) { ?>
                                <div class="carousel-item">
                                    <img src="<?php echo $img; ?>" class="d-block w-100"
                                        style="width: 300px; height: 600px;" alt="">
                                </div>
                            <?php } ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#carouselImages"
                            data-bs-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Previous</span>
                        </button>
                        <button class="carousel-control-next" type="button" data-bs-target="#carouselImages"
                            data-bs-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="visually-hidden">Next</span>
                        </button>
                    </div>
                </div>

                <div class="col-md-6">
                    <h2 class="d-inline"><?php echo $nombre; ?></h2>

                    <?php
                    // Consulta para obtener la categoría del producto
                    $sqlCategoriaProducto = "SELECT c.id, c.nombre FROM categorias c INNER JOIN productos p ON c.id = p.id_categoria WHERE p.id = :idProducto";

                    try {
                        $stmtCategoriaProducto = $con->prepare($sqlCategoriaProducto);
                        $stmtCategoriaProducto->bindParam(':idProducto', $idProducto, PDO::PARAM_INT);
                        $stmtCategoriaProducto->execute();

                        $resultCategoriaProducto = $stmtCategoriaProducto->fetch(PDO::FETCH_ASSOC);

                        if ($resultCategoriaProducto) {
                            $categoria = $resultCategoriaProducto; // Asigna el resultado a la variable $categoria
                            $nombreCategoria = $resultCategoriaProducto['nombre'];
                        } else {
                            $nombreCategoria = 'Categoría Desconocida';
                        }
                    } catch (PDOException $e) {
                        echo 'Error en la consulta de categoría del producto: ' . $e->getMessage();
                    }

                    ?>
                    <a href="ver_categorias.php?id=<?php echo $categoria['id']; ?>"
                        class="btn btn-success ms-2"><?php echo $nombreCategoria; ?></a>

                    <?php if ($descuento > 0) { ?>
                        <p><del><?php echo MONEDA . number_format($precio, 2, '.', ',') ?></del></p>
                        <h2><?php echo MONEDA . number_format($precio_desc, 2, '.', ',') ?>
                            <small class="text-success"><?php echo $descuento; ?>% de descuento</small>
                        </h2>
                    <?php } else { ?>
                        <h2><?php echo MONEDA . number_format($precio, 2, '.', ',') ?></h2>
                    <?php } ?>
                    <p class="lead">
                        <?php echo nl2br($descripcion); ?>
                    </p>
                    <div class="col-3 my-3">
                        Cantidad :<input class="form-control" id="cantidad" name="cantidad" type="number" min="1"
                            max="100" value="1">
                    </div>
                    <div class="d-grid gap-3 col-10 mx-auto">
                        <button class="btn btn-primary" type="button">Comprar ahora</button> 
                        <button class="btn btn-outline-primary" type="button"
                            onclick="addProducto(<?php echo $id; ?>, cantidad.value, '<?php echo $token_tmp; ?>')">Agregar
                            al Carrito</button>
                    </div>
                    <div class="d-flex justify-content-between mt-3">
                        <?php if ($id_anterior) { ?>
                            <a href="details.php?id=<?php echo $id_anterior; ?>&token=<?php echo hash_hmac('sha1', $id_anterior, KEY_TOKEN); ?>"
                                class="btn btn-primary">Anterior</a>
                        <?php } ?>
                        <?php if ($id_siguiente) { ?>
                            <a href="details.php?id=<?php echo $id_siguiente; ?>&token=<?php echo hash_hmac('sha1', $id_siguiente, KEY_TOKEN); ?>"
                                class="btn btn-primary">Siguiente</a>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
        crossorigin="anonymous"></script>

    <script>
        function addProducto(id, cantidad, token) {
            let url = 'clases/carrito.php'
            let formData = new FormData()
            formData.append('id', id)
            formData.append('cantidad', cantidad)
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