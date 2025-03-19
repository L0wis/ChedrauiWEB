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
$nombre = $_POST['nombre'];
$descripcion = $_POST['descripcion'];  // Agrega esta línea para obtener la descripción desde el formulario

// Actualizar tanto el nombre como la descripción
$sql = $con->prepare("UPDATE categorias SET nombre = ?, descripcion = ? WHERE id = ?");
$sql->execute([$nombre, $descripcion, $id]);

header('Location: index.php');
