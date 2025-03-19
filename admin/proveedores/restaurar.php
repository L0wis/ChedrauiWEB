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

$db = new Database();
$con = $db->conectar();

$id = $_GET['id']; // Cambiado de $_POST a $_GET ya que se espera un parámetro en la URL

$sql = $con->prepare("UPDATE proveedores SET activo = 1 WHERE id = ?");
$sql->execute([$id]);

header('Location: proveedores_desactivados.php');
