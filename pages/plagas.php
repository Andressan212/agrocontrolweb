<?php
include("../php/conexion.php"); // asegurate que $conn es tu variable de conexión

if(isset($_POST['guardar'])){
    $nombre = $_POST['nombre'];
    $lote_id = $_POST['lote_id'];
    $tratamiento = $_POST['tratamiento'];

    $stmt = $conn->prepare("INSERT INTO plagas (nombre, lote_id, tratamiento) VALUES (?, ?, ?)");
    $stmt->bind_param("sis", $nombre, $lote_id, $tratamiento);
    $stmt->execute();
    $stmt->close();
}

// Obtener lotes para el select
$lotes = $conn->query("SELECT * FROM lotes");

// Obtener plagas registradas con nombre de lote
$plagas = $conn->query("
    SELECT p.id, p.nombre, l.nombre AS nombre_lote, p.tratamiento
    FROM plagas p 
    LEFT JOIN lotes l ON p.lote_id = l.id
");
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
    <label>Nombre de la Plaga:</label>
    <input type="text" name="nombre" required>

    <label>Lote:</label>
    <select name="lote_id" required>
        <?php while($l = $lotes->fetch_assoc()){ ?>
            <option value="<?php echo $l['id']; ?>"><?php echo $l['nombre']; ?></option>
        <?php } ?>
    </select>

    <label>Tratamiento:</label>
    <input type="text" name="tratamiento">

    <button type="submit" name="guardar">Guardar</button>
</form>

<h3>Plagas Registradas</h3>
<table border="1">
<tr>
    <th>Nombre</th>
    <th>Lote</th>
    <th>Tratamiento</th>
</tr>

<?php while($p = $plagas->fetch_assoc()){ ?>
<tr>
    <td><?php echo $p['nombre']; ?></td>
    <td><?php echo $p['nombre_lote']; ?></td>
    <td><?php echo $p['tratamiento']; ?></td>
</tr>
<?php } ?>
</table>

<a href="../php/dashboard.php">Volver al Panel</a>
</div>

</body>
</html>
