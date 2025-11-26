<?php
include("../php/conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

$busqueda = isset($_GET['busqueda']) ? $_GET['busqueda'] : '';

// Consulta con búsqueda (protegida)
$sql = "SELECT * FROM insumos WHERE 1";
if($busqueda != ''){
    $b = $conn->real_escape_string($busqueda);
    $sql .= " AND (nombre LIKE '%" . $b . "%' OR tipo LIKE '%" . $b . "%')";
}

$consulta = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
<title>Insumos - AgroSystem</title>
<link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<h2>Gestión de Insumos 💧</h2>

<form action="" method="GET">
    <input type="text" name="busqueda" placeholder="Buscar insumo" value="<?php echo htmlspecialchars($busqueda); ?>">
    <button type="submit">Filtrar</button>
</form>

<table>
<tr>
    <th>Nombre</th>
    <th>Tipo</th>
    <th>Cantidad</th>
    <th>Unidad</th>
</tr>

<?php if ($consulta) {
    while($i = $consulta->fetch_assoc()){ ?>
<tr>
    <td><?php echo htmlspecialchars($i['nombre']); ?></td>
    <td><?php echo htmlspecialchars($i['tipo']); ?></td>
    <td><?php echo htmlspecialchars($i['cantidad']); ?></td>
    <td><?php echo htmlspecialchars($i['unidad']); ?></td>
</tr>
<?php }
} ?>
</table>

<a href="../php/dashboard.php">Volver al Panel</a>
</body>
</html>

