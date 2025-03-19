<?php

require_once 'config/config.php';
require_once 'clases/clienteFunciones.php';

$db = new Database();
$con = $db->conectar();

$token = generarToken();
$_SESSION['token'] = $token;

$id_cliente = $_SESSION['user_cliente'];

$sql = $con->prepare("SELECT id_transaccion, fecha, status, total FROM compra WHERE id_cliente = ? ORDER BY DATE (fecha) DESC");
$sql->execute([$id_cliente]);

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

<?php include 'menu.php'; ?>

    <main>
        <div class="container">
            <h4>Mis compras</h4>
            <hr>

            <?php while($row = $sql->fetch(PDO::FETCH_ASSOC)){ ?>

            <div class="card border-secondary mb-3">
  <div class="card-header">
    <?php echo $row['fecha']; ?>
  </div>
  <div class="card-body">
    <h5 class="card-title">Folio: <?php echo $row['id_transaccion']; ?></h5>
    <p class="card-text">Total: <?php echo $row['total']; ?></p>
    <a href="compra_detalle.php?orden=<?php echo $row['id_transaccion']; ?>&token=<?php echo $token; ?>" 
    class="btn btn-primary">Ver compra</a>
  </div>
</div>

<?php } ?>

            </div>
    </main>
    
<?php include 'footer.php'; ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>

</body>
</html>