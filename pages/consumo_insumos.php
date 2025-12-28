<?php
include("../php/conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

// Guardar consumo
if (isset($_POST['guardar'])) {
    $tarea_id = $_POST['tarea_id'];
    $insumo_id = $_POST['insumo_id'];
    $cantidad = $_POST['cantidad'];
    $fecha = date("Y-m-d");

    // Guardar consumo
    $stmt = $conn->prepare("
        INSERT INTO consumo_insumos (tarea_id, insumo_id, cantidad, fecha)
        VALUES (?, ?, ?, ?)
    ");
    $stmt->bind_param("iids", $tarea_id, $insumo_id, $cantidad, $fecha);
    $stmt->execute();
    $stmt->close();

    // Descontar stock
    $conn->query("
        UPDATE insumos 
        SET stock = stock - $cantidad 
        WHERE id = $insumo_id
    ");
}

// Datos
$tareas = $conn->query("SELECT id, descripcion FROM tareas");
$insumos = $conn->query("SELECT id, nombre FROM insumos");

//$consumos = $conn->query(" <!--reparar--> 
 //   SELECT c.fecha, t.descripcion, i.nombre, c.cantidad
 //   FROM consumo_insumos c
 //   JOIN tareas t ON c.tarea_id = t.id
 //   JOIN insumos i ON c.insumo_id = i.id
 //   ORDER BY c.id DESC
//");v<!--reparar--> 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Consumo de Insumos</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<h2>Consumo de Insumos 🧪</h2>

<form method="POST">
    <label>Tarea:</label>
    <select name="tarea_id" required>
        <?php while($t = $tareas->fetch_assoc()) { ?>
            <option value="<?= $t['id'] ?>"><?= $t['descripcion'] ?></option>
        <?php } ?>
    </select>

    <label>Insumo:</label>
    <select name="insumo_id" required>
        <?php while($i = $insumos->fetch_assoc()) { ?>
            <option value="<?= $i['id'] ?>"><?= $i['nombre'] ?></option>
        <?php } ?>
    </select>

    <label>Cantidad usada:</label>
    <input type="number" step="0.01" name="cantidad" required>

    <button type="submit" name="guardar">Registrar</button>
</form>

<h3>Historial</h3>

<table border="1" width="100%">
<tr>
    <th>Fecha</th>
    <th>Tarea</th>
    <th>Insumo</th>
    <th>Cantidad</th>
</tr>

<!--<?php while($c = $consumos->fetch_assoc()) { ?>--> <!--reparar--> 
<tr>
    <td><?= $c['fecha'] ?></td>
    <td><?= $c['descripcion'] ?></td>
    <td><?= $c['nombre'] ?></td>
    <td><?= $c['cantidad'] ?></td>
</tr>
<?php } ?>
</table>

<a href="../php/dashboard.php">Volver</a>
</body>
</html>
