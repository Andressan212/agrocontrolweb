<?php
session_start();
include("../php/conexion.php");

// Seguridad básica
if (!isset($_SESSION['rol'])) {
    header("Location: ../index.php");
    exit;
}

// Solo jefe y empleado pueden entrar (ambos)
$sql = "SELECT * FROM lotes ORDER BY id DESC";
$resultado = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Lotes - AgroControl</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<h2>Gestión de Lotes 🌾</h2>

<table border="1" width="100%">
<tr>
    <th>ID</th>
    <th>Nombre</th>
</tr>

<?php
if ($resultado && $resultado->num_rows > 0) {
    while ($lote = $resultado->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$lote['id']}</td>";
        echo "<td>{$lote['nombre']}</td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='2'>No hay lotes cargados</td></tr>";
}
?>
</table>


<br>
<a href="../php/dashboard.php">Volver</a>

</body>
</html>
