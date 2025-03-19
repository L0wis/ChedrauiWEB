<?php

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

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $usuario = $_POST['usuario'];

    $db = new Database();
    $con = $db->conectar();

    $sql = "UPDATE admin SET activo = 1 WHERE usuario = :usuario";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':usuario', $usuario, PDO::PARAM_STR);

    if ($stmt->execute()) {
        echo "Administrador restaurado correctamente.";
    } else {
        echo "Error al restaurar el administrador.";
    }
} else {
    header('Location: admin_desactivados.php');
    exit;
}

?>
