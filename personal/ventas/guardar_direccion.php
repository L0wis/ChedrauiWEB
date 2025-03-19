<?php
// Conectar a la base de datos
require '../config/config.php';
require '../header.php';

// Definir $id_venta, $direccion y $referencias
$id_venta = isset($_GET['id_venta']) ? $_GET['id_venta'] : null;
$direccion = isset($_GET['direccion']) ? $_GET['direccion'] : null;
$direccion_manual = isset($_GET['direccion_manual']) ? $_GET['direccion_manual'] : null;
$direccion_manual_hidden = isset($_GET['direccion_manual_hidden']) ? $_GET['direccion_manual_hidden'] : null;
$referencias = isset($_GET['referencias']) ? $_GET['referencias'] : null;

// Verificar si se proporcionó un ID de venta
if (!$id_venta) {
    die("ID de venta no proporcionado");
}

// Si se proporciona una dirección manual, usarla en lugar de la dirección de coordenadas
if ($direccion_manual) {
    $direccion = $direccion_manual;
}

// Asignar la dirección a la sesión
$_SESSION['direccionManual'] = $direccion;

// Actualizar la dirección y las referencias en la base de datos si hay una dirección válida
if ($direccion) {
    try {
        // Conectar a la base de datos
        $db = new Database();
        $con = $db->conectar();

        // Preparar la consulta SQL para actualizar la dirección y las referencias en la tabla compra_personal
        $sql_actualizar_direccion = $con->prepare("UPDATE compra_personal SET direccion = ?, referencias = ? WHERE id = ?");
        $sql_actualizar_direccion->execute([$direccion, $referencias, $id_venta]);
    } catch (PDOException $e) {
        echo "Error al actualizar la dirección en la base de datos: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos Cargados</title>
    <!-- Estilos CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            margin: 0;
            padding: 20px;
        }

        .message {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            margin-top: 20px;
            border-radius: 10px;
        }

        .btn {
            padding: 10px 20px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .btn-green {
            background-color: #4CAF50;
            color: white;
        }
    </style>
</head>

<body>
    <h1>Tus productos ya están siendo cargados</h1>
    <?php if (isset($_SESSION['direccionManual'])): ?>
        <p>Tus productos serán entregados a:</p>
        <p><?php echo $_SESSION['direccionManual']; ?></p>
    <?php endif; ?>
    
    <p>Nos vemos pronto.</p>

    <a href="recibo_envio.php?id_venta=<?php echo htmlspecialchars($id_venta, ENT_QUOTES, 'UTF-8'); ?>"><button class="btn btn-green">Ver Recibo</button></a>
        <a href="index.php"><button class="btn btn-green">Ir al Inicio</button></a>
    </p>
    <!-- Botones adicionales -->
    <div>
    </div>
</body>

</html>
