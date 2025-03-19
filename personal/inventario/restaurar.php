<?php

ob_start();

require '../config/config.php';
require '../header.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

$id = $_GET['id']; // Cambiado de $_POST a $_GET ya que se espera un parámetro en la URL

$sql = $con->prepare("UPDATE productos SET activo = 1 WHERE id = ?");
$sql->execute([$id]);

header('Location: productos_desactivados.php');
