<?php
include("../php/conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

// Consulta simple de cultivos
$sql = "SELECT * FROM cultivos ORDER BY id DESC";
$consulta = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cultivos - AgroSystem</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<h2>Gestión de Cultivos 🌱</h2>

<h3>Cultivos registrados</h3>

<table border="1" width="100%">
<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Tipo</th>
    <th>Fecha Siembra</th>
</tr>

<?php if ($consulta) {
    while($fila = $consulta->fetch_assoc()){ ?>
<tr>
    <td><?php echo htmlspecialchars($fila['id']); ?></td>
    <td><?php echo htmlspecialchars($fila['nombre']); ?></td>
    <td><?php echo htmlspecialchars($fila['tipo']); ?></td>
    <td><?php echo htmlspecialchars($fila['fecha_siembra']); ?></td>
</tr>
<?php }
} else { ?>
<tr><td colspan="4">No hay cultivos registrados</td></tr>
<?php } ?>
</table>

<a href="../php/dashboard.php">Volver al Panel</a>
</body>
</html>
