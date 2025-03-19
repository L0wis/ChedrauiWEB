<?php

require_once 'config/config.php';
require_once 'clases/clienteFunciones.php';

error_reporting(E_ALL & ~E_WARNING);

$db = new Database();
$con = $db->conectar();

$errors = [];

if(!empty($_POST)){
    $email = trim($_POST['email']);

    if(esNulo([$email])){
        $errors[] = "Debe de rellenar todos los campos";
    }
    if(!esEmail($email)){
        $errors[] = "La direccion de correo no es valida";
    }
    if(count($errors) == 0){
        if(emailExiste($email, $con)){
            $sql = $con->prepare("SELECT usuarios.id, clientes.nombres FROM usuarios INNER JOIN clientes 
            ON usuarios.id_cliente=clientes.id WHERE clientes.email LIKE ? LIMIT 1");
            $sql->execute([$email]);
            $row = $sql->fetch(PDO::FETCH_ASSOC);
            $user_id = $row['id'];
            $nombres = $row['nombres'];

           $token = solicitaPassword($user_id, $con);

           if($token !== null){

            require 'clases/Mailer.php';
            $mailer = new Mailer();

            $url = SITE_URL . '/reset_password.php?id=' . $user_id . '&token=' . $token;

        $asunto = "Recuperar password - Chedraui";
        $cuerpo = "Estimado $nombres: <br> Si haz solicitado el cambio de tu contraseña da clic en el siguiente link 
        <a href='$url'>$url</a>.";
        $cuerpo.= "<br>Si no hiciste esta solicitud, ignora este mensaje.";

        if($mailer->enviarEmail($email, $asunto, $cuerpo)){
            echo "<p><b>Correo enviado</b></p>";
            echo "<p>Hemos enviado un correo electronico a la direccion $email para restablecer la contraseña.</p>";
            exit;
             }
           }
        }else{
            $errors[] = "No existe una cuenta asociada a esta direccion de correo electronico";
        }
    }
}

//session_destroy();
//print_r($_SESSION);

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

<header data-bs-theme="dark">
  <div class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a href="#" class="navbar-brand">
        <strong>Chedraui</strong>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" 
      aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

    <div class="collapse navbar-collapse" id="navbarHeader">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
    <li class="navbar-item">
        <a href="#" class="nav-link active">Catalogo</a>
    </li>

    <li class="navbar-intem">
        <a href="#" class="nav-link active">Ofertas</a>
    </li>

    <li class="navbar-intem">
        <a href="#" class="nav-link">Atencion al cliente</a>
    </li>

    </ul>

    <a href="checkout.php" class="btn btn-primary">
      Carrito<span id="num_cart" class="badge bg-secondary"> <?php echo $num_cart;  ?> </span>
    </a>
 
    </div>
    </div>
  </div>
</header>

    <main class="form-login m-auto pt-4">
        <h3>Recupera tu contraseña</h3>

        <?php mostrarMensajes($errors); ?> 

        <form action="recupera.php" method="post" class="row g-3" autocomplete="off">

            <div class="form-floating">
                <input class="form-control" type="email" name="email" id="email" placeholder="Correo electronico" required>
                <label for="email">Correo electronico</label>
             </div>

                <div class="d-grid gap-3 col-12">
                  <button type="submit" class="btn btn-primary">Continuar</button>
                </div>
    
                <div class="col-12" >
                    ¿No tiene cuenta? <a href="registro.php">Registrate aqui</a>
                </div>

        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>

</body>
</html>