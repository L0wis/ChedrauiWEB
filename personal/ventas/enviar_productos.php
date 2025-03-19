<?php
// Verificar si se recibió el ID de la venta a través de la URL
if (isset($_GET['id_venta'])) {
    // Obtener el ID de la venta de la URL
    $id_venta = $_GET['id_venta'];

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
        
        // Consultar la dirección del cliente desde la tabla clientes
        $id_cliente = $info_compra['id_cliente'];
        $sql_direccion_cliente = $con->prepare("SELECT direccion FROM clientes WHERE id = ?");
        $sql_direccion_cliente->execute([$id_cliente]);
        $direccion_cliente = $sql_direccion_cliente->fetchColumn();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enviar Productos</title>
    <!-- Estilos CSS -->
    <link rel="stylesheet" type="text/css" href="estilo.css">
    <style>
        /* Estilos adicionales para los botones */
        button {
            background-color: #4CAF50; /* Color verde */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }

        button:hover {
            background-color: #45a049; /* Cambio de color al pasar el cursor */
        }

        /* Estilos adicionales para los campos de texto */
        input[type=text] {
            width: calc(100% - 22px);
            padding: 10px;
            border-radius: 5px;
            border: 1px solid #ccc;
            box-sizing: border-box;
            margin-top: 6px;
            margin-bottom: 16px;
        }

        /* Estilos adicionales para el formulario */
        .formulario {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }

        /* Estilos para el mapa */
        #map {
            height: 400px;
            width: 100%;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="formulario">
        <h1>Elegir Dirección de Envío</h1>

        <form action="guardar_direccion.php" method="get">
            <!-- Campo de texto para la dirección -->
            <label for="direccion">Dirección de Envío:</label>
            <input type="text" id="direccion" name="direccion" value="">
            <br>
            <!-- Campo de texto para ingresar la dirección manualmente -->
            <label for="direccion_manual">Ingresar Dirección Manualmente:</label>
            <input type="text" id="direccion_manual" name="direccion_manual" value="">
            <br>
            <!-- Campo de texto para ingresar referencias -->
            <label for="referencias">Referencias:</label>
            <input type="text" id="referencias" name="referencias" value="">
            
            <!-- Campo oculto para el ID de la venta -->
            <input type="hidden" name="id_venta" value="<?php echo htmlspecialchars($id_venta, ENT_QUOTES, 'UTF-8'); ?>">
            
            <!-- Campo oculto para la dirección manual -->
            <input type="hidden" id="direccion_manual_hidden" name="direccion_manual_hidden" value="Valor ingresado en el campo de dirección manual">

            <!-- Botones para enviar el formulario y acciones adicionales -->
            <div>
                <button type="submit">Enviar Productos</button>

                <!-- Botón para agregar la dirección del cliente -->
                <?php if (isset($direccion_cliente)) : ?>
                    <button type="button" onclick="agregarDireccionCliente('<?php echo htmlspecialchars($direccion_cliente, ENT_QUOTES, 'UTF-8'); ?>')">Agregar Dirección del Cliente</button>
                <?php endif; ?>
                
                <!-- Botón adicional para abrir una nueva ventana con la página -->
                <button type="button" onclick="abrirPagina()">Abrir Página Direccion</button>
            </div>
        </form>
    </div>

    <!-- Mapa interactivo para seleccionar la dirección -->
    <div id="map"></div>

    <!-- Script para inicializar el mapa -->
    <script>
        // Función para inicializar el mapa
        function iniciarMap() {
            // Coordenadas iniciales (por ejemplo, Ciudad de México)
            var coordsIniciales = { lat: 20.0588703, lng: -97.045648 };

            // Crear un nuevo mapa en el elemento con ID "map"
            var mapa = new google.maps.Map(document.getElementById('map'), {
                center: coordsIniciales,
                zoom: 14
            });

            // Crear un marcador que el usuario puede arrastrar para seleccionar la dirección
            var marcador = new google.maps.Marker({
                position: coordsIniciales,
                map: mapa,
                draggable: true
            });

            // Escuchar el evento 'dragend' para obtener las coordenadas del marcador cuando se suelte
            google.maps.event.addListener(marcador, 'dragend', function () {
                var latLng = marcador.getPosition();
                // Actualizar el campo de dirección en el formulario con las coordenadas seleccionadas
                document.getElementById('direccion').value = latLng.lat() + ', ' + latLng.lng();
            });
        }

        // Función para enviar la dirección manualmente ingresada
        function enviarDireccionManual() {
            var direccionManual = document.getElementById('direccion_manual').value;
            document.getElementById('direccion_manual_hidden').value = direccionManual;
        }

        // Función para agregar la dirección del cliente al campo de dirección manual
        function agregarDireccionCliente(direccionCliente) {
            document.getElementById('direccion_manual').value = direccionCliente;
        }

        // Llamar a la función enviarDireccionManual() antes de enviar el formulario
        document.querySelector('form').addEventListener('submit', enviarDireccionManual);

        // Función para abrir una nueva ventana con la página mediante una URL
        function abrirPagina() {
            var url = 'https://www.coordenadas-gps.com/convertidor-de-coordenadas-gps';
            // Obtener el tamaño de la ventana actual
            var width = window.innerWidth;
            var height = window.innerHeight;
            // Calcular las coordenadas para la esquina superior derecha
            var left = width - 600; // Ancho de la ventana nueva
            var top = 0;
            // Abrir la nueva ventana en la esquina superior derecha
            window.open(url, 'NuevaVentana', 'width=600,height=400,left=' + left + ',top=' + top);
        }
    </script>

    <!-- Llamar a la función iniciarMap() después de cargar la página -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBDaeWicvigtP9xPv919E-RNoxfvC-Hqik&callback=iniciarMap"></script>
</body>
</html>
