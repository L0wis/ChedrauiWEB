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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $db = new Database();
    $con = $db->conectar();

    $id = $_POST['id'];

    // Actualizar el estatus del cliente y la activación del usuario
    $sql = $con->prepare("UPDATE clientes c
                          INNER JOIN usuarios u ON c.id = u.id
                          SET c.estatus = 1, u.activacion = 1
                          WHERE c.id = ? AND c.estatus = 0 AND u.activacion = 0");
    $sql->execute([$id]);

    header('Location: clientes.php');
    exit;
} else {
    header('Location: clientes.php');
    exit;
}

?>
