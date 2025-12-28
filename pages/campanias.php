<?php
include("../php/conexion.php");
session_start();

if (isset($_POST['guardar'])) {
    $nombre = $_POST['nombre'];
    $inicio = $_POST['inicio'];
    $fin = $_POST['fin'];

    $stmt = $conn->prepare("
        INSERT INTO campanias(nombre, fecha_inicio, fecha_fin)
        VALUES (?, ?, ?)
    ");
    $stmt->bind_param("sss", $nombre, $inicio, $fin);
    $stmt->execute();
}

$campanias = $conn->query("SELECT * FROM campanias ORDER BY fecha_inicio DESC");
?>

<h2>Campañas 🌾</h2>

<form method="POST">
    <input type="text" name="nombre" placeholder="Campaña 2024-2025" required>
    <input type="date" name="inicio" required>
    <input type="date" name="fin" required>
    <button name="guardar">Guardar</button>
</form>

<table border="1">
<tr><th>Nombre</th><th>Inicio</th><th>Fin</th></tr>
<?php while($c = $campanias->fetch_assoc()){ ?>
<tr>
    <td><?= $c['nombre'] ?></td>
    <td><?= $c['fecha_inicio'] ?></td>
    <td><?= $c['fecha_fin'] ?></td>
</tr>
<?php } ?>
</table>
