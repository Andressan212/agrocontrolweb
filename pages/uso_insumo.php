<?php
include("../php/conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

// Cargar datos
$insumos = $conn->query("SELECT * FROM insumos ORDER BY nombre ASC");
$lotes = $conn->query("SELECT * FROM lotes ORDER BY nombre ASC");
$cultivos = $conn->query("SELECT * FROM cultivos ORDER BY nombre ASC");

// Guardar uso
if (isset($_POST['guardar'])) {
    $insumo_id = $_POST['insumo_id'];
    $cantidad = $_POST['cantidad'];
    $fecha = $_POST['fecha'];
    $lote_id = $_POST['lote_id'];
    $cultivo_id = $_POST['cultivo_id'];

    // Obtener stock actual
    $res = $conn->query("SELECT stock FROM insumos WHERE id = $insumo_id");
    $insumo = $res->fetch_assoc();

    if ($insumo['stock'] >= $cantidad) {

        // Registrar uso
        $conn->query("
            INSERT INTO uso_insumos(insumo_id, cantidad, fecha, lote_id, cultivo_id)
            VALUES('$insumo_id','$cantidad','$fecha','$lote_id','$cultivo_id')
        ");

        // Descontar stock
        $conn->query("
            UPDATE insumos 
            SET stock = stock - $cantidad 
            WHERE id = $insumo_id
        ");

        header("Location: uso_insumo.php");
        exit();
    } else {
        $error = "Stock insuficiente";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Uso de Insumos</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<h2>Registrar Uso de Insumo</h2>

<?php if (isset($error)) echo "<p style='color:red'>$error</p>"; ?>

<form method="POST">

    <label>Insumo</label>
    <select name="insumo_id" required>
        <?php while($i = $insumos->fetch_assoc()) { ?>
            <option value="<?php echo $i['id']; ?>">
                <?php echo $i['nombre']." (Stock: ".$i['stock'].")"; ?>
            </option>
        <?php } ?>
    </select><br><br>

    <label>Cantidad usada</label>
    <input type="number" step="0.01" name="cantidad" required><br><br>

    <label>Fecha</label>
    <input type="date" name="fecha" required><br><br>

    <label>Lote</label>
    <select name="lote_id" required>
        <?php while($l = $lotes->fetch_assoc()) { ?>
            <option value="<?php echo $l['id']; ?>">
                <?php echo $l['nombre']; ?>
            </option>
        <?php } ?>
    </select><br><br>

    <label>Cultivo</label>
    <select name="cultivo_id" required>
        <?php while($c = $cultivos->fetch_assoc()) { ?>
            <option value="<?php echo $c['id']; ?>">
                <?php echo $c['nombre']; ?>
            </option>
        <?php } ?>
    </select><br><br>

    <button type="submit" name="guardar">Registrar Uso</button>
</form>

<br>
<a href="../php/dashboard.php">Volver</a>

</body>
</html>
