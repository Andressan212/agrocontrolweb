<?php
include("../php/conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'] ?? 0;

$insumo = $conn->query("SELECT * FROM insumos WHERE id=$id")->fetch_assoc();

if (!$insumo) {
    die("Insumo no encontrado");
}

// Guardar cambios
if (isset($_POST['actualizar'])) {
    $nombre = $_POST['nombre'];
    $tipo = $_POST['tipo'];
    $stock = $_POST['stock'];
    $precio = $_POST['precio'];

    $stmt = $conn->prepare("UPDATE insumos SET nombre=?, tipo=?, stock=?, precio=? WHERE id=?");
    $stmt->bind_param("ssddi", $nombre, $tipo, $stock, $precio, $id);
    $stmt->execute();
    $stmt->close();

    header("Location: insumos.php");
    exit();
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Modificar Insumo</title>
</head>
<body>

<h2>Modificar Insumo</h2>

<form method="POST">
    <input type="text" name="nombre" value="<?= $insumo['nombre'] ?>" required>
    <input type="text" name="tipo" value="<?= $insumo['tipo'] ?>">
    <input type="number" step="0.01" name="stock" value="<?= $insumo['stock'] ?>">
    <input type="number" step="0.01" name="precio" value="<?= $insumo['precio'] ?>">

    <button type="submit" name="actualizar">Actualizar</button>
</form>

<a href="insumos.php">← Volver</a>

</body>
</html>
