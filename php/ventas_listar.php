<?php
include("conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

// Traer todas las ventas con nombre del cultivo
$sql = "SELECT ventas.*, cultivos.nombre AS cultivo_nombre
        FROM ventas
        INNER JOIN cultivos ON ventas.cultivo_id = cultivos.id
        ORDER BY ventas.id DESC";

$consulta = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Ventas - AgroControl</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<h2>Registro de Ventas</h2>

<a href="ventas_agregar.php">➕ Nueva Venta</a>
<br><br>

<table border="1" width="100%">
<tr>
    <th>ID</th>
    <th>Cultivo</th>
    <th>Cantidad</th>
    <th>Precio</th>
    <th>Total</th>
    <th>Fecha</th>
    <th>Eliminar</th>
</tr>

<?php if ($consulta && $consulta->num_rows > 0) { 
    while ($fila = $consulta->fetch_assoc()) { ?>
<tr>
    <td><?php echo htmlspecialchars($fila['id']); ?></td>
    <td><?php echo htmlspecialchars($fila['cultivo_nombre']); ?></td>
    <td><?php echo htmlspecialchars($fila['cantidad']); ?></td>
    <td>$<?php echo number_format((float)$fila['precio'], 2); ?></td>

    <!-- Total calculado -->
    <td><strong>$<?php echo number_format((float)$fila['cantidad'] * (float)$fila['precio'], 2); ?></strong></td>

    <td><?php echo htmlspecialchars($fila['fecha']); ?></td>

    <td>
        <a href="ventas_eliminar.php?id=<?php echo (int)$fila['id']; ?>" 
           onclick="return confirm('¿Eliminar esta venta?')">
           🗑️
        </a>
    </td>
</tr>
<?php } 
} else { ?>
<tr><td colspan="7">No hay ventas registradas</td></tr>
<?php } ?>
</table>

<br>
<a href="../pages/dashboard.php">Volver al Panel</a>

</body>
</html>
