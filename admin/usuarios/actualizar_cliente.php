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
    header('Location: ../../index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

$id = $_POST['id'];
$nombres = $_POST['nombres'];
$apellidos = $_POST['apellidos'];
$email = $_POST['email'];
$dni = $_POST['dni'];
$telefono = $_POST['telefono'];
$fecha_alta = $_POST['fecha_alta'];
$estatus = $_POST['estatus'];

$sql = "UPDATE clientes SET nombres = ?, apellidos = ?, email = ?, dni = ?, telefono = ?, fecha_alta = ?, estatus = ? WHERE id = ?"; 
$stm = $con->prepare($sql);

if ($stm->execute([$nombres, $apellidos, $email, $dni, $telefono, $fecha_alta, $estatus, $id])) {
    echo "Cliente actualizado correctamente.";
} else {
    echo "Error al actualizar el cliente.";
}

header('Location: clientes.php');
