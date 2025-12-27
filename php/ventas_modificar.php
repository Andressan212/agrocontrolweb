<?php
include("conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("No se proporcionó ID.");
}

$id = intval($_GET['id']);

// Obtener datos existentes usando prepared statement
$stmt = $conn->prepare("SELECT * FROM ventas WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
$venta = $res ? $res->fetch_assoc() : null;
$stmt->close();

// Sacar lista de cultivos
$cultivos = $conn->query("SELECT * FROM cultivos ORDER BY nombre ASC");

if (isset($_POST['guardar'])) {
    $cultivo_id = intval($_POST['cultivo_id']);
    $cantidad = floatval($_POST['cantidad']);
    $precio = floatval($_POST['precio']);
    $fecha = $_POST['fecha'];

    $update = $conn->prepare("UPDATE ventas SET cultivo_id = ?, cantidad = ?, precio = ?, fecha = ? WHERE id = ?");
    $update->bind_param("iddsi", $cultivo_id, $cantidad, $precio, $fecha, $id);
    $update->execute();
    $update->close();

    header("Location: ventas_listar.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modificar Venta</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<h2>Modificar Venta</h2>

<form method="POST">
    <label>Cultivo</label><br>
    <select name="cultivo_id">
        <?php while ($c = $cultivos->fetch_assoc()) { ?>
            <option value="<?php echo (int)$c['id']; ?>" 
                <?php if ($c['id'] == ($venta['cultivo_id'] ?? null)) echo "selected"; ?>>
                <?php echo htmlspecialchars($c['nombre']); ?>
            </option>
        <?php } ?>
    </select><br><br>

    <label>Cantidad</label><br>
    <input type="number" step="0.01" name="cantidad" required value="<?php echo htmlspecialchars($venta['cantidad'] ?? ''); ?>"><br><br>

    <label>Precio</label><br>
    <input type="number" step="0.01" name="precio" required value="<?php echo htmlspecialchars($venta['precio'] ?? ''); ?>"><br><br>

    <label>Fecha</label><br>
    <input type="date" name="fecha" required value="<?php echo htmlspecialchars($venta['fecha'] ?? ''); ?>"><br><br>

    <button type="submit" name="guardar">Guardar Cambios</button>
</form>

<br>
<a href="ventas_listar.php">Volver</a>

</body>
</html>
