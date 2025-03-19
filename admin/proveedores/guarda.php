<?php
ob_start();  // Agrega esta línea al inicio

require '../config/database.php';
require '../config/config.php';
require '../header.php';

// Verificar el tipo de usuario
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../../index.php');
    exit;
}

// Conectar a la base de datos
$db = new Database();
$con = $db->conectar();

// Capturar los datos del formulario
$nombre = $_POST['nombre'];
$nombre_contacto = $_POST['nombre_contacto'];
$direccion = $_POST['direccion'];
$ciudad = $_POST['ciudad'];
$telefono = $_POST['telefono'];
$inicio_contrato = date('Y-m-d', strtotime($_POST['inicio_contrato']));
$fin_contrato = date('Y-m-d', strtotime($_POST['fin_contrato']));
$tiempo_suministro = $_POST['tiempo_suministro'];
$cantidad_suministro = $_POST['cantidad_suministro'];
// Capturar el valor de tiempo_suministro_personalizado si existe
$tiempo_suministro_personalizado = isset($_POST['tiempo_suministro_personalizado']) ? $_POST['tiempo_suministro_personalizado'] : null;

// Si tiempo_suministro es "personalizado" y se proporciona un valor personalizado, usarlo, de lo contrario, usar el valor de tiempo_suministro
if ($tiempo_suministro === 'personalizado' && $tiempo_suministro_personalizado !== null) {
    $tiempo_suministro_final = $tiempo_suministro_personalizado;
} else {
    $tiempo_suministro_final = $tiempo_suministro;
}

// Insertar los datos en la tabla de proveedores
$sql = "INSERT INTO proveedores (nombre, nombre_contacto, direccion, ciudad, telefono, inicio_contrato, fin_contrato, tiempo_suministro, cantidad_suministro) 
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
$stm = $con->prepare($sql);

// Ejecutar la consulta preparada
if ($stm->execute([$nombre, $nombre_contacto, $direccion, $ciudad, $telefono, $inicio_contrato, $fin_contrato, $tiempo_suministro_final, $cantidad_suministro])) {
    // Obtener el ID del proveedor recién insertado
    $id_proveedor = $con->lastInsertId();

    // Resto del código para manejar las imágenes y cualquier otra operación necesaria


    // Preparar la información de la imagen principal
    $imagen_principal_name = $_FILES['imagen']['name'];
    $imagen_principal_tmp = $_FILES['imagen']['tmp_name'];
    $imagen_principal_size = $_FILES['imagen']['size'];

    // Validar que se haya seleccionado una imagen
    if (!empty($imagen_principal_name)) {
        // Obtener la extensión del archivo
        $imagen_principal_extension = strtolower(pathinfo($imagen_principal_name, PATHINFO_EXTENSION));

        // Ruta de la carpeta de imágenes de proveedores
        $rutaImagenes = '../../images/proveedores/' . $id_proveedor . '/';
        $imagenProveedor = $rutaImagenes . 'proveedor.jpg';

        // Crear la carpeta si no existe
        if (!file_exists($rutaImagenes)) {
            mkdir($rutaImagenes, 0777, true);
        }

        // Generar un nombre único para la imagen
        $imagen_principal_name = 'proveedor.' . $imagen_principal_extension;

        // Ruta completa donde se guardará la imagen
        $ruta_img = $rutaImagenes . $imagen_principal_name;

        // Mover el archivo temporal al directorio de destino
        if (move_uploaded_file($imagen_principal_tmp, $ruta_img)) {
            echo "La imagen principal se cargó correctamente.";
        } else {
            echo "Error al cargar la imagen principal.";
        }
    } else {
        echo "No se ha seleccionado una imagen principal.";
    }

    // Subir otras imágenes (si se proporcionan)
    if (isset($_FILES['otras_imagenes'])) {
        $permitidos = ['jpeg', 'jpg', 'png'];

        $contador = 1;
        foreach ($_FILES['otras_imagenes']['tmp_name'] as $key => $tmp_name) {
            $filename = $_FILES['otras_imagenes']['name'][$key];
            $arregloImagen = explode('.', $filename);
            $extension = strtolower(end($arregloImagen));

            if (in_array($extension, $permitidos)) {
                $ruta_img = $rutaImagenes . $contador . '.' . $extension;
                if (move_uploaded_file($tmp_name, $ruta_img)) {
                    echo "El archivo se cargó correctamente.<br>";
                    $contador++;
                } else {
                    echo "Error al cargar el archivo.";
                }
            } else {
                echo "Archivo no permitido";
            }
        }
    }
}

// Redirigir al usuario a la página de inicio
header('Location: index.php');
?>
