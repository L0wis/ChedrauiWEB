<?php
// Incluir las configuraciones y clases necesarias
require_once 'config/config.php';
require_once 'config/database.php';
require_once 'TCPDF-main/tcpdf.php';

// Lógica para obtener datos (puedes ajustar según tus necesidades)
$db = new Database();
$con = $db->conectar();

$sql = "SELECT c.nombre as categoria, COUNT(p.id) as cantidad_productos
        FROM categorias c
        LEFT JOIN productos p ON c.id = p.id_categoria
        WHERE c.activo = 1
        GROUP BY c.id";

$resultado = $con->query($sql);
$categorias = $resultado->fetchAll(PDO::FETCH_ASSOC);

// Crear instancia de TCPDF
$pdf = new TCPDF();
$pdf->AddPage();

// Configuración del archivo PDF
$filename = 'estadisticas.pdf';
header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . $filename . '"');

// Configurar la fuente y otros estilos según sea necesario
$pdf->SetFont('dejavusans', '', 14);

// Agregar contenido al PDF (puedes ajustar según tus necesidades)
$pdf->writeHTML('<h1 style="color: #009688; text-align: center;">Estadísticas de Categorías</h1>');

// Iterar sobre las categorías y agregar información al PDF
foreach ($categorias as $categoria) {
    $pdf->writeHTML('<p><strong style="color: #333;">Categoría: </strong> ' . $categoria['categoria'] . '</p>');
    $pdf->writeHTML('<p><strong style="color: #333;">Cantidad de Productos: </strong> ' . $categoria['cantidad_productos'] . '</p>');
    // Puedes agregar más información según tus necesidades
    $pdf->writeHTML('<br>'); // Separador entre categorías
}

// Generar el PDF y enviarlo al navegador para su descarga
$pdf->Output($filename, 'D');
?>
