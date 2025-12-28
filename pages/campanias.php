<?php
include("../php/conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

// Guardar campaña
if (isset($_POST['guardar'])) {
    $nombre = $_POST['nombre'] ?? '';
    $inicio = $_POST['inicio'] ?? '';
    $fin = $_POST['fin'] ?? '';

    if ($nombre && $inicio && $fin) {
        $stmt = $conn->prepare("
            INSERT INTO campanias (nombre, fecha_inicio, fecha_fin)
            VALUES (?, ?, ?)
        ");
        $stmt->bind_param("sss", $nombre, $inicio, $fin);
        $stmt->execute();
        $stmt->close();
    }
}

// Listar campañas
$campanias = $conn->query("
    SELECT * FROM campanias 
    ORDER BY fecha_inicio DESC
");
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Campañas</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<div class="content">

<h2>Campañas 🌾</h2>

<form method="POST">
    <input type="text" name="nombre" placeholder="Campaña 2024-2025" required>
    <input type="date" name="inicio" required>
    <input type="date" name="fin" required>
    <button type="submit" name="guardar" class="btn">Guardar</button>
</form>

<h3>Campañas registradas</h3>

<table border="1" width="100%">
<tr>
    <th>Nombre</th>
    <th>Inicio</th>
    <th>Fin</th>
</tr>

<?php if ($campanias && $campanias->num_rows > 0) {
    while($c = $campanias->fetch_assoc()) { ?>
<tr>
    <td><?= htmlspecialchars($c['nombre']) ?></td>
    <td><?= $c['fecha_inicio'] ?></td>
    <td><?= $c['fecha_fin'] ?></td>
</tr>
<?php }
} else { ?>
<tr>
    <td colspan="3">No hay campañas registradas</td>
</tr>
<?php } ?>

</table>

<br>
<a href="../php/dashboard.php" class="btn">⬅ Volver al Panel</a>

</div>

</body>
</html>
