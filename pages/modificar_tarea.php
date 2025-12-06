<?php
include("../php/conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

// Obtener ID de la tarea
$id = intval($_GET['id'] ?? 0);

if ($id <= 0) {
    echo "ID de tarea inválido";
    exit();
}

// Procesar actualización
if (isset($_POST['modificar'])) {
    $descripcion = $_POST['descripcion'] ?? '';
    $fecha = $_POST['fecha'] ?? '';
    $lote_id = $_POST['lote_id'] ?? '';
    $cultivo_id = $_POST['cultivo_id'] ?? '';

    if (!empty($descripcion) && !empty($fecha) && !empty($lote_id) && !empty($cultivo_id)) {
        $stmt = $conn->prepare("
            UPDATE tareas 
            SET descripcion=?, fecha=?, lote_id=?, cultivo_id=? 
            WHERE id=?
        ");
        $stmt->bind_param("ssiii", $descripcion, $fecha, $lote_id, $cultivo_id, $id);
        $stmt->execute();
        $stmt->close();

        header("Location: tareas.php");
        exit();
    }
}

// Obtener datos de la tarea
$stmt = $conn->prepare("
    SELECT descripcion, fecha, lote_id, cultivo_id 
    FROM tareas 
    WHERE id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$tarea = $result->fetch_assoc();
$stmt->close();

// Obtener lotes y cultivos para los selects
$lotes = $conn->query("SELECT id, nombre FROM lotes ORDER BY nombre ASC");
$cultivos = $conn->query("SELECT id, nombre FROM cultivos ORDER BY nombre ASC");

if (!$tarea) {
    echo "Tarea no encontrada";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Modificar Tarea - AgroSystem</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<h2>Modificar Tarea 📋</h2>

<form method="POST">
    <label>Descripción:</label>
    <textarea name="descripcion" required><?= htmlspecialchars($tarea['descripcion']); ?></textarea>

    <label>Fecha:</label>
    <input type="date" name="fecha" value="<?= $tarea['fecha']; ?>" required>

    <label>Lote:</label>
    <select name="lote_id" required>
        <?php while($l = $lotes->fetch_assoc()): ?>
            <option value="<?= $l['id']; ?>" <?= $l['id'] == $tarea['lote_id'] ? 'selected' : ''; ?>>
                <?= $l['nombre']; ?>
            </option>
        <?php endwhile; ?>
    </select>

    <label>Cultivo:</label>
    <select name="cultivo_id" required>
        <?php while($c = $cultivos->fetch_assoc()): ?>
            <option value="<?= $c['id']; ?>" <?= $c['id'] == $tarea['cultivo_id'] ? 'selected' : ''; ?>>
                <?= $c['nombre']; ?>
            </option>
        <?php endwhile; ?>
    </select>

    <button type="submit" name="modificar">Actualizar</button>
</form>

<a href="tareas.php">Volver a Tareas</a>

</body>
</html>
