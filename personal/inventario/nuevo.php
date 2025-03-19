<?php 
require '../config/config.php';
require '../header.php';

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit;
}


$db = new Database();
$con = $db->conectar();

$sql = "SELECT id,nombre FROM categorias WHERE activo = 1";
$resultado = $con->query($sql);
$categorias = $resultado->fetchAll(PDO::FETCH_ASSOC);

?>

<style>
  .ck-editor_editable[role="textbox"] {
    min-height: 450px;
  }
</style>

<script src="https://cdn.ckeditor.com/ckeditor5/40.1.0/classic/ckeditor.js"></script>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Nuevo proveedor</h2>

        <form action="guarda.php" method="post" enctype="multipart/form-data" autocomplete="off">
            <div class="mb-3">
                <label for="nombre" class="form-label">Nombre</label>
                <input type="text" class="form-control" name="nombre" id="nombre" required autofocus>
            </div>

            <!-- Agregar campo para cargar la imagen -->
            <div class="mb-3">
                <label for="imagen" class="form-label">Imagen de perfil del proveedor</label>
                <input type="file" class="form-control" name="imagen" id="imagen" accept="image/*" required>
            </div>

            <div class="mb-3">
                <label for="nombre_contacto" class="form-label">Nombre de Contacto</label>
                <input type="text" class="form-control" name="nombre_contacto" id="nombre_contacto" required>
            </div>

            <div class="mb-3">
                <label for="direccion" class="form-label">Dirección</label>
                <input type="text" class="form-control" name="direccion" id="direccion" required>
            </div>

            <div class="row">
                <div class="col mb-3">
                    <label for="ciudad" class="form-label">Ciudad</label>
                    <input type="text" class="form-control" name="ciudad" id="ciudad" required>
                </div>

                <div class="col mb-3">
                    <label for="telefono" class="form-label">Teléfono</label>
                    <input type="text" class="form-control" name="telefono" id="telefono" required>
                </div>
            </div>

            <div class="row">
                <div class="col mb-3">
                    <label for="inicio_contrato" class="form-label">Inicio Contrato</label>
                    <input type="date" class="form-control" name="inicio_contrato" id="inicio_contrato" required>
                </div>
                
                <div class="col mb-3">
                    <label for="fin_contrato" class="form-label">Fin Contrato</label>
                    <input type="date" class="form-control" name="fin_contrato" id="fin_contrato" required>
                </div>
            </div>

            <div class="row">
                <div class="col mb-3">
                    <label for="tiempo_suministro" class="form-label">Tiempo de Suministro</label>
                    <select class="form-select" name="tiempo_suministro" id="tiempo_suministro">
                        <option value="Opción 1">Cada Lunes</option>
                        <option value="Opción 2">Cada Viernes</option>
                        <option value="Opción 3">Cada Domingo</option>
                        <option value="personalizado">Personalizado</option>
                    </select>
                </div>
                
                <div class="col mb-3">
                    <label for="cantidad_suministro" class="form-label">Cantidad de Suministro</label>
                    <input type="text" class="form-control" name="cantidad_suministro" id="cantidad_suministro" required>
                </div>
            </div>

            <div class="row" id="personalizado" style="display: none;">
    <div class="mb-3 col-md-6">
        <label for="tiempo_suministro_personalizado" class="form-label">Personalizado</label>
        <input type="text" class="form-control" name="tiempo_suministro_personalizado" id="tiempo_suministro_personalizado">
    </div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var select = document.getElementById("tiempo_suministro");
        var personalizadoDiv = document.getElementById("personalizado");

        select.addEventListener("change", function () {
            if (select.value === "personalizado") {
                personalizadoDiv.style.display = "block";
            } else {
                personalizadoDiv.style.display = "none";
            }
        });
    });
</script>

</div>


            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
</main>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        var select = document.getElementById("tiempo_suministro");
        var personalizadoDiv = document.getElementById("personalizado");
        var inputPersonalizado = document.getElementById("tiempo_suministro_personalizado");

        select.addEventListener("change", function () {
            if (select.value === "personalizado") {
                personalizadoDiv.style.display = "block";
                inputPersonalizado.focus();
            } else {
                personalizadoDiv.style.display = "none";
                inputPersonalizado.value = "";
            }
        });

        // Agregar un listener para enviar el valor personalizado cuando se envía el formulario
        var form = document.querySelector('form');
        form.addEventListener('submit', function () {
            if (select.value === "personalizado") {
                // Agregar el valor personalizado al formulario antes de enviarlo
                var tiempoPersonalizado = inputPersonalizado.value;
                var inputTiempoPersonalizado = document.createElement('input');
                inputTiempoPersonalizado.type = 'hidden';
                inputTiempoPersonalizado.name = 'tiempo_suministro_personalizado';
                inputTiempoPersonalizado.value = tiempoPersonalizado;
                form.appendChild(inputTiempoPersonalizado);
            }
        });
    });
</script>

<?php require '../footer.php'; ?>
