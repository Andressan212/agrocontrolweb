<?php
include("../php/conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

// Procesar inserción de cultivos
if(isset($_POST['guardar'])){
    $nombre = $_POST['nombre'] ?? '';
    $variedad = $_POST['variedad'] ?? '';
    $fecha_siembra = $_POST['fecha_siembra'] ?? '';
    $fecha_cosecha = $_POST['fecha_cosecha'] ?? '';
    $lote_id = $_POST['lote_id'] ?? '';

    if(!empty($nombre) && !empty($lote_id)){
        $stmt = $conn->prepare("INSERT INTO cultivos(nombre, variedad, fecha_siembra, fecha_cosecha, lote_id) VALUES(?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $nombre, $variedad, $fecha_siembra, $fecha_cosecha, $lote_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Obtener cultivos registrados con nombre de lote
$cultivos = $conn->query("
    SELECT c.id, c.nombre, c.variedad, c.fecha_siembra, c.fecha_cosecha, l.nombre AS nombre_lote
    FROM cultivos c
    LEFT JOIN lotes l ON c.lote_id = l.id
");

// Obtener lotes para el select
$lotes = $conn->query("SELECT * FROM lotes ORDER BY nombre ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Cultivos - AgroSystem</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<h2>Gestión de Cultivos 🌱</h2>

<form method="POST">
    <label>Nombre del Cultivo:</label>
    <input type="text" name="nombre" required>

    <label>Variedad:</label>
    <input type="text" name="variedad">

    <label>Fecha de Siembra:</label>
    <input type="date" name="fecha_siembra">

    <label>Fecha de Cosecha:</label>
    <input type="date" name="fecha_cosecha">

    <label>Lote:</label>
    <select name="lote_id" required>
        <option value="">Selecciona un lote</option>
        <?php while($l = $lotes->fetch_assoc()){ ?>
            <option value="<?php echo $l['id']; ?>"><?php echo $l['nombre']; ?></option>
        <?php } ?>
    </select>

    <button type="submit" name="guardar">Guardar</button>
</form>

<h3>Cultivos registrados</h3>
<table border="1" width="100%">
<tr>
    <th>ID</th>
    <th>Nombre</th>
    <th>Variedad</th>
    <th>Fecha Siembra</th>
    <th>Fecha Cosecha</th>
    <th>Lote</th>
</tr>

<?php if($cultivos){ 
    while($c = $cultivos->fetch_assoc()){ ?>
<tr>
    <td><?php echo htmlspecialchars($c['id']); ?></td>
    <td><?php echo htmlspecialchars($c['nombre']); ?></td>
    <td><?php echo htmlspecialchars($c['variedad']); ?></td>
    <td><?php echo htmlspecialchars($c['fecha_siembra']); ?></td>
    <td><?php echo htmlspecialchars($c['fecha_cosecha']); ?></td>
    <td><?php echo htmlspecialchars($c['nombre_lote']); ?></td>
</tr>
<?php }
} else { ?>
<tr><td colspan="6">No hay cultivos registrados</td></tr>
<?php } ?>
</table>

<a href="../php/dashboard.php">Volver al Panel</a>

</body>
</html>
