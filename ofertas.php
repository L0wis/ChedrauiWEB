<?php

require_once 'config/config.php';

$db = new Database();
$con = $db->conectar();

// Determinar el tipo de orden seleccionado por el usuario (por defecto, orden alfabético ascendente)
$tipoOrden = isset($_GET['orden']) ? $_GET['orden'] : 'nombre_asc';
// Determinar la categoría seleccionada por el usuario (por defecto, todas las categorías)
$categoriaSeleccionada = isset($_GET['categoria']) ? $_GET['categoria'] : 'all';

// Consulta SQL para seleccionar productos con descuento y ordenar según el tipo seleccionado
switch ($tipoOrden) {
    case 'nombre_asc':
        $ordenSQL = 'ORDER BY nombre ASC';
        break;
    case 'nombre_desc':
        $ordenSQL = 'ORDER BY nombre DESC';
        break;
    case 'descuento_desc':
        $ordenSQL = 'ORDER BY descuento DESC';
        break;
    default:
        $ordenSQL = 'ORDER BY nombre ASC';
}

// Consulta SQL para obtener las categorías disponibles
$sqlCategorias = $con->prepare("SELECT id, nombre FROM categorias WHERE activo = 1");
$sqlCategorias->execute();
$categorias = $sqlCategorias->fetchAll(PDO::FETCH_ASSOC);

// Consulta SQL para seleccionar productos con descuento y ordenar según el tipo y filtrar por categoría
$sql = $con->prepare("SELECT id, nombre, precio, descuento FROM productos WHERE descuento > 0 AND activo = 1 $ordenSQL");
if ($categoriaSeleccionada != 'all') {
    $sql = $con->prepare("SELECT id, nombre, precio, descuento FROM productos WHERE descuento > 0 AND activo = 1 AND id_categoria = :categoria $ordenSQL");
    $sql->bindParam(':categoria', $categoriaSeleccionada, PDO::PARAM_INT);
}
$sql->execute();
$productos = $sql->fetchAll(PDO::FETCH_ASSOC);

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
            <div class="text-center">
                <h2 class="mb-3">OFERTAS DEL MES
                    <span class="badge bg-secondary"><?php echo count($productos); ?> ofertas</span>
                </h2>
            </div>
            <hr style="width: 100%; border-bottom: 2px solid #000;"> <!-- Línea horizontal -->
            <div class="text-end mb-3">
                <label for="orden">Ordenar por:</label>
                <select id="orden" onchange="cambiarOrden()">
                    <option value="nombre_asc" <?php echo $tipoOrden == 'nombre_asc' ? 'selected' : ''; ?>>Nombre (A-Z)
                    </option>
                    <option value="nombre_desc" <?php echo $tipoOrden == 'nombre_desc' ? 'selected' : ''; ?>>Nombre (Z-A)
                    </option>
                    <option value="descuento_desc" <?php echo $tipoOrden == 'descuento_desc' ? 'selected' : ''; ?>>Mayor
                        descuento</option>
                </select>
                <label for="categoria" class="ms-3">Categoría:</label>
                <select id="categoria" onchange="cambiarCategoria()">
                    <option value="all" <?php echo $categoriaSeleccionada == 'all' ? 'selected' : ''; ?>>Todas las
                        categorías</option>
                    <?php foreach ($categorias as $categoria) { ?>
                        <option value="<?php echo $categoria['id']; ?>" <?php echo $categoriaSeleccionada == $categoria['id'] ? 'selected' : ''; ?>><?php echo $categoria['nombre']; ?></option>
                    <?php } ?>
                </select>
            </div>
            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
                <?php foreach ($productos as $row) { ?>
                    <div class="col">
                        <div class="card shadow-sm">
                            <?php
                            $id = $row['id'];
                            $imagen = "images/productos/" . $id . "/principal.jpg";
                            if (!file_exists($imagen)) {
                                $imagen = "images/no-photo.jpeg";
                            }
                            $precio_original = $row['precio'];
                            $descuento = $row['descuento'];
                            $precio_descuento = $precio_original - (($precio_original * $descuento) / 100);
                            ?>
                            <img src="<?php echo $imagen; ?>" class="d-block" style="width: 428px; height: 350px;">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $row['nombre']; ?></h5>
                                <p class="card-text">
                                    $ <?php echo number_format($precio_descuento, 2, '.', ','); ?>
                                    <?php if ($descuento > 0) { ?>
                                        <span class="text-success"><?php echo $descuento; ?>% de descuento</span>
                                    <?php } ?>
                                </p>
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="details.php?id=<?php echo $row['id']; ?>&token=<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>"
                                            class="btn btn-primary">Detalles</a>
                                    </div>
                                    <button class="btn btn-outline-success" type="button"
                                        onclick="addProducto(<?php echo $row['id']; ?>, '<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>')">
                                        Agregar al Carrito
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>
    </main>
    <?php include 'footer.php'; ?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
        crossorigin="anonymous"></script>
    <script>
        function cambiarOrden() {
            var tipoOrden = document.getElementById('orden').value;
            var url = window.location.pathname + '?orden=' + tipoOrden;
            <?php if ($categoriaSeleccionada != 'all') { ?>
                url += '&categoria=<?php echo $categoriaSeleccionada; ?>';
            <?php } ?>
            window.location.href = url;
        }

        function cambiarCategoria() {
            var categoria = document.getElementById('categoria').value;
            var url = window.location.pathname + '?categoria=' + categoria;
            <?php if ($tipoOrden != 'nombre_asc') { ?>
                url += '&orden=<?php echo $tipoOrden; ?>';
            <?php } ?>
            window.location.href = url;
        }
    </script>
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
                    }
                })
        }
    </script>
</body>

</html>