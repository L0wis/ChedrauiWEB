<?php

require '../config/database.php';
require '../config/config.php';
require '../header.php';

if (!isset($_SESSION['user_type'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SESSION['user_type'] != 'admin' ){
    header('Location: ../../index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

// Consulta para obtener la cantidad de productos en cada categoría
$sqlCategorias = "SELECT c.nombre as categoria, COUNT(p.id) as cantidad_productos
                  FROM categorias c
                  LEFT JOIN productos p ON c.id = p.id_categoria
                  WHERE c.activo = 1
                  GROUP BY c.id";

$resultadoCategorias = $con->query($sqlCategorias);
$categorias = $resultadoCategorias->fetchAll(PDO::FETCH_ASSOC);

// Consulta SQL para obtener la cantidad de compras por tipo de pago
$sqlTiposPago = "SELECT tipo_pago, COUNT(*) as cantidad 
                FROM compra_personal 
                WHERE status IN ('APPROVED', 'COMPLETED') 
                GROUP BY tipo_pago";

$stmtTiposPago = $con->query($sqlTiposPago);
$tiposPago = $stmtTiposPago->fetchAll(PDO::FETCH_ASSOC);

// Consultar los 10 productos más vendidos por el personal
$sqlProductosVendidos = "SELECT cpp.id_producto, 
                                SUM(cpp.cantidad) as total_vendido, 
                                p.nombre,
                                (SELECT SUM(dc.cantidad) 
                                 FROM detalle_compra dc 
                                 WHERE dc.id_producto = cpp.id_producto) as total_comprado
                         FROM compra_personal_productos cpp
                         INNER JOIN productos p ON cpp.id_producto = p.id
                         INNER JOIN compra_personal cp ON cpp.id_venta = cp.id
                         WHERE cp.status IN ('APPROVED', 'COMPLETED')
                         GROUP BY cpp.id_producto
                         ORDER BY total_vendido DESC
                         LIMIT 10";

$stmtProductosVendidos = $con->prepare($sqlProductosVendidos);
$stmtProductosVendidos->execute();
$productos_vendidos = $stmtProductosVendidos->fetchAll(PDO::FETCH_ASSOC);

// Preparar los datos para la gráfica de líneas de los productos más vendidos
$labelsProductosVendidos = [];
$dataProductosVendidos = [];

foreach ($productos_vendidos as $producto) {
    $labelsProductosVendidos[] = $producto['nombre'];
    $dataProductosVendidos[] = $producto['total_vendido'];
}
?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Estadísticas</h2>
        <!-- Botones de reportes en la misma fila -->
        <div class="row mt-3">
            <div class="col-lg-4">
                <a href="descargar_reporte.php" class="btn btn-primary"><i class="bi bi-filetype-pdf"></i> Reporte de Categorias PDF</a>
            </div>
            <div class="col-lg-4 d-flex justify-content-center">
                <a href="descargar_reporte_tipo_pago.php" class="btn btn-secondary"><i class="bi bi-file-pdf"></i> Reporte de tipo de pago PDF</a>
            </div>
            <div class="col-lg-4 d-flex justify-content-end">
                <a href="descargar_reporte_productos_vendidos.php" class="btn btn-success"><i class="bi bi-file-pdf"></i> Reporte de Productos más vendidos PDF</a>
            </div>
        </div>

        <!-- Contenedores para las gráficas -->
        <div class="row mt-4">
            <div class="col-lg-4">
                <div class="card mb-4 h-100">
                    <div class="card-header">
                        <i class="fas fa-chart-bar mr-1"></i>
                        Cantidad de productos por categoría
                    </div>
                    <div class="card-body">
                        <canvas id="graficaCategorias" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card mb-4 h-100">
                    <div class="card-header">
                        <i class="fas fa-chart-pie mr-1"></i>
                        Cantidad de compras por tipo de pago
                    </div>
                    <div class="card-body">
                        <canvas id="graficaTiposPago" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card mb-4 h-100">
                    <div class="card-header">
                        <i class="fas fa-chart-line mr-1"></i>
                        Productos más vendidos
                    </div>
                    <div class="card-body">
                        <canvas id="graficaProductosVendidos" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>


<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Obtener el select y los canvas
        var selectGrafica = document.getElementById('selectGrafica');
        var graficas = document.getElementById('graficas').children;

        // Función para mostrar la gráfica seleccionada y ocultar las demás
        function mostrarGrafica(seleccion) {
            for (var i = 0; i < graficas.length; i++) {
                graficas[i].style.display = 'none';
            }
            if (seleccion) {
                document.getElementById(seleccion).style.display = 'block';
            }
        }

        // Event listener para cambiar la gráfica cuando se selecciona una opción del select
        selectGrafica.addEventListener('change', function() {
            mostrarGrafica(selectGrafica.value);
        });

        // Mostrar la gráfica predeterminada
        mostrarGrafica();
    });
</script>


<?php require '../footer.php'; ?>

<!-- Asegúrate de que esta etiqueta script esté dentro del head de tu HTML -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>

<script>
    // Colores para las gráficas
    var colores = [
        'rgba(255, 99, 132, 0.8)',
        'rgba(54, 162, 235, 0.8)',
        'rgba(255, 206, 86, 0.8)',
        'rgba(75, 192, 192, 0.8)',
        'rgba(153, 102, 255, 0.8)',
        'rgba(255, 159, 64, 0.8)'
    ];

    // Gráfica de cantidad de productos por categoría
    document.addEventListener('DOMContentLoaded', function() {
        var datosCategorias = <?php echo json_encode($categorias); ?>;
        var labelsCategorias = datosCategorias.map(function(categoria) {
            return categoria.categoria;
        });
        var dataCategorias = datosCategorias.map(function(categoria) {
            return categoria.cantidad_productos;
        });

        var ctxCategorias = document.getElementById('graficaCategorias').getContext('2d');
        var myBarChart = new Chart(ctxCategorias, {
            type: 'bar',
            data: {
                labels: labelsCategorias,
                datasets: [{
                    label: 'Cantidad de Productos',
                    data: dataCategorias,
                    backgroundColor: colores,
                    borderColor: colores.map(color => color.replace('0.8', '1')),
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });

    // Gráfica de cantidad de compras por tipo de pago
    document.addEventListener('DOMContentLoaded', function() {
        var datosTiposPago = <?php echo json_encode($tiposPago); ?>;
        var labelsTiposPago = datosTiposPago.map(function(tipoPago) {
            return tipoPago.tipo_pago;
        });
        var dataTiposPago = datosTiposPago.map(function(tipoPago) {
            return tipoPago.cantidad;
        });

        var ctxTiposPago = document.getElementById('graficaTiposPago').getContext('2d');
        var myPieChart = new Chart(ctxTiposPago, {
            type: 'pie',
            data: {
                labels: labelsTiposPago,
                datasets: [{
                    label: 'Cantidad de Compras por Tipo de Pago',
                    data: dataTiposPago,
                    backgroundColor: colores,
                    borderColor: colores.map(color => color.replace('0.8', '1')),
                    borderWidth: 1
                }]
            },
            options: {
                // Agrega opciones de configuración según sea necesario
            }
        });
    });

    // Gráfica de los 10 productos más vendidos
    document.addEventListener('DOMContentLoaded', function() {
        var datosProductosVendidos = <?php echo json_encode(['labels' => $labelsProductosVendidos, 'data' => $dataProductosVendidos]); ?>;

        var ctxProductosVendidos = document.getElementById('graficaProductosVendidos').getContext('2d');
        var myLineChart = new Chart(ctxProductosVendidos, {
            type: 'line',
            data: {
                labels: datosProductosVendidos.labels,
                datasets: [{
                    label: 'Cantidad Vendida',
                    data: datosProductosVendidos.data,
                    fill: false,
                    borderColor: 'rgb(75, 192, 192)',
                    tension: 0.1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });
</script>
