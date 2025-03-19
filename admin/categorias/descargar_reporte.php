<?php
ob_start(); // Inicia el buffer de salida

require '../config/database.php';
require '../config/config.php';
require '../header.php';
require '../../fpdf186/fpdf.php';

if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: ../index.php');
    exit;
}

// Obtener el ID de la categoría desde la URL
if (isset($_GET['categoria_id'])) {
    $categoria_id = $_GET['categoria_id'];

    // Consulta para obtener el nombre de la categoría
    $db = new Database();
    $con = $db->conectar();

    $sql_categoria = "SELECT nombre FROM categorias WHERE id = :categoria_id AND activo = 1";
    $stmt_categoria = $con->prepare($sql_categoria);
    $stmt_categoria->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
    $stmt_categoria->execute();

    $nombre_categoria = $stmt_categoria->fetchColumn();

    // Consulta para obtener la cantidad total de productos en la categoría
    $sql_cantidad_productos = "SELECT COUNT(*) AS cantidad_productos FROM productos WHERE id_categoria = :categoria_id AND activo = 1";
    $stmt_cantidad_productos = $con->prepare($sql_cantidad_productos);
    $stmt_cantidad_productos->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
    $stmt_cantidad_productos->execute();

    $cantidad_productos = $stmt_cantidad_productos->fetchColumn();

    // Consulta para obtener los productos de la categoría seleccionada
    $sql_productos = "SELECT id, nombre, precio, stock FROM productos WHERE id_categoria = :categoria_id AND activo = 1";
    $stmt_productos = $con->prepare($sql_productos);
    $stmt_productos->bindParam(':categoria_id', $categoria_id, PDO::PARAM_INT);
    $stmt_productos->execute();

    $productos = $stmt_productos->fetchAll(PDO::FETCH_ASSOC);

    if (empty($productos)) {
        echo 'No hay productos disponibles en esta categoría.';
        exit;
    }

    // Generar el informe PDF
    $pdf = new FPDF();
    $pdf->SetAutoPageBreak(true, 10);  // Nueva línea añadida para manejar el salto de página automáticamente
    
    // Resto del código...
    
    // Cambiar el tamaño de la página a un tamaño personalizado
    $pdf->AddPage('P', array(230, 355)); // Ancho: 230mm, Alto: 355mm (tamaño A4)

    $pdf->SetFont('Arial', 'B', 16);
    $pdf->Cell(0, 10, 'Reporte de Productos - Categoria: ' . $nombre_categoria, 0, 1, 'C');

    // Agregar información adicional
    $pdf->SetFont('Arial', 'B', 12); // Negrita
    $pdf->Cell(0, 10, 'Fecha de corte: ' . date('Y-m-d H:i:s'), 0, 1, 'C');
    $pdf->Cell(0, 10, 'Cantidad de Productos: ' . count($productos), 0, 1, 'C');
    $pdf->Cell(0, 10, 'Realizado por: Chedraui-Admin', 0, 1, 'C');

    $pdf->Ln(2);  // Añadir espacio entre la información y la tabla

    // Encabezados de la tabla
    $pdf->SetFont('Arial', 'B', 12);

    // Anchos de las columnas
    $nombreWidth = 90;
    $precioWidth = 45;
    $stockWidth = 35;

    // Calcular el ancho total de la tabla
    $tableWidth = $nombreWidth + $precioWidth + $stockWidth;

    // Calcular la posición para centrar la tabla
    $pageWidth = $pdf->GetPageWidth();
    $tableX = ($pageWidth - $tableWidth) / 3;

    // Añadir espacio antes de la tabla
    $pdf->Cell($tableX);

    // Crear las celdas de encabezado centradas
    $pdf->Cell($nombreWidth, 10, 'Nombre', 1, 0, 'C');
    $pdf->Cell($precioWidth, 10, 'Precio', 1, 0, 'C');
    $pdf->Cell($stockWidth, 10, 'Stock', 1, 1, 'C');

    foreach ($productos as $producto) {
        // Añadir espacio antes de cada fila de la tabla
        $pdf->Cell($tableX);

        // Crear las celdas de datos centradas
        $pdf->Cell($nombreWidth, 10, utf8_decode($producto['nombre']), 1, 0, 'C');
        $pdf->Cell($precioWidth, 10, $producto['precio'], 1, 0, 'C');
        $pdf->Cell($stockWidth, 10, $producto['stock'], 1, 1, 'C');
    }

    ob_clean();

    $pdf->Output();
    exit;
} else {
    // Redirigir si no se proporciona el ID de la categoría
    header('Location: ../index.php');
    exit;
}
?>
