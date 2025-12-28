<?php
include("../php/conexion.php");
session_start();
$id = intval($_GET['id'] ?? 0);
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}
$id = intval($id);
$t = $conn->query("SELECT * FROM trabajadores WHERE id=$id")->fetch_assoc();

if (!$t) die("Trabajador no encontrado.");

if (isset($_POST['actualizar'])) {
    $nombre = $_POST['nombre'];
    $telefono = $_POST['telefono'];
    $cargo = $_POST['cargo'];
    $sueldo = $_POST['sueldo'];

    $stmt = $conn->prepare("
        UPDATE trabajadores SET nombre=?, telefono=?, cargo=?, sueldo=? WHERE id=?
    ");
    $stmt->bind_param("sssdi", $nombre, $telefono, $cargo, $sueldo, $id);
    $stmt->execute();

    header("Location: trabajadores.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head><title>Modificar Trabajador</title></head>
<body>

<h2>Modificar Trabajador</h2>

<form method="POST">

    <label>Nombre:</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($t['nombre']) ?>" required>

    <label>Teléfono:</label>
    <input type="text" name="telefono" value="<?= htmlspecialchars($t['telefono']) ?>">

    <label>Cargo:</label>
    <input type="text" name="cargo" value="<?= htmlspecialchars($t['cargo']) ?>" required>

    <label>Sueldo:</label>
    <input type="number" step="0.01" name="sueldo" value="<?= htmlspecialchars($t['sueldo']) ?>">

    <button type="submit" name="actualizar">Actualizar</button>
</form>

<a href="trabajadores.php">← Volver</a>

</body>
</html>
