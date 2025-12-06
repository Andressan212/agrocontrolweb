<?php
include("../php/conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

// Procesar inserción de maquinaria
if (isset($_POST['guardar'])) {
    $nombre = $_POST['nombre'] ?? '';
    $modelo = $_POST['modelo'] ?? '';
    $estado = $_POST['estado'] ?? '';
    
    if (!empty($nombre)) {
        $sql_insert = "INSERT INTO maquinaria(nombre, modelo, estado) VALUES(?, ?, ?)";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bind_param("sss", $nombre, $modelo, $estado);
        $stmt->execute();
        $stmt->close();
    }
}

// Consulta de maquinaria
$consulta = $conn->query("SELECT * FROM maquinaria ORDER BY id DESC");
?>

<!DOCTYPE html>
<html>
<head>
<title>Maquinaria</title>
<link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<h2>Gestión de Maquinaria 🚜</h2>

<form method="POST">
    <input type="text" name="nombre" placeholder="Nombre de la máquina" required>
    <input type="text" name="modelo" placeholder="Modelo de la máquina">
    <input type="text" name="estado" placeholder="Estado (activo, reparación)">
    <button type="submit" name="guardar">Guardar</button>
</form>

<h3>Maquinaria registrada</h3>

<table border="1" width="100%">
<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Modelo</th>
    <th>Estado</th>
</tr>

<?php
if ($consulta) {
    while($m = $consulta->fetch_assoc()){
?>
<tr>
    <td><?php echo htmlspecialchars($m['id']); ?></td>
    <td><?php echo htmlspecialchars($m['nombre']); ?></td>
    <td><?php echo htmlspecialchars($m['modelo']); ?></td>
    <td><?php echo htmlspecialchars($m['estado']); ?></td>
</tr>
<?php }
} else { ?>
<tr><td colspan="4">No hay maquinaria registrada</td></tr>
<?php } ?>
</table>

<a href="../php/dashboard.php">Volver al Panel</a>
</body>
</html>
