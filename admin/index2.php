<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elige tu rol</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-image: url('../images/quien-eres.gif'); /* Ruta de tu imagen GIF */
            background-size: cover;
            background-position: center;
        }

        .container {
            text-align: center;
            background-color: rgba(255, 255, 255, 0.7); /* Color de fondo semi-transparente para que el texto sea legible */
            padding: 20px;
            border-radius: 10px;
        }

        h1 {
            font-size: 2em;
            margin-bottom: 20px;
        }

        .btn {
            padding: 10px 20px;
            font-size: 1.2em;
            margin: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #ddd;
        }

        #admin-btn {
            background-color: #007bff;
            color: #fff;
        }

        #personal-btn {
            background-color: #28a745;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>¿Qué eres?</h1>
        <button class="btn" id="admin-btn">Administrador</button>
        <button class="btn" id="personal-btn">Personal</button>
    </div>

    <script>
        document.getElementById('admin-btn').addEventListener('click', function() {
            // Acción para el botón de administrador
            // Puedes redirigir a la página de inicio de sesión de administrador
            window.location.href = 'index.php';
        });

        document.getElementById('personal-btn').addEventListener('click', function() {
            // Acción para el botón de personal
            // Puedes redirigir a la página de inicio de sesión de personal
            window.location.href = '../personal/index.php';
        });
    </script>
</body>
</html>
