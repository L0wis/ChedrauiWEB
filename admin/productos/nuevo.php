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

// Consultar categorías activas
$sql = "SELECT id,nombre FROM categorias WHERE activo = 1";
$resultado = $con->query($sql);
$categorias = $resultado->fetchAll(PDO::FETCH_ASSOC);

// Consultar proveedores activos
$sqlProveedores = "SELECT id, nombre FROM proveedores WHERE activo = 1";
$resultadoProveedores = $con->query($sqlProveedores);
$proveedores = $resultadoProveedores->fetchAll(PDO::FETCH_ASSOC);

?>

<style>
  .ck-editor_editable[role="textbox"] {
    min-height: 450px;
  }
</style>

<script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>

<main>
  <div class="container-fluid px-4">
    <h2 class="mt-3">Nuevo producto</h2>

    <form action="guarda.php" method="post" enctype="multipart/form-data" autocomplete="off">
      <div class="mb-3">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" class="form-control" name="nombre" id="nombre" required autofocus>
      </div>

      <div class="mb-3">
        <label for="descripcion" class="form-label">Descripcion</label>
        <textarea class="form-control" name="descripcion" id="editor"></textarea>
      </div>

      <div class="row mb-2">
        <div class="col">
          <label for="imagen_principal" class="form-label">Imagen principal</label>
          <input type="file" class="form-control" name="imagen_principal" id="imagen_principal" accept="image/jpeg"
            required>
        </div>
        <div class="col">
          <label for="otras_imagenes" class="form-label">otras_imagenes</label>
          <input type="file" class="form-control" name="otras_imagenes[]" id="otras_imagenes" accept="image/jpeg"
            multiple>
        </div>
      </div>

      <div class="row">
        <div class="col mb-3">
          <label for="precio" class="form-label">Precio</label>
          <input type="number" class="form-control" name="precio" id="precio" min="1" required>
          <div id="precioAdvertencia" class="form-text text-danger" style="display: none;">
            El precio no puede ser 0 o negativo.
          </div>
        </div>


        <div class="col mb-3">
          <label for="descuento" class="form-label">Descuento</label>
          <input type="number" class="form-control" name="descuento" id="descuento" required>
        </div>

        <div class="col mb-3">
          <label for="stock" class="form-label">Stock</label>
          <input type="number" class="form-control" name="stock" id="stock" required>
        </div>
      </div>

      <div class="row">
        <div class="col-6 mb-3">
          <label for="categoria" class="form-label">Categoría</label>
          <select class="form-select" name="categoria" id="categoria" required>
            <option value="">Seleccionar</option>
            <?php foreach ($categorias as $categoria) { ?>
              <option value="<?php echo $categoria['id']; ?>"><?php echo htmlspecialchars($categoria['nombre']); ?></option>
            <?php } ?>
          </select>
        </div>

        <div class="col-6 mb-3">
          <label for="proveedores" class="form-label">Proveedores</label>
          <select class="form-select" name="proveedores[]" id="proveedores" multiple>
            <?php foreach ($proveedores as $proveedor) { ?>
              <option value="<?php echo $proveedor['id']; ?>">
                <?php echo htmlspecialchars($proveedor['nombre']); ?>
              </option>
            <?php } ?>
          </select>
          <div class="form-text">Mantén presionada la tecla <b>Ctrl</b> o <b>Cmd</b> (en Mac) para seleccionar múltiples opciones.</div>
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
</script>

<script>
  document.getElementById('precio').addEventListener('input', function () {
    const precio = parseFloat(this.value);
    const advertencia = document.getElementById('precioAdvertencia');
    if (precio <= 0) {
      advertencia.style.display = 'block';
    } else {
      advertencia.style.display = 'none';
    }
  });
</script>

<?php require '../footer.php'; ?>