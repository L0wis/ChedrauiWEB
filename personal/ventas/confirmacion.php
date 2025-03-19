<?php
// Verificar si se recibió el ID de la venta a través de la URL
if (isset($_GET['id_venta'])) {
    // Obtener el ID de la venta de la URL
    $id_venta = $_GET['id_venta'];

    // Verificar si se recibió el tipo de pago
    if (isset($_GET['tipo_pago'])) {
        $tipo_pago = $_GET['tipo_pago'];
        // Aquí puedes continuar con el código...
    } else {
        // Si no se recibió el tipo de pago, puedes definir un valor predeterminado o mostrar un mensaje de error
        $tipo_pago = "Tipo de Pago no especificado";
    }

    // Conectar a la base de datos
    require '../config/config.php';
    require '../header.php';
    $db = new Database();
    $con = $db->conectar();

    // Consultar la información de la compra basada en el ID de la venta
    $sql_info_compra = $con->prepare("SELECT * FROM compra_personal WHERE id = ?");
    $sql_info_compra->execute([$id_venta]);
    $info_compra = $sql_info_compra->fetch(PDO::FETCH_ASSOC);

    // Verificar si se encontró la compra con el ID proporcionado
    if ($info_compra) {
        // Consultar los productos comprados en esta compra
        $sql_productos_comprados = $con->prepare("SELECT productos.nombre AS nombre_producto, compra_personal_productos.cantidad, productos.precio 
                                                  FROM compra_personal_productos 
                                                  INNER JOIN productos ON compra_personal_productos.id_producto = productos.id 
                                                  WHERE compra_personal_productos.id_venta = ?");
        $sql_productos_comprados->execute([$id_venta]);
        $productos_comprados = $sql_productos_comprados->fetchAll(PDO::FETCH_ASSOC);

        // Mostrar la información de la compra y los productos comprados
        ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Confirmación de Compra</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
                integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
        </head>

        <body>
            <div class="container">
                <h1>Confirmación de Compra</h1>
                <div class="row">
                    <div class="col-md-6">
                        <h2>Detalles de la Compra</h2>
                        <p>ID de Venta: <?php echo $info_compra['id']; ?></p>
                        <p>Folio de Venta: <?php echo $info_compra['id_transaccion']; ?></p>
                        <p>Email del Cliente: <?php echo $info_compra['email']; ?></p>
                        <p>Fecha de la Compra: <?php echo $info_compra['fecha']; ?></p>
                        <p>Total: <?php echo MONEDA . number_format($info_compra['total'], 2); ?></p>
                    </div>
                    <div class="col-md-6">
                        <!-- Botones -->
                        <div class="mb-3">
                            <button class="btn btn-warning float-end me-2" data-bs-toggle="modal"
                                data-bs-target="#modalAprobarVenta">Aprobar Venta</button>
                            <button class="btn btn-success float-end me-2"
                                onclick="window.location.href='crear_venta.php'">Realizar Nueva Entrega</button>
                            <button class="btn btn-info float-end me-2"
                                onclick="window.location.href='generar_recibo.php?id_venta=<?php echo $id_venta; ?>'">Imprimir
                                Recibo</button>
                            <button class="btn btn-primary float-end me-2"
                                onclick="window.location.href='enviar_productos.php?id_venta=<?php echo $id_venta; ?>'">Enviar
                                Productos</button>
                        </div>
                    </div>

                </div>
                <hr>
                <h2>Productos Comprados</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio Unitario</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($productos_comprados as $producto) { ?>
                            <tr>
                                <td><?php echo $producto['nombre_producto']; ?></td>
                                <td><?php echo $producto['cantidad']; ?></td>
                                <td><?php echo MONEDA . number_format($producto['precio'], 2); ?></td>
                                <td><?php echo MONEDA . number_format($producto['cantidad'] * $producto['precio'], 2); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>

                <div class="text-center">
                    <button class="btn btn-primary"
                        onclick="window.location.href='enviar_productos.php?id_venta=<?php echo $id_venta; ?>'">Enviar
                        Productos</button>
                </div>

            </div>

            <!-- Modal Aprobar Venta -->
            <div class="modal fade" id="modalAprobarVenta" tabindex="-1" aria-labelledby="modalAprobarVentaLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modalAprobarVentaLabel">Aprobar Venta</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            ¿Estás seguro de que deseas aprobar esta venta?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                            <button type="button" class="btn btn-primary" id="btnAprobarVenta">Aprobar</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Scripts de Bootstrap y jQuery -->
            <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
                crossorigin="anonymous"></script>
        </body>

        </html>

        <script>
            $(document).ready(function () {
                // Función para manejar el clic en el botón de aprobar venta
                $('#btnAprobarVenta').click(function () {
                    $.ajax({
                        url: 'aprobar_venta.php', // Ruta al archivo PHP que contiene las funciones
                        type: 'POST',
                        data: { id_venta: <?php echo $id_venta; ?>}, // Datos adicionales que deseas enviar al servidor
                        success: function (response) {
                            // Manejar la respuesta del servidor
                            if (response === 'success') {
                                // Si la respuesta es éxito, mostrar mensaje de éxito en el modal
                                $('#modalAprobarVenta .modal-body').html('<p>La compra se aprobó exitosamente.</p>');
                                $('#modalAprobarVenta .modal-footer').html('<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>');
                            } else {
                                // Si la respuesta es error, mostrar mensaje de error en el modal
                                $('#modalAprobarVenta .modal-body').html('<p>Ocurrió un error al aprobar la venta.</p>');
                                $('#modalAprobarVenta .modal-footer').html('<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>');
                            }
                        },
                        error: function (xhr, status, error) {
                            // Manejar errores de AJAX
                            console.error(xhr.responseText);
                        }
                    });
                });
            });
        </script>



        <?php
    } else {
        // Mostrar un mensaje de error si no se encontró la compra con el ID proporcionado
        echo "No se encontró ninguna compra con el ID proporcionado.";
    }
} else {
    // Mostrar un mensaje de error si no se recibió el ID de la venta
    echo "No se recibió ningún ID de venta.";
}
?>