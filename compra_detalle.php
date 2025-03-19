<?php

require_once 'config/config.php';
require_once 'clases/clienteFunciones.php';

$token_session = $_SESSION['token'];
$orden = $_GET['orden'] ?? null;
$token = $_GET['token'] ?? null;

if ($orden == null || $token == null || $token != $token_session) {
    header("Location: compras.php");
    exit;
}

$db = new Database();
$con = $db->conectar();

$sqlCompra = $con->prepare("SELECT id, id_transaccion, fecha, total FROM compra WHERE id_transaccion = ? LIMIT 1");
$sqlCompra->execute([$orden]);
$rowCompra = $sqlCompra->fetch(PDO::FETCH_ASSOC);
$idcompra = $rowCompra['id'];

$fecha = new DateTime($rowCompra['fecha']);
$fecha = $fecha->format('d-m-Y H:i');

$sqlDetalle = $con->prepare("SELECT id, id_compra, nombre, precio, cantidad FROM detalle_compra WHERE id_compra = ?");
$sqlDetalle->execute([$idcompra]);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chedraui</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link href="css/estilos.css" rel="stylesheet">

</head>

<body>

    <?php include 'menu.php'; ?>

    <main>
        <div class="container">
            <div class="row">
                <div class="col-12 col-md-4">
                    <div class="card mb-3">
                        <div class="card-header">
                            <strong>Detalle de la compra</strong>
                        </div>
                        <div class="card-body">
                            <p><strong>Fecha: </strong> <?php echo $fecha; ?></p>
                            <p><strong>Orden: </strong> <?php echo $rowCompra['id_transaccion']; ?></p>
                            <p><strong>Total: </strong> <?php echo MONEDA . ' ' . number_format(
                                $rowCompra['total'],
                                2,
                                '.',
                                ','
                            ); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-8">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Subtotal</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while ($row = $sqlDetalle->fetch(PDO::FETCH_ASSOC)) {
                                    $precio = $row['precio'];
                                    $cantidad = $row['cantidad'];
                                    $subtotal = $precio * $cantidad;
                                    ?>

                                    <tr>
                                        <td><?php echo $row['nombre']; ?></td>
                                        <td><?php echo MONEDA . ' ' . number_format(
                                            $precio,
                                            2,
                                            '.',
                                            ','
                                        ); ?></td>
                                        <td><?php echo $cantidad; ?></td>
                                        <td><?php echo MONEDA . ' ' . number_format(
                                            $subtotal,
                                            2,
                                            '.',
                                            ','
                                        ); ?></td>
                                    </tr>
                                <?php } ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Botón de Descargar comprobante de compra -->
                    <button class="btn btn-primary" id="btnDescargarComprobante"><i class="bi bi-file-earmark-pdf"></i>
                        Descargar comprobante de compra</button>


                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
        crossorigin="anonymous"></script>

    <script>
        document.getElementById('btnDescargarComprobante').addEventListener('click', function () {
            // Hacer una solicitud al servidor para generar el PDF
            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'generar_pdf.php?orden=<?php echo $orden; ?>', true);
            xhr.responseType = 'blob'; // Indicar que la respuesta esperada es un archivo binario
            xhr.onreadystatechange = function () {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    // Crear un objeto URL con el contenido binario recibido
                    var blob = new Blob([xhr.response], { type: 'application/pdf' });
                    var url = window.URL.createObjectURL(blob);

                    // Crear un enlace y simular un clic para descargar el archivo
                    var a = document.createElement('a');
                    a.href = url;
                    a.download = 'detalle_compra_<?php echo $orden; ?>.pdf';
                    document.body.appendChild(a);
                    a.click();

                    // Limpiar el objeto URL y el enlace creado
                    window.URL.revokeObjectURL(url);
                    document.body.removeChild(a);
                }
            };
            xhr.send();
        });

    </script>


</body>

</html>