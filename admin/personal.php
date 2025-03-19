<?php

require 'config/config.php';
require 'config/database.php';
require 'clases/adminFunciones.php';

$db = new Database();
$con = $db->conectar();

$errors = [];
if(!empty($_POST)){
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);

   if(esNulo([$usuario, $password])){
        $errors[] = "Debe de llenar todos los campos";
    } 
    if(count($errors) == 0){
        $errors[] = login($usuario, $password, $con);
    }
 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Login - Personal</title>
    <link href="css/styles.css" rel="stylesheet" />
    <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>
    <style>
        body {
            background-image: url('../images/Fondo-personal.jpg'); /* Ruta de tu GIF */
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-primary">
    <div id="layoutAuthentication">
        <div id="layoutAuthentication_content">
            <main>
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-lg-5">
                            <div class="card shadow-lg border-0 rounded-lg mt-5">
                                <div class="card-header"><h3 class="text-center font-weight-light my-4">Iniciar sesión</h3></div>
                                <div class="card-body">
                                    <form action="index.php" method="post" autocomplete="off">
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="usuario" name="usuario" type="text" placeholder="Usuario" autofocus />
                                            <label for="usuario">Usuario</label>
                                        </div>
                                        <div class="form-floating mb-3">
                                            <input class="form-control" id="password" name="password" type="password" placeholder="Contraseña"/>
                                            <label for="password">Contraseña</label>
                                        </div>

                                        <?php mostrarMensajes($errors); ?>
                           
                                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                            <a class="small" href="password.html">¿Olvidaste tu contraseña?</a>
                                            <button type="submit" class="btn btn-primary">Iniciar sesión</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                                                    <!-- Botón adicional -->
    <div class="text-center mt-4">
        <a href="../index.php" class="btn btn-success w-50">Regresar al catálogo</a>
    </div>

                    </div>
                </div>
            </main>
        </div>
        
        <!-- ... (resto del contenido) ... -->
        
        <div id="layoutAuthentication_footer" class="fixed-bottom w-100">
            <footer class="py-4 bg-light">
                <div class="container-fluid px-4">
                    <div class="d-flex align-items-center justify-content-between small">
                        <div class="text-muted">Copyright &copy; Chedraui 2023</div>
                    </div>
                </div>
            </footer>
        </div>
        
        <!-- ... (resto del contenido) ... -->
        
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script src="js/scripts.js"></script>
</body>
</html>
