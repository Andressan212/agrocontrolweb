<?php
include("conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

$trabajadores = $conn->query("SELECT * FROM trabajadores ORDER BY nombre ASC");

if (isset($_POST['guardar'])) {
    $trabajador = $_POST['trabajador'];
    $monto = $_POST['monto'];
    $fecha = $_POST['fecha'];
    $descripcion = $_POST['descripcion'];

    $sql = "INSERT INTO pagos(trabajador_id, monto, fecha, descripcion)
            VALUES('$trabajador', '$monto', '$fecha', '$descripcion')";
    $conn->query($sql);

    header("Location: pago_listar.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Nuevo Pago</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<h2>Registrar Pago</h2>

<form method="POST">

    <label>Trabajador</label>
    <select name="trabajador" required>
        <option value="">Seleccione</option>
        <?php while($t = $trabajadores->fetch_assoc()) { ?>
        <option value="<?php echo $t['id']; ?>">
            <?php echo $t['nombre']." - ".$t['cargo']; ?>
        </option>
        <?php } ?>
    </select><br><br>

    <label>Monto</label>
    <input type="number" step="0.01" name="monto" required><br><br>

    <label>Fecha</label>
    <input type="date" name="fecha" required><br><br>

    <label>Descripción</label>
    <textarea name="descripcion"></textarea><br><br>

    <button type="submit" name="guardar">Guardar Pago</button>

</form>

<br>
<a href="pago_listar.php">Volver</a>

</body>
</html>
