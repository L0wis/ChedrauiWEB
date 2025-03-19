<?php

require '../config/database.php';
require '../config/config.php';
require '../header.php';

if (!isset($_SESSION['user_type'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SESSION['user_type'] != 'admin' ){
    header('Location: ../../index.php');
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];

    $db = new Database();
    $con = $db->conectar();

    // Actualizar el estado activo del personal a 1 (activo)
    $sql = "UPDATE personal SET activo = 1 WHERE id = :id";
    $stmt = $con->prepare($sql);
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: personal.php");
    exit;
} else {
    header("Location: personal.php");
    exit;
}

?>
