<?php 
include("../php/conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

// Procesar inserción de clima
if (isset($_POST['guardar'])) {
    $fecha = $_POST['fecha'] ?? '';
    $temperatura = $_POST['temperatura'] ?? '';
    $lluvia = $_POST['lluvia'] ?? '';
    
    if (!empty($fecha)) {
        $sql = "INSERT INTO clima(fecha, temperatura, lluvia) VALUES(?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sdd", $fecha, $temperatura, $lluvia);
        $stmt->execute();
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Clima</title>
<link rel="stylesheet" href="../css/dashboard.css">
</head>
<body>

<h2>Registro de Clima ☁️</h2>

<form method="POST">
    <input type="date" name="fecha" required>
    <input type="number" name="temperatura" placeholder="Temperatura °C">
    <input type="number" name="lluvia" placeholder="Lluvia mm">
    <button type="submit" name="guardar">Guardar</button>
</form>

<table border="1" width="100%">
<tr>
    <th>Fecha</th>
    <th>Temp</th>
    <th>Lluvia</th>
</tr>

<?php
$consulta = $conn->query("SELECT * FROM clima ORDER BY fecha DESC");
if ($consulta) {
    while($c = $consulta->fetch_assoc()){
?>
<tr>
    <td><?php echo htmlspecialchars($c['fecha']); ?></td>
    <td><?php echo htmlspecialchars($c['temperatura']); ?> °C</td>
    <td><?php echo htmlspecialchars($c['lluvia']); ?> mm</td>
</tr>
<?php }
} ?>
</table>

<a href="../php/dashboard.php">Volver al Panel</a>
</body>
</html>
