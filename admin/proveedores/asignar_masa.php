<?php
require '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productos = $_POST['productos_seleccionados'];
    $proveedor_id = $_POST['proveedor'];

    if (empty($productos) || empty($proveedor_id)) {
        header('Location: gestion_productos.php?error=Faltan datos');
        exit;
    }

    $productos_ids = explode(',', $productos);
    $db = new Database();
    $con = $db->conectar();

    foreach ($productos_ids as $producto_id) {
        $sql = "UPDATE productos SET id_proveedor = :proveedor_id WHERE id = :producto_id";
        $stmt = $con->prepare($sql);
        $stmt->execute([':proveedor_id' => $proveedor_id, ':producto_id' => $producto_id]);
    }

    header('Location: productos.php');
    exit;
}
?>
