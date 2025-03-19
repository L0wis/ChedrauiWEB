<?php
// Iniciar sesión si aún no se ha iniciado
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos Cargados</title>
    <!-- Estilos CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            margin: 0;
            padding: 20px;
        }
        .message {
            background-color: #4CAF50;
            color: white;
            padding: 20px;
            margin-top: 20px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <h1>Tus productos ya están siendo cargados</h1>
    <?php if(isset($_SESSION['direccionManual'])): ?>
    <p>Tus productos serán entregados a:</p>
    <p><?php echo $_SESSION['direccionManual']; ?></p>
    <?php endif; ?>
    <p>Nos vemos pronto.</p>

    <!-- Botones adicionales -->
    <div>
        <button onclick="window.print()">Imprimir Recibo</button>
        <a href="index.php"><button>Ir al Inicio</button></a>
    </div>
</body>
</html>
