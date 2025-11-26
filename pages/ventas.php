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

// Registrar venta
if(isset($_POST['registrar'])){
    $id_cultivo = (int)$_POST['cultivo_id'];
    $cantidad = $conn->real_escape_string($_POST['cantidad']);
    $precio = $conn->real_escape_string($_POST['precio']);

    $sql_insert = "INSERT INTO ventas (cultivo_id, cantidad, precio, fecha) VALUES ($id_cultivo, '$cantidad', '$precio', CURDATE())";
    $conn->query($sql_insert);
}

// Lista de ventas
$sql = "SELECT v.*, c.nombre FROM ventas v INNER JOIN cultivos c ON v.cultivo_id=c.id WHERE 1";

if(!empty($fecha_inicio)) {
    $fecha_inicio_esc = $conn->real_escape_string($fecha_inicio);
    $sql .= " AND v.fecha >= '$fecha_inicio_esc'";
}
if(!empty($fecha_fin)) {
    $fecha_fin_esc = $conn->real_escape_string($fecha_fin);
    $sql .= " AND v.fecha <= '$fecha_fin_esc'";
}

$ventas = $conn->query($sql);

// Cultivos para formulario
$cultivos = $conn->query("SELECT id, nombre FROM cultivos");
?>

<!DOCTYPE html>
<html>
<head>
<title>Ventas - AgroSystem</title>
<link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<h2>Registro de Ventas 💰</h2>

<form action="" method="POST">
    <label>Cultivo:</label>
    <select name="cultivo_id" required>
        <option value="">Seleccione cultivo</option>
        <?php while($c = $cultivos->fetch_assoc()){ ?>
            <option value="<?php echo (int)$c['id']; ?>"><?php echo htmlspecialchars($c['nombre']); ?></option>
        <?php } ?>
    </select>
    <label>Cantidad:</label>
    <input type="number" step="0.01" name="cantidad" required>
    <label>Precio:</label>
    <input type="number" step="0.01" name="precio" required>
    <button type="submit" name="registrar">Registrar Venta</button>
</form>

<h3>Ventas Registradas</h3>

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
    <th>Cultivo</th>
    <th>Cantidad</th>
    <th>Precio</th>
</tr>

<?php while($v = $ventas->fetch_assoc()){ ?>
<tr>
    <td><?php echo htmlspecialchars($v['fecha']); ?></td>
    <td><?php echo htmlspecialchars($v['nombre']); ?></td>
    <td><?php echo htmlspecialchars($v['cantidad']); ?></td>
    <td><?php echo htmlspecialchars($v['precio']); ?></td>
</tr>
<?php } ?>
</table>

<a href="../php/dashboard.php">Volver</a>
</body>
</html>
