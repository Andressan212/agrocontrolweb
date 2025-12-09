<?php
include("../php/conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'] ?? 0;

$venta = $conn->query("SELECT * FROM ventas WHERE id=$id")->fetch_assoc();
$cultivos = $conn->query("SELECT id, nombre FROM cultivos ORDER BY nombre ASC");

if (!$venta) {
    die("Venta no encontrada");
}

if (isset($_POST['actualizar'])) {
    $cultivo_id = $_POST['cultivo_id'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];
    $fecha = $_POST['fecha'];

    $stmt = $conn->prepare("UPDATE ventas SET cultivo_id=?, cantidad=?, precio=?, fecha=? WHERE id=?");
    $stmt->bind_param("iddsi", $cultivo_id, $cantidad, $precio, $fecha, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: ventas.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head><title>Modificar Venta</title></head>
<body>

<h2>Modificar Venta</h2>

<form method="POST">

    <label>Cultivo:</label>
    <select name="cultivo_id" required>
        <?php while ($c = $cultivos->fetch_assoc()) { ?>
            <option value="<?= $c['id'] ?>" <?= ($c['id'] == $venta['cultivo_id']) ? "selected" : "" ?>>
                <?= $c['nombre'] ?>
            </option>
        <?php } ?>
    </select>

    <label>Cantidad:</label>
    <input type="number" step="0.01" name="cantidad" value="<?= $venta['cantidad'] ?>" required>

    <label>Precio:</label>
    <input type="number" step="0.01" name="precio" value="<?= $venta['precio'] ?>" required>

    <label>Fecha:</label>
    <input type="date" name="fecha" value="<?= $venta['fecha'] ?>" required>

    <button type="submit" name="actualizar">Actualizar</button>
</form>

<a href="ventas.php">← Volver</a>

</body>
</html>
