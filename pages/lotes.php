<?php
include("../php/conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

$est = $_SESSION['establecimiento_id'];

$lotes = $conn->query("
    SELECT * FROM lotes
    WHERE establecimiento_id = $est
");

if (isset($_POST['guardar'])) {
    $nombre = $_POST['nombre'] ?? '';
    $ubicacion = $_POST['ubicacion'] ?? '';
    $hectareas = $_POST['hectareas'] ?? '';
    $tipo_suelo = $_POST['tipo_suelo'] ?? '';
    $estado = $_POST['estado'] ?? '';

    $sql = "INSERT INTO lotes(nombre, ubicacion, hectareas, tipo_suelo, estado)
            VALUES(?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdss", $nombre, $ubicacion, $hectareas, $tipo_suelo, $estado);
    $stmt->execute();
    $stmt->close();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lotes</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

<div class="content">
<h2>Registro de Lotes</h2>

<form method="POST">
    <input type="text" name="nombre" placeholder="Nombre del lote" required><br>
    <input type="text" name="ubicacion" placeholder="Ubicación" required><br>
    <input type="number" step="0.01" name="hectareas" placeholder="Hectáreas" required><br>
    <input type="text" name="tipo_suelo" placeholder="Tipo de suelo" required><br>
    <input type="text" name="estado" placeholder="Estado" required><br>
    <button type="submit" name="guardar">Guardar lote</button>
</form>

<h3>Lotes registrados</h3>

<table border="1" width="100%">
<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Ubicación</th>
    <th>Hectáreas</th>
    <th>Tipo suelo</th>
    <th>Estado</th>
</tr>

<?php
$result = $conn->query("SELECT * FROM lotes");

while($row = $result->fetch_assoc()){
    echo "<tr>
            <td>" . htmlspecialchars($row['id']) . "</td>
            <td>" . htmlspecialchars($row['nombre']) . "</td>
            <td>" . htmlspecialchars($row['ubicacion']) . "</td>
            <td>" . htmlspecialchars($row['hectareas']) . "</td>
            <td>" . htmlspecialchars($row['tipo_suelo']) . "</td>
            <td>" . htmlspecialchars($row['estado']) . "</td>
          </tr>";
}
?>
</table>

</div>

<a href="../php/dashboard.php">Volver al Panel</a>
</body>
</html>
