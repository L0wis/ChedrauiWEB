<?php

ob_start();  // Agrega esta línea al inicio

require '../config/database.php';
require '../config/config.php';
require '../header.php';

if (!isset($_SESSION['user_type'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SESSION['user_type'] != 'admin') {
    header('Location: clientes.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

$id = $_POST['id'];

$sql = $con->prepare("UPDATE clientes c
                      INNER JOIN usuarios u ON c.id = u.id
                      SET c.estatus = 0, u.activacion = 0
                      WHERE c.id = ?");

if ($sql->execute([$id])) {
    echo "Cliente desactivado correctamente.";
} else {
    echo "Error al desactivar el cliente.";
}

header('Location: clientes.php');
