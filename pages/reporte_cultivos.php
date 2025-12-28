<?php
include("../php/conexion.php");
session_start();
if (!isset($_SESSION['usuario'])) exit();

$reporte = $conn->query("
    SELECT c.nombre,
           SUM(v.cantidad * v.precio) AS total_ventas
    FROM ventas v
    JOIN cultivos c ON v.cultivo_id = c.id
    GROUP BY c.id
");
?>

<h2>📈 Reporte por Cultivo</h2>

<table border="1" width="60%">
<tr>
<th>Cultivo</th>
<th>Total Vendido</th>
</tr>

<?php while($r=$reporte->fetch_assoc()){ ?>
<tr>
<td><?= $r['nombre'] ?></td>
<td>$<?= number_format($r['total_ventas'],2) ?></td>
</tr>
<?php } ?>
</table>

<br>
<a href="imprimir_reporte_cultivos.php" target="_blank">🧾 Imprimir</a>
<br><br>
<a href="../php/dashboard.php">Volver</a>
<!DOCTYPE html>
<html>
<head>
    <title>Nuevo Pago</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
</body>
</html>
