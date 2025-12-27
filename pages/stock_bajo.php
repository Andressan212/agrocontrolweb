<?php
include("../php/conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

// Insumos con stock bajo
$insumos = $conn->query("
    SELECT * FROM insumos
    WHERE stock <= 10
    ORDER BY stock ASC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Stock Bajo - AgroControl</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<h2>⚠️ Insumos con Stock Bajo</h2>

<?php if ($insumos->num_rows > 0) { ?>

<table border="1" width="100%">
<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Tipo</th>
    <th>Stock Actual</th>
    <th>Precio</th>
</tr>

<?php while ($i = $insumos->fetch_assoc()) { ?>
<tr style="background-color:#f8d7da;">
    <td><?php echo $i['id']; ?></td>
    <td><?php echo $i['nombre']; ?></td>
    <td><?php echo $i['tipo']; ?></td>
    <td><strong><?php echo $i['stock']; ?></strong></td>
    <td>$<?php echo $i['precio']; ?></td>
</tr>
<?php } ?>

</table>

<?php } else { ?>
<p>✅ No hay insumos con stock bajo.</p>
<?php } ?>

<a href="../php/dashboard.php">Volver al Panel</a>

</body>
</html>
