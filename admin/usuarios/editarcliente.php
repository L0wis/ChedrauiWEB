<?php

require '../config/database.php';
require '../config/config.php';
require '../header.php';

if (!isset($_SESSION['user_type'])) {
    header('Location: ../index.php');
    exit;
}

if ($_SESSION['user_type'] != 'admin'){
    header('Location: ../../index.php');
    exit;
}

$db = new Database();
$con = $db->conectar();

$id = $_GET['id'];

$sql = $con->prepare("SELECT id, nombres, apellidos, email, dni, telefono, fecha_alta, estatus FROM clientes WHERE id = ?");
$sql->execute([$id]);
$cliente = $sql->fetch(PDO::FETCH_ASSOC);

?>

<main>
    <div class="container-fluid px-4">
        <h2 class="mt-3">Editar Cliente</h2>

        <form action="actualizar_cliente.php" method="post" autocomplete="off">
            <input type="hidden" name="id" value="<?php echo $cliente['id']; ?>">

            <div class="row">
            <div class="col mb-3">
                <label for="nombres" class="form-label">Nombres</label>
                <input type="text" class="form-control" name="nombres" id="nombres" value="<?php echo htmlspecialchars($cliente['nombres'], ENT_QUOTES); ?>" required autofocus>
            </div>
            <div class="col mb-3">
                <label for="apellidos" class="form-label">Apellidos</label>
                <input type="text" class="form-control" name="apellidos" id="apellidos" value="<?php echo htmlspecialchars($cliente['apellidos'], ENT_QUOTES); ?>" required>
            </div>
            <div class="row">
            <div class="col mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" id="email" value="<?php echo htmlspecialchars($cliente['email'], ENT_QUOTES); ?>" required>
            </div>
            <div class="col mb-3">
                <label for="dni" class="form-label">DNI</label>
                <input type="text" class="form-control" name="dni" id="dni" value="<?php echo htmlspecialchars($cliente['dni'], ENT_QUOTES); ?>" required>
            </div>
            <div class="mb-3">
                <label for="telefono" class="form-label">Teléfono</label>
                <input type="text" class="form-control" name="telefono" id="telefono" value="<?php echo htmlspecialchars($cliente['telefono'], ENT_QUOTES); ?>" required>
            </div>
       
            <div class="mb-3">
                <label for="estatus" class="form-label">Estatus</label>
                <select class="form-select" name="estatus" id="estatus" required>
                    <option value="1" <?php if ($cliente['estatus'] == 1) echo 'selected'; ?>>Activo</option>
                    <option value="0" <?php if ($cliente['estatus'] == 0) echo 'selected'; ?>>Inactivo</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </form>
    </div>
</main>

<?php require '../footer.php'; ?>
