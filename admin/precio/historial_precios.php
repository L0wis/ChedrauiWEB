<?php
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

// Obtener historial de precios con el stock del producto correspondiente
$sql_historial = "SELECT h.id, 
                         p.nombre AS producto, 
                         p.stock,
                         h.precio_anteriores, 
                         h.precio_nuevo, 
                         h.fecha_ajuste, 
                         h.tipo_ajuste, 
                         h.motivo, 
                         h.activo
                  FROM historial_precios h
                  INNER JOIN productos p ON h.producto_id = p.id
                  ORDER BY h.fecha_ajuste DESC";
$stmt_historial = $con->prepare($sql_historial);
$stmt_historial->execute();
$historial_precios = $stmt_historial->fetchAll(PDO::FETCH_ASSOC);
?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Historial de Precios</h2>

        <!-- Tabla de historial de precios -->
        <div class="table-responsive mt-3">
            <table class="table table-hover" id="tablaHistorial">
                <thead>
                    <tr>
                        <th scope="col">Producto</th>
                        <th scope="col">Precio Anterior</th>
                        <th scope="col">Precio Nuevo</th>
                        <th scope="col">Fecha Ajuste</th>
                        <th scope="col">Tipo de Ajuste</th>
                        <th scope="col">Motivo</th>
                        <th scope="col">Activo</th>
                        <th scope="col">Pérdida/Ganancia</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($historial_precios as $registro) { 
                        // Cálculo de pérdida/ganancia
                        $diferencia = $registro['precio_nuevo'] - $registro['precio_anteriores'];
                        $impacto = $diferencia * $registro['stock'];
                        $resultado = '';

                        if ($registro['tipo_ajuste'] === 'disminucion') {
                            $resultado = $impacto < 0 ? 'Pérdida: $' . number_format(abs($impacto), 2) : 'Ganancia: $' . number_format($impacto, 2);
                        } elseif ($registro['tipo_ajuste'] === 'aumento') {
                            $resultado = $impacto > 0 ? 'Ganancia: $' . number_format($impacto, 2) : 'Pérdida: $' . number_format(abs($impacto), 2);
                        }
                    ?>
                        <tr>
                            <td><?php echo htmlspecialchars($registro['producto'], ENT_QUOTES); ?></td>
                            <td>$<?php echo number_format($registro['precio_anteriores'], 2); ?></td>
                            <td>$<?php echo number_format($registro['precio_nuevo'], 2); ?></td>
                            <td><?php echo $registro['fecha_ajuste']; ?></td>
                            <td><?php echo ucfirst($registro['tipo_ajuste']); ?></td>
                            <td><?php echo htmlspecialchars($registro['motivo'], ENT_QUOTES); ?></td>
                            <td><?php echo $registro['activo'] ? 'Sí' : 'No'; ?></td>
                            <td><?php echo $resultado; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<?php require '../footer.php'; ?>
