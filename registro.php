<?php

error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', 1);

require_once 'config/config.php';
require_once 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();

$errors = [];

// Generar un DNI único cuando se carga la página
$dni = generarDNI();
while (dniExiste($dni, $con)) {
    $dni = generarDNI();
}

if (!empty($_POST)) {
    $nombres = trim($_POST['nombres']);
    $apellidos = trim($_POST['apellidos']);
    $email = trim($_POST['email']);
    $telefono = trim($_POST['telefono']);
    $direccion = trim($_POST['direccion']);
    $dni = trim($_POST['dni']); // Obtener el DNI desde el formulario
    $usuario = trim($_POST['usuario']);
    $password = trim($_POST['password']);
    $repassword = trim($_POST['repassword']);

    if (esNulo([$nombres, $apellidos, $email, $telefono, $direccion, $dni, $usuario, $password, $repassword])) {
        $errors[] = "Debe de rellenar todos los campos";
    }
    if (!esEmail($email)) {
        $errors[] = "La direccion de correo no es valida";
    }

    if (!validaPassword($password, $repassword)) {
        $errors[] = "Las contraseñas no coinciden";
    }

    if (usuarioExiste($usuario, $con)) {
        $errors[] = "El nombre de usuario $usuario ya existe";
    }

    if (emailExiste($email, $con)) {
        $errors[] = "El correo electronico $email ya existe";
    }

    if (count($errors) == 0) {

        $id = registrarCliente([$nombres, $apellidos, $email, $telefono, $direccion, $dni], $con);

        if ($id > 0) {

            require 'clases/Mailer.php';
            $mailer = new Mailer();
            $token = generarToken();
            $pass_hash = password_hash($password, PASSWORD_DEFAULT);

            $idusuario = registrarUsuario([$usuario, $pass_hash, $token, $id], $con);
            if ($idusuario > 0) {

                $url = SITE_URL . '/activa_cliente.php?id=' . $idusuario . '&token=' . $token;
                $asunto = "Activar cuenta - Tienda Omnia";
                $cuerpo = "Estimado $nombres: <br> Para continuar con el proceso de registro es indispensable de click en la siguiente liga 
                <a href='$url'>Activar cuenta</a>";

                if ($mailer->enviarEmail($email, $asunto, $cuerpo)) {
                    echo "Para terminar el proceso de registro siga las instrucciones que le hemos enviado
                    a la direccion de correo electronico $email";

                    exit;
                }
            } else {

                $errors[] = "Error al registrar usuario";
            }
        } else {
            $errors[] = "Error al registrar cliente";
        }
    }
}

function generarDNI() {
    $letra = chr(rand(65, 90)); // Genera una letra mayúscula (A-Z)
    $numeros = str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT); // Genera un número de 6 dígitos con ceros a la izquierda
    return $letra . $numeros;
}

function dniExiste($dni, $con) {
    $sql = $con->prepare("SELECT COUNT(*) FROM clientes WHERE dni = ?");
    $sql->execute([$dni]);
    return $sql->fetchColumn() > 0;
}

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
      <a href="index.php" class="navbar-brand">
        <strong>Chedraui</strong>
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarHeader" aria-controls="navbarHeader" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

    <div class="collapse navbar-collapse" id="navbarHeader">
    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
    <li class="navbar-item">
        <a href="index.php" class="nav-link active">Catalogo</a>
    </li>

    <li class="navbar-intem">
        <a href="ofertas.php" class="nav-link active">Ofertas</a>
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

<main>
    <div class="container">
        <h2>Datos del cliente</h2>

        <?php mostrarMensajes($errors); ?>

        <form class="row g-3" action="registro.php" method="post" autocomplete="off">
            <div class="col-md-6">
                <label for="nombres"><span class="text-danger">*</span> Nombres</label>
                <input type="text" name="nombres" id="nombres" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label for="apellidos"><span class="text-danger">*</span> Apellidos</label>
                <input type="text" name="apellidos" id="apellidos" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label for="email"><span class="text-danger">*</span> Correo electronico</label>
                <input type="email" name="email" id="email" class="form-control" required>
                <span id="validaEmail" class="text-danger"></span>
            </div>

            <div class="col-md-6">
                <label for="telefono"><span class="text-danger">*</span> Telefono</label>
                <input type="tel" name="telefono" id="telefono" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label for="usuario"><span class="text-danger">*</span> Usuario</label>
                <input type="text" name="usuario" id="usuario" class="form-control" required>
                <span id="validaUsuario" class="text-danger"></span>
            </div>

            <div class="col-md-6">
                <label for="direccion"><span class="text-danger">*</span> Dirección</label>
                <input type="text" name="direccion" id="direccion" class="form-control" required>
            </div>

            <input type="hidden" name="dni" value="<?php echo $dni; ?>">

            <div class="col-md-6">
                <label for="password"><span class="text-danger">*</span> Contraseña</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>

            <div class="col-md-6">
                <label for="repassword"><span class="text-danger">*</span> Repetir contraseña</label>
                <input type="password" name="repassword" id="repassword" class="form-control" required>
            </div>

            <i><b>Nota:</b> Los campos con asterisco son obligatorios</i>
            <div class="col-12">
                <button type="submit" class="btn btn-primary">Registrar</button>
            </div>
        </form>
    </div>
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>

<script>
    let txtUsuario = document.getElementById('usuario');
    txtUsuario.addEventListener("blur", function () {
        existeUsuario(txtUsuario.value);
    }, false);

    let txtEmail = document.getElementById('email');
    txtEmail.addEventListener("blur", function () {
        existeEmail(txtEmail.value);
    }, false);

    function existeEmail(email) {
        let url = "clases/clienteAjax.php";
        let formData = new FormData();
        formData.append("action", "existeEmail");
        formData.append("email", email);

        fetch(url, {
            method: 'POST',
            body: formData
        }).then(response => response.json())
        .then(data => {
            if (data.ok) {
                document.getElementById('email').value = '';
                document.getElementById('validaEmail').innerHTML = 'Email no disponible';
            } else {
                document.getElementById('validaEmail').innerHTML = '';
            }
        });
    }

    function existeUsuario(usuario) {
        let url = "clases/clienteAjax.php";
        let formData = new FormData();
        formData.append("action", "existeUsuario");
        formData.append("usuario", usuario);

        fetch(url, {
            method: 'POST',
            body: formData
        }).then(response => response.json())
        .then(data => {
            if (data.ok) {
                document.getElementById('usuario').value = '';
                document.getElementById('validaUsuario').innerHTML = 'Usuario no disponible';
            } else {
                document.getElementById('validaUsuario').innerHTML = '';
            }
        });
    }
</script>

</body>
</html>
