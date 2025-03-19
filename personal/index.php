<?php

require 'config/config.php';
require 'config/database.php';
require 'clases/personalFunciones.php';

$db = new Database();
$con = $db->conectar();

/* HOLA */

/*$password = password_hash('pearson', PASSWORD_DEFAULT);
$sql = "INSERT INTO personal (usuario, password, nombre, email, activo, fecha_alta)
values  ('pearson','$password','Personal','chavezfelipelouis@gmail.com','1',NOW())";
$con->query($sql);  */

/*$password = password_hash('ElAmanteDelCafe', PASSWORD_DEFAULT);
$sql = "INSERT INTO personal (usuario, password, nombre, email, activo, fecha_alta)
values  ('ElAmanteDelCafe','$password','Alejandro Arteaga','chavezfelipelouis@gmail.com','1',NOW())";
$con->query($sql);  */

/*$password = password_hash('ChefGourmet', PASSWORD_DEFAULT);
$sql = "INSERT INTO personal (usuario, password, nombre, email, activo, fecha_alta)
values  ('ChefGourmet','$password','Martín  Magallan','chavezfelipelouis@gmail.com','1',NOW())";
$con->query($sql);  */

/*$password = password_hash('SrLimpios', PASSWORD_DEFAULT);
$sql = "INSERT INTO personal (usuario, password, nombre, email, activo, fecha_alta)
values  ('SrLimpios','$password','Fernando Ceballos','chavezfelipelouis@gmail.com','1',NOW())";
$con->query($sql);   */

/*$password = password_hash('FashionMan', PASSWORD_DEFAULT);
$sql = "INSERT INTO personal (usuario, password, nombre, email, activo, fecha_alta)
values  ('FashionMan','$password','Valentina Nappi','chavezfelipelouis@gmail.com','1',NOW())";
$con->query($sql);  */

/*$password = password_hash('StyleQueen', PASSWORD_DEFAULT);
$sql = "INSERT INTO personal (usuario, password, nombre, email, activo, fecha_alta)
values  ('StyleQueen','$password','Elsa Jean','chavezfelipelouis@gmail.com','1',NOW())";
$con->query($sql);  */

/*$password = password_hash('TechGeek', PASSWORD_DEFAULT);
$sql = "INSERT INTO personal (usuario, password, nombre, email, activo, fecha_alta)
values  ('TechGeek','$password','Adriana Chechik','chavezfelipelouis@gmail.com','1',NOW())";
$con->query($sql);  */

/*$password = password_hash('CleanFreak', PASSWORD_DEFAULT);
$sql = "INSERT INTO personal (usuario, password, nombre, email, activo, fecha_alta)
values  ('CleanFreak','$password','Ariella Ferrera','chavezfelipelouis@gmail.com','1',NOW())";
$con->query($sql);  */

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
        <title>Login - SB Personal</title>
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://use.fontawesome.com/releases/v6.3.0/js/all.js" crossorigin="anonymous"></script>

        <style>
        body {
            background-image: url('../images/Fondo-Admin.gif'); /* Ruta de tu GIF */
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
                                    <div class="card-header"><h3 class="text-center font-weight-light my-4">Iniciar sesion</h3></div>
                                    <div class="card-body">
                                        <form action="index.php" method="post" autocomplete="off">
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="usuario" name="usuario" type="text" placeholder="usuario" autofocus />
                                                <label for="usuario">usuario</label>
                                            </div>
                                            <div class="form-floating mb-3">
                                                <input class="form-control" id="password" name="password" type="password" placeholder="Contraseña"/>
                                                <label for="password">Contraseña</label>
                                            </div>

                                            <?php mostrarMensajes($errors); ?>
                               
                                            <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                                                <a class="small" href="password.html">¿Olvidaste tu Contraseña?</a>
                                                <button type="submit" class="btn btn-primary">Registrarse</button>
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
