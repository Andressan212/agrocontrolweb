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

// Obtener datos existentes
$buscar = $conn->query("SELECT * FROM ventas WHERE id = $id");
$venta = $buscar->fetch_assoc();

// Sacar lista de cultivos
$cultivos = $conn->query("SELECT * FROM cultivos ORDER BY nombre ASC");

if (isset($_POST['guardar'])) {
    $cultivo_id = $_POST['cultivo_id'];
    $cantidad = $_POST['cantidad'];
    $precio = $_POST['precio'];
    $fecha = $_POST['fecha'];

    $sql = "UPDATE ventas SET 
                cultivo_id='$cultivo_id',
                cantidad='$cantidad',
                precio='$precio',
                fecha='$fecha'
            WHERE id=$id";

    $conn->query($sql);

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
            <option value="<?php echo $c['id']; ?>" 
                <?php if ($c['id'] == $venta['cultivo_id']) echo "selected"; ?>>
                <?php echo $c['nombre']; ?>
            </option>
        <?php } ?>
    </select><br><br>

    <label>Cantidad</label><br>
    <input type="number" step="0.01" name="cantidad" required value="<?php echo $venta['cantidad']; ?>"><br><br>

    <label>Precio</label><br>
    <input type="number" step="0.01" name="precio" required value="<?php echo $venta['precio']; ?>"><br><br>

    <label>Fecha</label><br>
    <input type="date" name="fecha" required value="<?php echo $venta['fecha']; ?>"><br><br>

    <button type="submit" name="guardar">Guardar Cambios</button>
</form>

<br>
<a href="ventas_listar.php">Volver</a>

</body>
</html>
