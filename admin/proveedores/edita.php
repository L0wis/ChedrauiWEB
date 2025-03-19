<?php
ob_start();
error_reporting(E_ERROR | E_PARSE);

require '../config/database.php';
require '../config/config.php';
require '../header.php';

if (!isset($_SESSION['user_type'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SESSION['user_type'] != 'admin') {
    header('Location: ../../index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

$id = $_GET['id'];

// Obtener la información del proveedor
$sql = $con->prepare("SELECT id, nombre, nombre_contacto, direccion, ciudad, telefono, inicio_contrato, fin_contrato, tiempo_suministro, cantidad_suministro FROM proveedores WHERE id=? LIMIT 1");
$sql->execute([$id]);
$proveedor = $sql->fetch(PDO::FETCH_ASSOC);

// Ruta de la carpeta de imágenes de proveedores
$rutaImagenes = '../../images/proveedores/' . $id . '/';
$imagenProveedor = $rutaImagenes . 'proveedor.jpg';

// Crear la carpeta si no existe
if (!file_exists($rutaImagenes)) {
    mkdir($rutaImagenes, 0777, true);
}

// Procesar la eliminación de la imagen
if (isset($_POST['eliminar_imagen'])) {
    if (file_exists($imagenProveedor)) {
        unlink($imagenProveedor);
    }
    // Redireccionar después de la actualización
    header("Location: index.php");
    exit;
}

// Procesar el formulario al enviar
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Actualizar información del proveedor
    $nombre = $_POST['nombre'];
    $nombre_contacto = $_POST['nombre_contacto'];
    $direccion = $_POST['direccion'];
    $ciudad = $_POST['ciudad'];
    $telefono = $_POST['telefono'];
    $inicio_contrato = $_POST['inicio_contrato'];
    $fin_contrato = $_POST['fin_contrato'];
    $tiempo_suministro = $_POST['tiempo_suministro'];
    $cantidad_suministro = $_POST['cantidad_suministro'];

    // Formatear las fechas al formato YYYY-MM-DD
    $inicio_contrato = date('Y-m-d', strtotime($inicio_contrato));
    $fin_contrato = date('Y-m-d', strtotime($fin_contrato));

    // Verificar si se seleccionó la opción "Personalizado"
    if ($tiempo_suministro == "personalizado") {
        // Insertar el valor personalizado en la base de datos
        $tiempo_suministro_personalizado = $_POST['tiempo_suministro_personalizado'];
        $sqlInsertPersonalizado = $con->prepare("UPDATE proveedores SET tiempo_suministro=? WHERE id=?");
        $sqlInsertPersonalizado->execute([$tiempo_suministro_personalizado, $id]);
    }

    // Procesar la subida de la imagen
    if ($_FILES['imagen_proveedor']['name']) {
        // Ruta de la imagen del proveedor
        $imagenProveedor = $rutaImagenes . 'proveedor.jpg';

        // Mover la imagen subida a la carpeta
        move_uploaded_file($_FILES['imagen_proveedor']['tmp_name'], $imagenProveedor);
    }

    // Actualizar la información del proveedor en la base de datos
    $sqlUpdate = $con->prepare("UPDATE proveedores SET nombre=?, nombre_contacto=?, direccion=?, ciudad=?, telefono=?, inicio_contrato=?, fin_contrato=?, cantidad_suministro=? WHERE id=?");
    $sqlUpdate->execute([$nombre, $nombre_contacto, $direccion, $ciudad, $telefono, $inicio_contrato, $fin_contrato, $cantidad_suministro, $id]);

    // Redireccionar después de la actualización
    header("Location: vermas.php?id={$id}");
    exit;
}

?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Editar proveedor</h2>

        <form action="edita.php?id=<?php echo $id; ?>" method="post" enctype="multipart/form-data" autocomplete="off">
            <input type="hidden" name="id" value="<?php echo $proveedor['id']; ?>">

            <!-- Tarjeta 1: Información General -->
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Información General</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="imagen_proveedor" class="form-label">Imagen del proveedor</label>
                            <input type="file" class="form-control" name="imagen_proveedor" id="imagen_proveedor"
                                accept="image/jpeg, image/png, image/gif">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="ciudad" class="form-label">Ciudad</label>
                            <input type="text" class="form-control" name="ciudad" id="ciudad"
                                value="<?php echo $proveedor['ciudad']; ?>" required>
                        </div>
                    </div>
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="nombre" class="form-label">Nombre del proveedor</label>
                            <input type="text" class="form-control" name="nombre" id="nombre"
                                value="<?php echo $proveedor['nombre']; ?>" required autofocus>
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="nombre_contacto" class="form-label">Nombre de contacto</label>
                            <input type="text" class="form-control" name="nombre_contacto" id="nombre_contacto"
                                value="<?php echo $proveedor['nombre_contacto']; ?>" required>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 2: Detalles del Contrato -->
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Detalles del Contrato</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="inicio_contrato" class="form-label">Inicio Contrato</label>
                            <input type="date" class="form-control" name="inicio_contrato" id="inicio_contrato"
                                value="<?php echo date('Y-m-d', strtotime($proveedor['inicio_contrato'])); ?>">
                        </div>
                        <div class="mb-3 col-md-6">
                            <label for="fin_contrato" class="form-label">Fin Contrato</label>
                            <input type="date" class="form-control" name="fin_contrato" id="fin_contrato"
                                value="<?php echo date('Y-m-d', strtotime($proveedor['fin_contrato'])); ?>">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tarjeta 3: Suministro -->
            <div class="card mb-4">
                <div class="card-header">
                    <strong>Suministro</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="mb-3 col-md-6">
                            <label for="tiempo_suministro" class="form-label">Tiempo Suministro</label>
                            <select class="form-select" name="tiempo_suministro" id="tiempo_suministro">
                                <option value="Opción 1">Cada Lunes</option>
                                <option value="Opción 2">Cada Viernes</option>
                                <option value="Opción 3">Cada Domingo</option>
                                <option value="personalizado">Personalizado</option>
                            </select>
                        </div>
                        <div class="mb-3 col-md-6" id="personalizado" style="display: none;">
                            <label for="tiempo_suministro_personalizado" class="form-label">Personalizado</label>
                            <input type="text" class="form-control" name="tiempo_suministro_personalizado"
                                id="tiempo_suministro_personalizado">
                        </div>
                    </div>
                    <div class="mb-3 col-md-6">
                        <label for="cantidad_suministro" class="form-label">Cantidad Suministro</label>
                        <input type="text" class="form-control" name="cantidad_suministro" id="cantidad_suministro"
                            value="<?php echo $proveedor['cantidad_suministro']; ?>">
                    </div>
                </div>
            </div>

            <!-- Imagen Actual -->
            <?php if (file_exists($imagenProveedor)) { ?>
                <div class="text-center my-4">
                    <img src="<?php echo $imagenProveedor . '?id=' . time(); ?>" class="img-thumbnail"
                        alt="Imagen del proveedor" style="max-width: 300px; max-height: 300px;">
                    <button type="submit" class="btn btn-danger mt-2" name="eliminar_imagen">Eliminar Imagen</button>
                </div>
            <?php } ?>

            <!-- Botón Guardar -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var select = document.getElementById("tiempo_suministro");
        var personalizadoDiv = document.getElementById("personalizado");
        var inputPersonalizado = document.getElementById("tiempo_suministro_personalizado");

        select.addEventListener("change", function () {
            if (select.value === "personalizado") {
                personalizadoDiv.style.display = "block";
                inputPersonalizado.focus();
            } else {
                personalizadoDiv.style.display = "none";
                inputPersonalizado.value = "";
            }
        });
    });
</script>

<?php require '../footer.php'; ?>