<?php

require '../config/database.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $ajuste_id = $_POST['id']; // ID recibido desde el formulario del modal

    if (!empty($ajuste_id)) {
        $db = new Database();
        $con = $db->conectar();

        // Obtener datos del ajuste
        $sql_ajuste = "SELECT * FROM ajustes_precios WHERE id = ? AND activo = 1";
        $stmt_ajuste = $con->prepare($sql_ajuste);
        $stmt_ajuste->execute([$ajuste_id]);
        $ajuste = $stmt_ajuste->fetch(PDO::FETCH_ASSOC);

        if ($ajuste) {
            $producto_id = $ajuste['producto_id'];
            $tipo_ajuste = $ajuste['tipo_ajuste'];
            $valor = $ajuste['valor'];
            $tipo_valor = $ajuste['tipo_valor'];

            // Obtener el precio actual del producto
            $sql_producto = "SELECT precio FROM productos WHERE id = ?";
            $stmt_producto = $con->prepare($sql_producto);
            $stmt_producto->execute([$producto_id]);
            $producto = $stmt_producto->fetch(PDO::FETCH_ASSOC);

            if ($producto) {
                $precio_actual = $producto['precio'];

                // Calcular el precio original antes del ajuste
                if ($tipo_valor == 'porcentaje') {
                    if ($tipo_ajuste == 'incremento') {
                        $precio_original = $precio_actual / (1 + ($valor / 100));
                    } elseif ($tipo_ajuste == 'descuento') {
                        $precio_original = $precio_actual / (1 - ($valor / 100));
                    }
                } else {
                    if ($tipo_ajuste == 'incremento') {
                        $precio_original = $precio_actual - $valor;
                    } elseif ($tipo_ajuste == 'descuento') {
                        $precio_original = $precio_actual + $valor;
                    }
                }

                // Restaurar el precio original en la tabla de productos
                $sql_update_producto = "UPDATE productos SET precio = ? WHERE id = ?";
                $stmt_update_producto = $con->prepare($sql_update_producto);
                $stmt_update_producto->execute([round($precio_original, 2), $producto_id]);

                // Cambiar el estado del ajuste a inactivo (activo = 0)
                $sql_update_ajuste = "UPDATE ajustes_precios SET activo = 0 WHERE id = ?";
                $stmt_update_ajuste = $con->prepare($sql_update_ajuste);
                $stmt_update_ajuste->execute([$ajuste_id]);

                // Redirigir con mensaje de éxito
                header('Location: index.php?status=success');
                exit;
            }
        }
    }

    // Redirigir con mensaje de error si algo falla
    header('Location: index.php?status=error');
    exit;
}
?>


