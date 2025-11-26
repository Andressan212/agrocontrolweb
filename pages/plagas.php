<?php 
include("../php/conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

// Procesar inserción de plaga
if (isset($_POST['guardar'])) {
    $nombre = $_POST['nombre'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    
    if (!empty($nombre)) {
        $sql = "INSERT INTO plagas(nombre, descripcion) VALUES(?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $nombre, $descripcion);
        $stmt->execute();
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Plagas</title>
<link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<div class="sidebar">
    <h2>AgroControl</h2>
    <a href="../php/dashboard.php">Panel</a>
    <a href="plagas.php">Plagas</a>
</div>

<div class="content">
<h2>Plagas y Enfermedades 🐛</h2>

<form method="POST">
    <input type="text" name="nombre" placeholder="Nombre plaga/enfermedad" required>
    <textarea name="descripcion" placeholder="Descripción"></textarea>
    <button type="submit" name="guardar">Guardar</button>
</form>

<h3>Plagas registradas</h3>

<table border="1" width="100%">
<tr>
    <th>Nombre</th>
    <th>Descripción</th>
</tr>

<?php
$consulta = $conn->query("SELECT * FROM plagas");
if ($consulta) {
    while($p = $consulta->fetch_assoc()){
        echo "<tr>
            <td>" . htmlspecialchars($p['nombre']) . "</td>
            <td>" . htmlspecialchars($p['descripcion']) . "</td>
        </tr>";
    }
}
?>
</table>

<a href="../php/dashboard.php">Volver al Panel</a>
</div>

</body>
</html>
