<?php
include("conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'];

$consulta = $conn->query("SELECT * FROM pagos WHERE id = $id");
$pago = $consulta->fetch_assoc();

$trabajadores = $conn->query("SELECT * FROM trabajadores ORDER BY nombre ASC");

if (isset($_POST['guardar'])) {
    $trabajador = $_POST['trabajador'];
    $monto = $_POST['monto'];
    $fecha = $_POST['fecha'];
    $descripcion = $_POST['descripcion'];

    $conn->query("
        UPDATE pagos SET 
            trabajador_id='$trabajador',
            monto='$monto',
            fecha='$fecha',
            descripcion='$descripcion'
        WHERE id=$id
    ");

    header("Location: pago_listar.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modificar Pago</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<h2>Modificar Pago</h2>

<form method="POST">

<label>Trabajador</label>
<select name="trabajador" required>
    <?php while ($t = $trabajadores->fetch_assoc()) { ?>
        <option value="<?php echo $t['id']; ?>"
            <?php if ($t['id'] == $pago['trabajador_id']) echo "selected"; ?>>
            <?php echo $t['nombre']; ?>
        </option>
    <?php } ?>
</select><br><br>

<label>Monto</label>
<input type="number" step="0.01" name="monto" value="<?php echo $pago['monto']; ?>" required><br><br>

<label>Fecha</label>
<input type="date" name="fecha" value="<?php echo $pago['fecha']; ?>" required><br><br>

<label>Descripción</label>
<textarea name="descripcion"><?php echo $pago['descripcion']; ?></textarea><br><br>

<button type="submit" name="guardar">Guardar Cambios</button>

</form>

<br>
<a href="pago_listar.php">Volver</a>

</body>
</html>
