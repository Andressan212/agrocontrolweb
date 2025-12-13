<?php
include("../php/conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

$historial = $conn->query("
    SELECT u.fecha, u.cantidad,
           i.nombre AS insumo,
           l.nombre AS lote,
           c.nombre AS cultivo
    FROM uso_insumos u
    LEFT JOIN insumos i ON u.insumo_id = i.id
    LEFT JOIN lotes l ON u.lote_id = l.id
    LEFT JOIN cultivos c ON u.cultivo_id = c.id
    ORDER BY u.fecha DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Historial de Insumos</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<h2>Historial de Uso de Insumos</h2>

<table border="1" width="100%">
<tr>
    <th>Fecha</th>
    <th>Insumo</th>
    <th>Cantidad</th>
    <th>Lote</th>
    <th>Cultivo</th>
</tr>

<?php while($h = $historial->fetch_assoc()) { ?>
<tr>
    <td><?php echo $h['fecha']; ?></td>
    <td><?php echo $h['insumo']; ?></td>
    <td><?php echo $h['cantidad']; ?></td>
    <td><?php echo $h['lote']; ?></td>
    <td><?php echo $h['cultivo']; ?></td>
</tr>
<?php } ?>

</table>

<br>
<a href="../php/dashboard.php">Volver</a>

</body>
</html>
