<?php

require_once 'config/config.php';
require_once 'clases/clienteFunciones.php';

$user_id = $_GET['id'] ?? $_POST['user_id'] ?? '';
$token = $_GET['token'] ?? $_POST['token'] ?? '';

if($user_id == '' || $token == '') {
    header("Location: index.php");
    exit;
}

$db = new Database();
$con = $db->conectar();

$errors = [];

if(!verificaTokenRequest($user_id, $token, $con)){
echo "No se pudo verificar la informacion";
exit;
}

if(!empty($_POST)){

    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);

    if(esNulo([$user_id, $token, $password, $repassword])){
        $errors[] = "Debe de rellenar todos los campos";
    }
 
    if(!validaPassword($password, $repassword)){
        $errors[] = "Las contraseñas no coinciden";
    }
    if(count($errors) == 0){
        $pass_hash = password_hash($password, PASSWORD_DEFAULT);
        if(actualizaPassword($user_id, $pass_hash, $con)){
            echo "La contraseña ha sido actualizada.<br><a href='login.php'>Iniciar sesion </a>";
            exit;
        }else{
            $errors[] = "Error al modificar contraseña. Intentalo nuevamente";
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

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4bw+/aepP/YC94hEpVNVgiZdgIC5+VKNBQNGCHeKRQN+PtmoHDEXuppvnDJzQIu9" crossorigin="anonymous">
    <link href="css/estilos.css" rel="stylesheet">

</head>
<body>

<header data-bs-theme="dark">
  <div class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
      <a href="#" class="navbar-brand">
        <strong>Chedraui</strong>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
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
        <h3>Cambiar contraseña</h3>

        <?php mostrarMensajes($errors); ?>

        <form action="reset_password.php" method="post" class="row g-3" autocomplete="off">

        <input type="hidden" name="user_id" id="user_id" value="<?=$user_id; ?>" />
        <input type="hidden" name="token" id="token" value="<?=$token; ?>" />

            <div class="form-floating">
                <input class="form-control" type="password" name="password" id="password" placeholder="Nueva contraseña" required>
                <label for="email">Nueva contraseña</label>
             </div>

             <div class="form-floating">
                <input class="form-control" type="password" name="repassword" id="repassword" placeholder="Confirmar contraseña" required>
                <label for="email">Confirmar contraseña</label>
             </div>

                <div class="d-grid gap-3 col-12">
                  <button type="submit" class="btn btn-primary">Continuar</button>
                </div>
    
                <div class="col-12" >
                    <a href="login.php">Iniciar sesion</a>
                </div>

        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>

</body>
</html>