<?php

require 'config/config.php';


$productos = isset($_SESSION['carrito']['productos']) ? $_SESSION['carrito']['productos'] : null;

$db = new Database();
$con = $db->conectar();

//print_r($_SESSION);

$lista_carrito = array();

if ($productos != null){
    foreach ($productos as $clave => $cantidad){

        $sql = $con->prepare("SELECT id, nombre, precio, descuento, $cantidad AS cantidad FROM productos WHERE 
        id=? AND activo=1");
        $sql->execute([$clave]);
        $lista_carrito[] = $sql->fetch(PDO::FETCH_ASSOC);
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

<?php include 'menu.php'; ?>

    <main>
        <div class="container">
            <div class="table-responsive"> 
                <table class="table">
                    <thead>
                    <tr>
            <th><strong>Producto</strong></th>
            <th><strong>Precio</strong></th>
            <th><strong>Cantidad</strong></th>
            <th><strong>Subtotal</strong></th>
            <th></th>
        </tr>
                    </thead>

                        <tbody>
                            <?php if($lista_carrito == null){
                                echo '<tr><td colspan="5" class="text-center"><b>Lista vacia</b></td></tr>';
                            }else{

                                $total = 0;
                                foreach($lista_carrito as $producto) {
                                    $_id = $producto['id'];
                                    $nombre = $producto['nombre'];
                                    $precio = $producto['precio'];
                                    $descuento = $producto['descuento'];
                                    $cantidad = $producto['cantidad'];
                                    $precio_desc = $precio - (($precio * $descuento) /100);
                                    $subtotal = $cantidad * $precio_desc;
                                    $total += $subtotal;
                                ?>
                            
                            <tr>
                                <td><?php echo $nombre; ?></td>
                                <td><?php echo MONEDA . number_format($precio_desc,2, '.', ','); ?></td>

                                <td> <input type="number" min="1" max="10" step="1" value="<?php echo $cantidad ?>"
                                size="5" id="cantidad_<?php echo $_id; ?>" onchange="actualizaCantidad(this.value, <?php echo $_id; ?>)">
                            </td>
                                <td>
                                    <div id="subtotal_<?php echo $_id; ?>" name="subtotal[]"><?php echo MONEDA . 
                                    number_format($subtotal,2, '.', ','); ?></div>
                                </td>
                                <td><a id="eliminar" class="btn btn-warning btn-sm" data-bs-id="<?php 
                                echo $_id; ?>" data-bs-toggle="modal" data-bs-target="#eliminaModal"><i class="bi bi-trash3"></i></a></td>
                            </tr>
                            <?php } ?>

                                <tr>
                                    <td colspan="3"></td>
                                    <td colspan="2">
                                        <p class="h3" id="total"><?php echo MONEDA . number_format($total, 2, '.', ','); ?></p>
                                    </td>
                                </tr>

                        </tbody>
                            <?php } ?>
                </table>
        </div>

        <?php if($lista_carrito != null){ ?>
        <div class="row">
                <div class="col-md-5 offset-md-7 d-grid gab-2">
                  <?php if(isset($_SESSION['user_cliente'])){ ?>

                    <a href="pago.php" class="btn btn-primary btn-lg">Realizar pago</a>
                    <?php }else { ?>
                    <a href="login.php?pago" class="btn btn-primary btn-lg">Realizar pago</a>
                      <?php } ?>
                </div>
        </div>
        <?php } ?>
        
        </div>
    </main>

    <!-- Modal -->
<div class="modal fade" id="eliminaModal" tabindex="-1" aria-labelledby="eliminaModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="eliminaModalLabel">Alerta</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        ¿Desea eliminar el producto del carrito?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">No, no lo elimines</button>
        <button id="btn-elimina" type="button" class="btn btn-danger" onclick="eliminar()">Si eliminalo, por favor</button>
      </div>
    </div>
  </div>
</div>

    
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js" 
integrity="sha384-HwwvtgBNo3bZJJLYd8oVXjrBZt8cqVSpeBNS5n7C8IVInixGAoxmnlMuBnhbgrkm" crossorigin="anonymous"></script>

<script>
        let eliminaModal =document.getElementById('eliminaModal')
        eliminaModal.addEventListener('show.bs.modal', function(event){
          let button = event.relatedTarget
          let id = button.getAttribute('data-bs-id')
          let buttonElimina = eliminaModal.querySelector('.modal-footer #btn-elimina')
          buttonElimina.value = id
        })

              function actualizaCantidad(cantidad, id) {
                let url = 'clases/actualizar_carrito.php'
                let formData = new FormData()
                formData.append('action', 'agregar')
                formData.append('id', id)
                formData.append('cantidad', cantidad)

                fetch(url, {
                  method: 'POST',
                  body: formData,
                  mode: 'cors'
                }).then(response => response.json())
                .then(data => {
                  if(data.ok){

                    let divsubtotal = document.getElementById('subtotal_' + id)
                    divsubtotal.innerHTML = data.sub

                    let total = 0.00
                    let list = document.getElementsByName('subtotal[]')

                    for(let i = 0; i < list.length; i++){
                      total += parseFloat(list[i].innerHTML.replace(/[[<?php echo MONEDA ?>,]/g, ''))
                    }

                    total =new Intl.NumberFormat('en-US', {
                      minimumFractionDigits : 2
                    }).format(total)
                    document.getElementById("total").innerHTML = '<?php echo MONEDA; ?>' + total

                  } else {
                    let inputCantidad = document.getElementById('cantidad_' + id)
                    inputCantidad.value = data.cantidadAnterior
                    alert("No hay suficientes existencias")
                  }
                })
              }

              function eliminar() {

                let botonElimina = document.getElementById('btn-elimina')
                let id =botonElimina.value

                let url = 'clases/actualizar_carrito.php'
                let formData = new FormData()
                formData.append('action', 'eliminar')
                formData.append('id', id)

                fetch(url, {
                  method: 'POST',
                  body: formData,
                  mode: 'cors'
                }).then(response => response.json())
                .then(data => {
                  if(data.ok){
                    location.reload()
                  }
                })
              }
            </script>

</body>
</html>
    

