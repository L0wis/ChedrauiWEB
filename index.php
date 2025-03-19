<?php

// Evitar el almacenamiento en caché del navegador
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false); // Compatibilidad con IE
header("Pragma: no-cache"); // HTTP/1.0


require_once 'config/config.php';

// Verifica si el usuario está logeado
if (!isset($_SESSION['user_logged_in'])) {
    // Si no está logeado, verifica si ha sido redirigido antes
    if (!isset($_SESSION['redirected_to_xmenu'])) {
        // Si no ha sido redirigido antes, redirígelo a xmenu.php
        $_SESSION['redirected_to_xmenu'] = true;
        header('Location: xmenu.php');
        exit;
    }
}

require_once 'config/config.php';

$db = new Database();
$con = $db->conectar();

// Verifica si se ha enviado el formulario y el parámetro 'orden' está presente
if(isset($_GET['orden'])) {
    $orden = $_GET['orden'];
    switch($orden) {
        case 'precio_alto':
            $sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE activo=1 ORDER BY precio DESC");
            break;
        case 'precio_bajo':
            $sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE activo=1 ORDER BY precio ASC");
            break;
        case 'asc':
            $sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE activo=1 ORDER BY nombre ASC");
            break;
        case 'desc':
            $sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE activo=1 ORDER BY nombre DESC");
            break;
        default:
            // Si el valor del parámetro 'orden' no es válido, ejecutar la consulta SQL predeterminada
            $sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE activo=1");
            break;
    }
    $sql->execute();
    $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Si no se ha seleccionado ningún orden, ejecutar la consulta SQL predeterminada
    $sql = $con->prepare("SELECT id, nombre, precio FROM productos WHERE activo=1");
    $sql->execute();
    $resultado = $sql->fetchAll(PDO::FETCH_ASSOC);
}

// Realizar la consulta SQL para obtener la cantidad total de productos activos
$sqlCantidadProductos = "SELECT COUNT(*) as total FROM productos WHERE activo = 1";
$resultCantidadProductos = $con->query($sqlCantidadProductos);
$totalProductos = $resultCantidadProductos->fetch(PDO::FETCH_ASSOC)['total'];
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chedraui</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link href="css/estilos.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

</head>
<body>

<?php include 'menu.php'; ?>

<main>
<div class="container">
    <!-- Productos Totales y Menú de Ordenar -->
    <div class="row mb-3">
        <!-- Columna para la cantidad de productos totales -->
        <div class="col-md-9">
            <h1 class="mb-0">Productos Totales (<?php echo $totalProductos; ?>)</h1>
        </div>
        <!-- Columna para el texto "Ordenar por" y el menú de selección de orden -->
        <div class="col-md-3 d-flex align-items-center">
            <span class="me-2 mb-0">Ordenar por:</span>
            <form id="ordenForm" action="" method="GET" class="flex-grow-1">
                <div class="me-2">
                    <select class="form-select" name="orden" id="orden" onchange="document.getElementById('ordenForm').submit()">
                        <option value="precio_alto" <?php echo isset($_GET['orden']) && $_GET['orden'] == 'precio_alto' ? 'selected' : ''; ?>>Precios más altos</option>
                        <option value="precio_bajo" <?php echo isset($_GET['orden']) && $_GET['orden'] == 'precio_bajo' ? 'selected' : ''; ?>>Precios más bajos</option>
                        <option value="asc" <?php echo isset($_GET['orden']) && $_GET['orden'] == 'asc' ? 'selected' : ''; ?>>Nombre A-Z</option>
                        <option value="desc" <?php echo isset($_GET['orden']) && $_GET['orden'] == 'desc' ? 'selected' : ''; ?>>Nombre Z-A</option>
                    </select>
                </div>
            </form>
        </div>
    </div>
    <!-- Línea negra como separador -->
    <hr class="mb-4 border-bottom">
    <!-- Productos -->
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-3">
        <?php foreach($resultado as $row){ ?>
            <div class="col">
                <div class="card shadow-sm">
                    <?php 
                    $id = $row['id'];
                    $imagen = "images/productos/". $id . "/principal.jpg";

                    if (!file_exists($imagen)) {
                        $imagen = "images/no-photo.jpeg";
                    }
                    ?>
                    <img src="<?php echo $imagen; ?>" class="d-block">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo $row['nombre']; ?> </h5>
                        <p class="card-text">$ <?php echo number_format($row['precio'], 2, '.', ','); ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="btn-group">
                                <a href="details.php?id=<?php echo $row['id']; ?>&token=<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>" 
                                    class="btn btn-primary">Detalles</a>
                            </div>
                            <button type="button" class="btn btn-outline-success" onclick="agregarYMostrarToast(<?php echo $row['id']; ?>, '<?php echo hash_hmac('sha1', $row['id'], KEY_TOKEN); ?>')">Agregar al Carrito</button>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

</main>

<?php include 'footer.php'; ?>
    
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
        <img src="images/carrito.jpeg" class="rounded me-2" alt="Icono de carrito de compras" width="50" height="50">
            <strong class="me-auto">ChedraWuicho</strong>
            <small>Justo ahora</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            ¡Producto agregado al carrito!
        </div>
    </div>
</div>

<script>
    // Función para agregar productos al carrito
    function addProducto(id, token) {
        let url = 'clases/carrito.php';
        let formData = new FormData();
        formData.append('id', id);
        formData.append('token', token);

        fetch(url, {
            method: 'POST',
            body: formData,
            mode: 'cors'
        }).then(response => response.json())
        .then(data => {
            if(data.ok){
                let elemento = document.getElementById("num_cart");
                elemento.innerHTML = data.numero;
            } else {
                alert("No hay suficientes existencias");
            }
        });
    }

    // Función para agregar al carrito y mostrar el toast con duración personalizada
    function agregarYMostrarToast(id, token) {
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
                let toast = new bootstrap.Toast(document.getElementById('liveToast'), {
                    delay: 3000 // Duración del toast en milisegundos (3 segundos)
                });
                toast.show();
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
