<?php
include("conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

$pagos = $conn->query("
    SELECT p.id, p.monto, p.fecha, p.descripcion,
           t.nombre, t.cargo
    FROM pagos p
    LEFT JOIN trabajadores t ON p.trabajador_id = t.id
    ORDER BY p.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Pagos Registrados</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<h2>Pagos realizados</h2>

<a href="pago_agregar.php">➕ Nuevo Pago</a>
<br><br>

<table border="1" width="100%">
    <tr>
        <th>ID</th>
        <th>Trabajador</th>
        <th>Cargo</th>
        <th>Monto</th>
        <th>Fecha</th>
        <th>Descripción</th>
        <th></th>
        <th></th>
    </tr>

    <?php while ($p = $pagos->fetch_assoc()) { ?>
    <tr>
        <td><?php echo $p['id']; ?></td>
        <td><?php echo $p['nombre']; ?></td>
        <td><?php echo $p['cargo']; ?></td>
        <td><?php echo $p['monto']; ?></td>
        <td><?php echo $p['fecha']; ?></td>
        <td><?php echo $p['descripcion']; ?></td>

        <td><a href="pago_modificar.php?id=<?php echo $p['id']; ?>">Modificar</a></td>
        <td><a href="pago_eliminar.php?id=<?php echo $p['id']; ?>" onclick="return confirm('¿Eliminar pago?')">Eliminar</a></td>
    </tr>
    <?php } ?>

</table>

<br>
<a href="../php/dashboard.php">Volver</a>

</body>
</html>
