<?php
include("../php/conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

// Procesar inserción de tarea
if (isset($_POST['guardar'])) {
    $descripcion = $_POST['descripcion'] ?? '';
    $fecha = $_POST['fecha'] ?? '';
    $lote_id = $_POST['lote_id'] ?? '';
    $cultivo_id = $_POST['cultivo_id'] ?? '';
    
    if (!empty($descripcion) && !empty($fecha)) {
        $sql_insert = "INSERT INTO tareas(descripcion, fecha, lote_id, cultivo_id) VALUES(?, ?, ?, ?)";
        $stmt = $conn->prepare($sql_insert);
        $stmt->bind_param("ssii", $descripcion, $fecha, $lote_id, $cultivo_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Captura de filtros
$fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : '';
$fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : '';
$lote_filtro = isset($_GET['lote']) ? (int)$_GET['lote'] : '';

// Obtener todos los lotes para el filtro
$lotes = $conn->query("SELECT id, nombre FROM lotes");

// Construir consulta con filtros
$sql = "SELECT * FROM tareas WHERE 1=1";

if (!empty($fecha_inicio)) {
    $fecha_inicio_esc = $conn->real_escape_string($fecha_inicio);
    $sql .= " AND fecha >= '$fecha_inicio_esc'";
}

if (!empty($fecha_fin)) {
    $fecha_fin_esc = $conn->real_escape_string($fecha_fin);
    $sql .= " AND fecha <= '$fecha_fin_esc'";
}

if (!empty($lote_filtro)) {
    $sql .= " AND lote_id = $lote_filtro";
}

$sql .= " ORDER BY fecha DESC";

$consulta = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tareas - AgroSystem</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<h2>Tareas Agrícolas 🚜</h2>

<form method="POST">
    <input type="text" name="descripcion" placeholder="Descripción de la tarea" required>
    <input type="date" name="fecha" required>
    <select name="cultivo_id" required>
        <option value="">Seleccione cultivo</option>
        <?php 
        $cultivos_list = $conn->query("SELECT id, nombre FROM cultivos");
        while($c = $cultivos_list->fetch_assoc()){ ?>
            <option value="<?php echo (int)$c['id']; ?>"><?php echo htmlspecialchars($c['nombre']); ?></option>
        <?php } ?>
    </select>
    <select name="lote_id" required>
        <option value="">Seleccione lote</option>
        <?php 
        $lotes_copy = $conn->query("SELECT id, nombre FROM lotes");
        while($l = $lotes_copy->fetch_assoc()){ ?>
            <option value="<?php echo (int)$l['id']; ?>"><?php echo htmlspecialchars($l['nombre']); ?></option>
        <?php } ?>
    </select>
    <button type="submit" name="guardar">Guardar Tarea</button>
</form>

<h3>Tareas registradas</h3>

<form action="" method="GET" style="margin-bottom: 20px;">
    <label>Desde:</label>
    <input type="date" name="fecha_inicio" value="<?php echo htmlspecialchars($fecha_inicio); ?>">
    <label>Hasta:</label>
    <input type="date" name="fecha_fin" value="<?php echo htmlspecialchars($fecha_fin); ?>">

    <label>Lote:</label>
    <select name="lote">
        <option value="">Todos los lotes</option>
        <?php 
        $lotes_filter = $conn->query("SELECT id, nombre FROM lotes");
        while($l = $lotes_filter->fetch_assoc()){ ?>
            <option value="<?php echo (int)$l['id']; ?>" <?php if($lote_filtro==(int)$l['id']) echo 'selected'; ?>>
                <?php echo htmlspecialchars($l['nombre']); ?>
            </option>
        <?php } ?>
    </select>

    <button type="submit">Filtrar</button>
</form>

<table border="1" width="100%">
<tr>
    <th>ID</th>
    <th>Descripción</th>
    <th>Fecha</th>
    <th>Lote</th>
    <th>Cultivo</th>
</tr>

<?php if ($consulta) {
    while($t = $consulta->fetch_assoc()){ 
        // Obtener nombre del lote
        $lote_nombre = '';
        if ($t['lote_id']) {
            $lote_result = $conn->query("SELECT nombre FROM lotes WHERE id = " . (int)$t['lote_id']);
            if ($lote_result && $lote_row = $lote_result->fetch_assoc()) {
                $lote_nombre = $lote_row['nombre'];
            }
        }
        // Obtener nombre del cultivo
        $cultivo_nombre = '';
        if ($t['cultivo_id']) {
            $cultivo_result = $conn->query("SELECT nombre FROM cultivos WHERE id = " . (int)$t['cultivo_id']);
            if ($cultivo_result && $cultivo_row = $cultivo_result->fetch_assoc()) {
                $cultivo_nombre = $cultivo_row['nombre'];
            }
        }
    ?>
<tr>
    <td><?php echo htmlspecialchars($t['id']); ?></td>
    <td><?php echo htmlspecialchars($t['descripcion'] ?? ''); ?></td>
    <td><?php echo htmlspecialchars($t['fecha']); ?></td>
    <td><?php echo htmlspecialchars($lote_nombre); ?></td>
    <td><?php echo htmlspecialchars($cultivo_nombre); ?></td>
</tr>
<?php }
} else { ?>
<tr><td colspan="5">No hay tareas registradas</td></tr>
<?php } ?>
</table>

<a href="../php/dashboard.php">Volver al Panel</a>
</body>
</html>
