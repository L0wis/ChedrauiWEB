<?php

ob_start();

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

// Establecer la zona horaria a México
date_default_timezone_set('America/Mexico_City');

// Manejo del formulario de creación de respaldo
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Recolección de los datos del formulario
    $nombreUsuario = $_POST['nombre_usuario'];
    $nombreArchivo = $_POST['nombre_archivo'];
    $ubicacion = $_POST['ubicacion'];
  //  $usuario = $_SESSION['username']; // Suponiendo que tienes el nombre de usuario en la sesión
    $descripcion = $_POST['descripcion'];
    $comentarios = $_POST['comentarios'];

// Insertar los datos del respaldo en la base de datos
$db = new Database();
$con = $db->conectar();

$sql = "INSERT INTO backups (fecha_hora, nombre_archivo, ubicacion, descripcion, comentarios, estado, usuario, activo) VALUES (NOW(), ?, ?, ?, ?, 'pendiente', ?, 1)";
$stmt = $con->prepare($sql);
$resultado = $stmt->execute([$nombreArchivo, $ubicacion, $descripcion, $comentarios, $nombreUsuario]);

// Establecer el estado según el resultado de la inserción
$estado = $resultado ? 'Exito' : 'fallido';

if ($resultado) {
    // Si la inserción fue exitosa, actualiza el estado a "exitoso"
    $sql_update = "UPDATE backups SET estado = 'Exito' WHERE nombre_archivo = ?";
    $stmt_update = $con->prepare($sql_update);
    $stmt_update->execute([$nombreArchivo]);
}

// Resto del código...

    // Datos para la conexión a la base de datos
    $db_host = 'localhost'; // Host del Servidor MySQL
    $db_name = 'chedraui'; // Nombre de la Base de datos
    $db_user = 'root'; // Usuario de MySQL
    $db_pass = 'louisfelipe'; // Password de Usuario MySQL

    // Fecha y hora para identificar el respaldo
    $fechaHora = date("Ymd-His");

    // Ruta de la carpeta donde se guardará el respaldo
    $rutaCarpeta = '../backups/respaldos/' . date("Ymd");

    // Verificar si la carpeta existe, si no, crearla
    if (!file_exists($rutaCarpeta)) {
        mkdir($rutaCarpeta, 0777, true);
    }

    // Construcción del nombre de archivo SQL Ejemplo: basededatos_20240513-080000.sql
    $nombreArchivoSQL = $rutaCarpeta . '/' . $db_name . '_' . $fechaHora . '.sql';

    // Comando para generar el respaldo de MySQL
    $dump = "C:/wamp64/bin/mysql/mysql8.0.31/bin/mysqldump -h{$db_host} -u{$db_user} -p{$db_pass} --opt {$db_name} > {$nombreArchivoSQL}";
    exec($dump, $output, $return_var);

    // Verificar si el comando se ejecutó correctamente
    if ($return_var === 0) {
        // Compresión en ZIP
        $zip = new ZipArchive();
        $nombreZIP = $rutaCarpeta . '/' . $db_name . '_' . $fechaHora . '.zip';
        
        if ($zip->open($nombreZIP, ZIPARCHIVE::CREATE) === true) {
            $zip->addFile($nombreArchivoSQL, basename($nombreArchivoSQL));
            $zip->close();
          //  unlink($nombreArchivoSQL); // Eliminamos el archivo temporal SQL
        } else {
            echo 'Error al crear el archivo ZIP';
        }

                // Enlace para descargar el archivo ZIP
                $rutaDescarga = str_replace('../', '', $nombreZIP);
                echo '<a href="' . $rutaDescarga . '" download>Descargar Respaldo</a>';        

        // Redirección para descargar el archivo ZIP
        header('Location: index.php');
        exit;
    } else {
        echo 'Error al generar el respaldo de la base de datos';
    }
}

?>


<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Crear Respaldo de Base de Datos</h2>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="mb-3">
                <label for="nombre_usuario" class="form-label">Nombre de Usuario</label>
                <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" required>
            </div>
            <div class="mb-3">
                <label for="nombre_archivo" class="form-label">Nombre Archivo</label>
                <!-- Generar el nombre de archivo según la fecha -->
                <?php
                    $nombreArchivoSQL = 'chedraui_' . date('Ymd_His') . '.sql';
                ?>
                <input type="text" class="form-control" id="nombre_archivo" name="nombre_archivo" value="<?php echo $nombreArchivoSQL; ?>" readonly>
            </div>
            <div class="mb-3">
                <label for="ubicacion" class="form-label">Ubicación</label>
                <!-- Establecer la ubicación predeterminada -->
                <input type="text" class="form-control" id="ubicacion" name="ubicacion" value="../../backups/respaldos/+fecha" readonly>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción</label>
                <!-- Campo de descripción -->
                <input type="text" class="form-control" id="descripcion" name="descripcion" value="Respaldo TOTAL de la base de datos" readonly>
            </div>
            <div class="mb-3">
                <label for="comentarios" class="form-label">Comentarios</label>
                <textarea class="form-control" id="comentarios" name="comentarios" rows="3"></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Crear Respaldo</button>
        </form>
    </div>
</main>

<?php require '../footer.php'; ?>
