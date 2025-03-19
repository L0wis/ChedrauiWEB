<?php
require '../config/config.php';
require '../header.php';

$db = new Database();
$con = $db->conectar();

// Verificar si se recibió el ID de la venta de manera adecuada
if (isset($_GET['id_venta'])) {
    $id_venta = $_GET['id_venta'];

    // Verificar si se recibió el tipo de pago
    if (isset($_GET['tipo_pago'])) {
        $tipo_pago = $_GET['tipo_pago'];
        // Consulta SQL para actualizar el tipo de pago a EFECTIVO
        $sql_actualizar_tipo_pago = $con->prepare("UPDATE compra_personal SET tipo_pago = ? WHERE id = ?");
        $sql_actualizar_tipo_pago->execute([$tipo_pago, $id_venta]);
    }

    // Consulta SQL para obtener la información de la compra
    $sql_info_compra = $con->prepare("SELECT * FROM compra_personal WHERE id = ?");
    $sql_info_compra->execute([$id_venta]);
    $info_compra = $sql_info_compra->fetch(PDO::FETCH_ASSOC);

    // Consulta SQL para obtener los productos comprados en esta venta
    $sql_productos_comprados = $con->prepare("SELECT productos.nombre AS nombre_producto, compra_personal_productos.cantidad, productos.precio 
                                              FROM compra_personal_productos 
                                              INNER JOIN productos ON compra_personal_productos.id_producto = productos.id 
                                              WHERE compra_personal_productos.id_venta = ?");
    $sql_productos_comprados->execute([$id_venta]);
    $productos_comprados = $sql_productos_comprados->fetchAll(PDO::FETCH_ASSOC);

    // Verificar si se obtuvieron los datos de la compra y los productos comprados correctamente
    if ($info_compra && $productos_comprados) {
        ?>

        <!DOCTYPE html>
        <html lang="en">

        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Detalles de la Compra</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet"
                integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
        </head>

        <body>
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h2>Detalles de la Compra</h2>
                        <p>ID de Transacción: <?php echo $info_compra['id_transaccion']; ?></p>
                        <p>Email del Cliente: <?php echo $info_compra['email']; ?></p>
                        <p>Fecha de la Compra: <?php echo $info_compra['fecha']; ?></p>
                        <p>Total: <?php echo MONEDA . number_format($info_compra['total'], 2); ?></p>
                        <hr>
                        <!-- Botón de PayPal -->
                        <div id="paypal-button-container"></div>
                        <hr>
                        <!-- Botón de Pago en Efectivo -->
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                            data-bs-target="#modalPagoEfectivo">
                            Pagar en Efectivo
                        </button>
                    </div>
                    <div class="col-md-6">
                        <div class="productos-comprados">
                            <h3>Productos Comprados</h3>
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
                                            <td><?php echo MONEDA . number_format($producto['cantidad'] * $producto['precio'], 2); ?>
                                            </td>
                                        </tr>
                                    <?php } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modal para el pago en efectivo -->
            <div class="modal fade" id="modalPagoEfectivo" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Pagar en Efectivo</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Aquí se mostrará el formulario de pago en efectivo -->
                            <h4>Resumen de la Compra</h4>
                            <p>ID de Transacción: <?php echo $info_compra['id_transaccion']; ?></p>
                            <p>Email del Cliente: <?php echo $info_compra['email']; ?></p>
                            <p>Total: <?php echo MONEDA . number_format($info_compra['total'], 2); ?></p>
                            <!-- Agrega más detalles de la compra si es necesario -->
                            <hr>
                            <!-- Formulario para el pago en efectivo -->
                            <form id="formPagoEfectivo" action="confirmacion.php" method="get">
                                <input type="hidden" name="id_venta" value="<?php echo $id_venta; ?>">
                                <input type="hidden" id="totalCompra" value="<?php echo $info_compra['total']; ?>">
                                <input type="hidden" name="tipo_pago" value="EFECTIVO">
                                <!-- Campo oculto para indicar el tipo de pago -->
                                <div class="mb-3">
                                    <label for="dineroIngresado" class="form-label">Dinero Ingresado</label>
                                    <input type="number" class="form-control" id="dineroIngresado" name="dineroIngresado"
                                        required>
                                </div>
                                <div class="mb-3">
                                    <label for="vuelto" class="form-label">Vuelto</label>
                                    <input type="text" class="form-control" id="vuelto" name="vuelto" readonly>
                                </div>
                                <div id="mensajeSaldoInsuficiente" class="alert alert-danger" style="display: none;">Saldo
                                    Insuficiente</div>
                                <button type="button" class="btn btn-primary" id="btnConfirmarPago"
                                    onclick="confirmarPago()">Confirmar Pago</button>
                            </form>
                        </div>
                        <!-- Ventana de Confirmación de Pago -->
                        <div class="modal-body" id="modalConfirmarPago" style="display: none;">
                            <h4>Confirmar Pago</h4>
                            <p>¿Estás seguro de que deseas confirmar el pago?</p>
                            <button type="button" class="btn btn-success" id="btnProcesarPago">Procesar Pago</button>
                            <button type="button" class="btn btn-secondary" id="btnCancelarPago"
                                data-bs-dismiss="modal">Cancelar</button>
                        </div>

                        <!-- Ventana de Procesamiento de Pago -->
                        <div class="modal-body" id="modalProcesandoPago"
                            style="display: none; display: flex; justify-content: center; align-items: center;">
                            <div style="text-align: center;">
                                <h4>Procesando Pago</h4>
                                <p>Por favor, espera mientras procesamos tu pago...</p>
                                <!-- GIF animado de carga -->
                                <img src="../images/cargando.gif" alt="Cargando..." style="width: 50px; height: 50px;">
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            <script>
                function confirmarPago() {
                    var tipoPago = 'EFECTIVO'; // O cualquier otro método para obtener el tipo de pago
                    var idVenta = '<?php echo $id_venta; ?>';
                    window.location.href = "confirmacion.php?id_venta=" + idVenta + "&tipo_pago=" + tipoPago;
                }
            </script>


            <script>
                // Evento para confirmar el pago
                document.getElementById('btnConfirmarPago').addEventListener('click', function () {
                    var dineroIngresado = parseFloat(document.getElementById('dineroIngresado').value);
                    var totalCompra = parseFloat(document.getElementById('totalCompra').value);
                    if (dineroIngresado < totalCompra) {
                        document.getElementById('mensajeSaldoInsuficiente').style.display = 'block';
                    } else {
                        document.getElementById('modalConfirmarPago').style.display = 'none';
                        document.getElementById('modalProcesandoPago').style.display = 'block';
                        // Aquí puedes realizar cualquier operación adicional antes de redirigir
                        setTimeout(function () {
                            // Redirecciona a la página de agradecimiento
                            window.location.href = "confirmacion.php?id_venta=<?php echo $id_venta; ?>";
                        }, 3000); // Tiempo de espera en milisegundos antes de redirigir (ejemplo: 3 segundos)
                    }
                });

                // Evento para cancelar el pago
                document.getElementById('btnCancelarPago').addEventListener('click', function () {
                    document.getElementById('modalConfirmarPago').style.display = 'none';
                });

                // Evento para procesar el pago
                document.getElementById('btnProcesarPago').addEventListener('click', function () {
                    document.getElementById('modalProcesandoPago').style.display = 'block';
                    // Aquí puedes realizar cualquier operación adicional antes de redirigir
                    setTimeout(function () {
                        // Redirecciona a la página de agradecimiento
                        window.location.href = "confirmacion.php?id_venta=<?php echo $id_venta; ?>";
                    }, 3000); // Tiempo de espera en milisegundos antes de redirigir (ejemplo: 3 segundos)
                });
            </script>


            <!-- Scripts de Bootstrap y PayPal -->
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"
                integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm"
                crossorigin="anonymous"></script>
            <script
                src="https://www.paypal.com/sdk/js?client-id=<?php echo CLIENT_ID; ?>&currency=<?php echo CURRENCY; ?>"></script>
            <script>
                paypal.Buttons({
                    style: {
                        color: 'blue',
                        shape: 'pill',
                        label: 'pay'
                    },
                    createOrder: function (data, actions) {
                        return actions.order.create({
                            purchase_units: [{
                                amount: {
                                    value: <?php echo $info_compra['total']; ?>
                                }
                            }]
                        });
                    },
                    onApprove: function (data, actions) {
                        let url = 'captura.php';
                        actions.order.capture().then(function (detalles) {
                            console.log(detalles);
                            return fetch(url, {
                                method: 'post',
                                headers: {
                                    'content-type': 'application/json'
                                },
                                body: JSON.stringify({
                                    detalles: detalles
                                })
                            }).then(function (response) {
                                window.location.href = "confirmacion.php?id_venta=<?php echo $id_venta; ?>";
                            });
                        });
                    },
                    onCancel: function (data) {
                        alert("Pago cancelado");
                        console.log(data);
                    }
                }).render('#paypal-button-container');
            </script>

            <script>
                // Calcular el vuelto automáticamente
                document.getElementById('dineroIngresado').addEventListener('input', function () {
                    var dineroIngresado = parseFloat(this.value);
                    var totalCompra = <?php echo $info_compra['total']; ?>;
                    var vuelto = dineroIngresado - totalCompra;
                    document.getElementById('vuelto').value = vuelto.toFixed(2);
                });
            </script>

        </body>

        </html>
        <?php
    } else {
        // Si no se obtuvieron los datos de la compra y los productos comprados correctamente, mostrar un mensaje de error
        echo "Error: No se pudieron obtener los detalles de la compra o los productos comprados.";
    }
} else {
    // Si no se recibió el ID de la venta adecuadamente, mostrar un mensaje de error
    echo "Error: No se recibió el ID de la venta.";
}

require '../footer.php';
?>