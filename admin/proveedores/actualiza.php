<?php
ob_start();

require '../config/database.php';
require '../config/config.php';
require '../header.php';

// Verificar el tipo de usuario
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../../index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$nombre_contacto = $_POST['nombre_contacto'];
$direccion = $_POST['direccion'];
$ciudad = $_POST['ciudad'];
$telefono = $_POST['telefono'];

// Nuevas columnas
$inicio_contrato = $_POST['inicio_contrato'];
$fin_contrato = $_POST['fin_contrato'];
$tiempo_suministro = $_POST['tiempo_suministro'];
$cantidad_suministro = $_POST['cantidad_suministro'];

// Actualizar los datos del proveedor en la base de datos
$sql = "UPDATE proveedores SET nombre = :nombre, nombre_contacto = :nombre_contacto, direccion = :direccion, ciudad = :ciudad, telefono = :telefono, inicio_contrato = :inicio_contrato, fin_contrato = :fin_contrato, tiempo_suministro = :tiempo_suministro, cantidad_suministro = :cantidad_suministro WHERE id = :id";
$stmt = $con->prepare($sql);
$stmt->bindParam(':nombre', $nombre);
$stmt->bindParam(':nombre_contacto', $nombre_contacto);
$stmt->bindParam(':direccion', $direccion);
$stmt->bindParam(':ciudad', $ciudad);
$stmt->bindParam(':telefono', $telefono);
$stmt->bindParam(':inicio_contrato', $inicio_contrato);
$stmt->bindParam(':fin_contrato', $fin_contrato);
$stmt->bindParam(':tiempo_suministro', $tiempo_suministro);
$stmt->bindParam(':cantidad_suministro', $cantidad_suministro);
$stmt->bindParam(':id', $id);
$stmt->execute();

header('Location: index.php');
exit;
?>
