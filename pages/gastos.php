<?php
include("../php/conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

// Captura filtros
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';

// Registrar gasto
if(isset($_POST['registrar'])){
    $concepto = $conn->real_escape_string($_POST['concepto']);
    $monto = $conn->real_escape_string($_POST['monto']);
    $observaciones = $conn->real_escape_string($_POST['observaciones']);

    $sql_insert = "INSERT INTO gastos (fecha,concepto,monto,observaciones) VALUES (CURDATE(),'$concepto','$monto','$observaciones')";
    $conn->query($sql_insert);
}

// Lista de gastos
$sql = "SELECT * FROM gastos WHERE 1";

if(!empty($fecha_inicio)) {
    $fecha_inicio_esc = $conn->real_escape_string($fecha_inicio);
    $sql .= " AND fecha >= '$fecha_inicio_esc'";
}
if(!empty($fecha_fin)) {
    $fecha_fin_esc = $conn->real_escape_string($fecha_fin);
    $sql .= " AND fecha <= '$fecha_fin_esc'";
}

$gastos = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Gastos - AgroSystem</title>
<link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<h2>Registro de Gastos 💸</h2>

<form action="" method="POST">
    <label>Concepto:</label>
    <input type="text" name="concepto" required>
    <label>Monto:</label>
    <input type="number" step="0.01" name="monto" required>
    <label>Observaciones:</label>
    <input type="text" name="observaciones">
    <button type="submit" name="registrar">Registrar Gasto</button>
</form>

<h3>Gastos Registrados</h3>

<form method="GET">
    <label>Desde:</label>
    <input type="date" name="fecha_inicio" value="<?php echo htmlspecialchars($fecha_inicio); ?>">
    <label>Hasta:</label>
    <input type="date" name="fecha_fin" value="<?php echo htmlspecialchars($fecha_fin); ?>">
    <button type="submit">Filtrar</button>
</form>

<table>
<tr>
    <th>Fecha</th>
    <th>Concepto</th>
    <th>Monto</th>
    <th>Observaciones</th>
</tr>

<?php while($g = $gastos->fetch_assoc()){ ?>
<tr>
    <td><?php echo htmlspecialchars($g['fecha']); ?></td>
    <td><?php echo htmlspecialchars($g['concepto']); ?></td>
    <td><?php echo htmlspecialchars($g['monto']); ?></td>
    <td><?php echo htmlspecialchars($g['observaciones'] ?? ''); ?></td>
</tr>
<?php } ?>
</table>

<a href="../php/dashboard.php">Volver</a>
</body>
</html>
