<?php

require '../config/database.php';
require '../config/config.php';
require '../header.php';

if (!isset($_SESSION['user_type'])) {
  header('Location: ../index.php');
  exit;
}

if ($_SESSION['user_type'] != 'admin') {
  header('Location: ../../index.php');
  exit;
}

$db = new Database();
$con = $db->conectar();

$id = $_GET['id'];

// Obtener datos del producto
$sql = $con->prepare("SELECT id, nombre, descripcion, precio, stock, descuento, id_categoria, id_proveedor FROM productos WHERE id = ? AND activo = 1");
$sql->execute([$id]);
$producto = $sql->fetch(PDO::FETCH_ASSOC);

// Obtener todas las categorías
$sql = "SELECT id,nombre FROM categorias WHERE activo = 1";
$resultado = $con->query($sql);
$categorias = $resultado->fetchAll(PDO::FETCH_ASSOC);

// Obtener todos los proveedores
$sql = "SELECT id, nombre FROM proveedores WHERE activo = 1";
$resultadoProveedores = $con->query($sql);
$proveedores = $resultadoProveedores->fetchAll(PDO::FETCH_ASSOC);

// Proveedores seleccionados
$proveedoresSeleccionados = isset($producto['id_proveedor']) && $producto['id_proveedor'] !== null 
    ? explode(',', $producto['id_proveedor']) 
    : [];

$rutaImagenes = '../../images/productos/' . $id . '/';
$imagenPrincipal = $rutaImagenes . 'principal.jpg';

if (!file_exists($rutaImagenes)) {
  mkdir($rutaImagenes, 0777, true);  // Crea el directorio si no existe
}

$imagenes = [];
$dirInit = dir($rutaImagenes);

while (($archivo = $dirInit->read()) !== false) {
  if ($archivo != 'principal.jpg' && (strpos($archivo, 'jpg') || strpos($archivo, 'jpeg'))) {
    $imagen = $rutaImagenes . $archivo;
    $imagenes[] = $imagen;
  }
}

$dirInit->close();

?>
<style>
  .ck-editor_editable[role="textbox"] {
    min-height: 450px;
  }
</style>

<script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>

<main>
  <div class="container-fluid px-4">
    <h2 class="mt-3">Modifica producto</h2>

    <form action="actualiza.php" method="post" enctype="multipart/form-data" autocomplete="off">
      <input type="hidden" name="id" value="<?php echo $producto['id']; ?>">
      <div class="mb-3">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" class="form-control" name="nombre" id="nombre"
          value="<?php echo htmlspecialchars($producto['nombre'], ENT_QUOTES); ?>" required autofocus>
      </div>

      <div class="mb-3">
        <label for="descripcion" class="form-label">Descripcion</label>
        <textarea class="form-control" name="descripcion"
          id="editor"> <?php echo $producto['descripcion']; ?> </textarea>
      </div>

      <div class="row mb-2">
        <div class="col-12 col-md-6">
          <label for="imagen_principal" class="form-label">Imagen principal</label>
          <input type="file" class="form-control" name="imagen_principal" id="imagen_principal" accept="image/jpeg">
        </div>
        <div class="col-12 col-md-6">
          <label for="otras_imagenes" class="form-label">otras_imagenes</label>
          <input type="file" class="form-control" name="otras_imagenes[]" id="otras_imagenes" accept="image/jpeg"
            multiple>
        </div>
      </div>

      <div class="row mb-2">
        <div class="col-12 col-md-6">
          <?php if (file_exists($imagenPrincipal)) { ?>
            <img src="<?php echo $imagenPrincipal . '?id=' . time(); ?>=" class="img-thumbnail my-3"><br>
            <button class="btn btn-danger btn-sm"
              onclick="eliminaImagen('<?php echo $imagenPrincipal; ?>')">Eliminar</button>
          <?php } ?>
        </div>

        <div class="col-12 col-md-6">
          <div class="row">
            <?php foreach ($imagenes as $imagen) { ?>
              <div class="col-4">
                <img src="<?php echo $imagen . '?id=' . time(); ?>" class="img-thumbnail my-3"><br>
                <button class="btn btn-danger btn-sm" onclick="eliminaImagen('<?php echo $imagen; ?>')">Eliminar</button>
              </div>
            <?php } ?>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col mb-3">
          <label for="precio" class="form-label">Precio</label>
          <input type="number" class="form-control" name="precio" id="precio" value="<?php echo $producto['precio']; ?>"
            required>
        </div>

        <div class="col mb-3">
          <label for="descuento" class="form-label">Descuento</label>
          <input type="number" class="form-control" name="descuento" id="descuento"
            value="<?php echo $producto['descuento']; ?>" required>
        </div>

        <div class="col mb-3">
          <label for="stock" class="form-label">Stock</label>
          <input type="number" class="form-control" name="stock" id="stock" value="<?php echo $producto['stock']; ?>"
            required>
        </div>
      </div>

      <div class="row">
    <!-- Select de Categoría -->
    <div class="col-6 mb-3">
        <label for="categoria" class="form-label">Categoría</label>
        <select class="form-select" name="categoria" id="categoria" required>
            <option value="">Seleccionar</option>
            <?php foreach ($categorias as $categoria) { ?>
                <option value="<?php echo $categoria['id']; ?>" 
                    <?php if ($categoria['id'] == $producto['id_categoria']) echo 'selected'; ?>>
                    <?php echo $categoria['nombre']; ?>
                </option>
            <?php } ?>
        </select>
    </div>

    <!-- Select de Proveedores -->
    <div class="col-6 mb-3">
        <label for="proveedores" class="form-label">Proveedores</label>
        <select class="form-select" name="proveedores[]" id="proveedores" multiple>
            <?php
            // Obtener proveedores seleccionados del producto
            $sqlProveedores = $con->prepare("SELECT id_proveedor FROM productos WHERE id = ?");
            $sqlProveedores->execute([$id]);
            $proveedoresSeleccionados = $sqlProveedores->fetchAll(PDO::FETCH_COLUMN);

            // Obtener todos los proveedores
            $sqlTodosProveedores = "SELECT id, nombre FROM proveedores WHERE activo = 1";
            $resultadoProveedores = $con->query($sqlTodosProveedores);
            $proveedores = $resultadoProveedores->fetchAll(PDO::FETCH_ASSOC);

            foreach ($proveedores as $proveedor) { ?>
                <option value="<?php echo $proveedor['id']; ?>"
                    <?php if (in_array($proveedor['id'], $proveedoresSeleccionados)) echo 'selected'; ?>>
                    <?php echo $proveedor['nombre']; ?>
                </option>
            <?php } ?>
        </select>
    </div>
</div>

      <button type="submit" class="btn btn-primary">Guarda</button>

    </form>

  </div>
</main>

<script>
  ClassicEditor
    .create(document.querySelector('#editor'))
    .catch(error => {
      console.error(error);
    });

  function eliminaImagen(urlImagen) {
    let url = 'eliminar_imagen.php';
    let formData = new FormData()
    formData.append('urlImagen', urlImagen)

    fetch(url, {
      method: 'POST',
      body: formData
    }).then((response) => {
      if (response.ok) {
        location.reload()
      }
    })
  }

</script>

<?php require '../footer.php'; ?>