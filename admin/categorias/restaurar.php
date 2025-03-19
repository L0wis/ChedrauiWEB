<?php

require '../config/database.php';
require '../config/config.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('Location: ../../index.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);

    $db = new Database();
    $con = $db->conectar();

    $sql = "UPDATE categorias SET activo = 1 WHERE id = ?";
    $query = $con->prepare($sql);
    $query->execute([$id]);

    header('Location: index.php');
    exit;
}
?>
