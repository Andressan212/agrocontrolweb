<?php
include("../php/conexion.php"); // conexión correcta
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
    
    registrarAuditoria(
    $conn,
    $_SESSION['usuario'],
    'CREAR',
    'TAREAS',
    "Nueva tarea: $descripcion"
);
}

// Guardar tarea nueva
if (isset($_POST['guardar'])) {
    $descripcion = $_POST['descripcion'] ?? '';
    $fecha = $_POST['fecha'] ?? '';
    $lote_id = $_POST['lote_id'] ?? '';
    $cultivo_id = $_POST['cultivo_id'] ?? '';

    if (!empty($descripcion) && !empty($fecha) && !empty($lote_id) && !empty($cultivo_id)) {
        $stmt = $conn->prepare("
            INSERT INTO tareas(descripcion, fecha, lote_id, cultivo_id)
            VALUES(?, ?, ?, ?)
        ");
        $stmt->bind_param("ssii", $descripcion, $fecha, $lote_id, $cultivo_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Eliminar tarea
if (isset($_GET['eliminar'])) {
    $idEliminar = intval($_GET['eliminar']);
    $conn->query("DELETE FROM tareas WHERE id = $idEliminar");
    header("Location: tareas.php");
    exit();
}

// Obtener tareas con datos de lote y cultivo
$tareas = $conn->query("
    SELECT t.id, t.descripcion, t.fecha,
           l.nombre AS nombre_lote,
           c.nombre AS nombre_cultivo
    FROM tareas t
    LEFT JOIN lotes l ON t.lote_id = l.id
    LEFT JOIN cultivos c ON t.cultivo_id = c.id
    ORDER BY t.id DESC
");

// Lotes y cultivos para el select
$lotes = $conn->query("SELECT id, nombre FROM lotes ORDER BY nombre ASC");
$cultivos = $conn->query("SELECT id, nombre FROM cultivos ORDER BY nombre ASC");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Tareas - AgroSystem</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <style>
        table { width:100%; border-collapse: collapse; margin-top: 20px;}
        th, td { border: 1px solid #ccc; padding: 8px; text-align: left;}
        th { background:#222; color:#fff; }
        a.boton { padding:5px 10px; text-decoration:none; border-radius:4px; color:white; margin-right:5px;}
        .imprimir { background: #007bff;}
        .eliminar { background: #d9534f;}
        .modificar { background: #f0ad4e;}
        a.boton:hover { opacity:0.8; }
        button.imprimirTabla { margin-top: 10px; padding:8px 15px; background:#28a745; color:white; border:none; border-radius:5px; cursor:pointer;}
        button.imprimirTabla:hover { opacity:0.8; }
    </style>
    <script>
        function imprimirTabla() {
            var contenido = document.getElementById('tablaTareas').outerHTML;
            var win = window.open('', '', 'height=600,width=800');
            win.document.write('<html><head><title>Imprimir Tareas</title></head><body>');
            win.document.write('<h2>Tareas Registradas</h2>');
            win.document.write(contenido);
            win.document.write('</body></html>');
            win.document.close();
            win.print();
        }
    </script>
</head>
<body>

<h2>Gestión de Tareas 📋</h2>

<form method="POST">
    <label>Descripción:</label>
    <textarea name="descripcion" required></textarea>

    <label>Fecha:</label>
    <input type="date" name="fecha" required>

    <label>Lote:</label>
    <select name="lote_id" required>
        <option value="">Seleccione un lote</option>
        <?php while($l = $lotes->fetch_assoc()) { ?>
            <option value="<?= $l['id']; ?>"><?= $l['nombre']; ?></option>
        <?php } ?>
    </select>

    <label>Cultivo:</label>
    <select name="cultivo_id" required>
        <option value="">Seleccione un cultivo</option>
        <?php while($c = $cultivos->fetch_assoc()) { ?>
            <option value="<?= $c['id']; ?>"><?= $c['nombre']; ?></option>
        <?php } ?>
    </select>

    <button type="submit" name="guardar">Guardar</button>
</form>

<h3>Tareas registradas</h3>

<button class="imprimirTabla" onclick="imprimirTabla()">Imprimir Toda la Tabla</button>

<table id="tablaTareas">
<tr>
    <th>ID</th>
    <th>Descripción</th>
    <th>Fecha</th>
    <th>Lote</th>
    <th>Cultivo</th>
    <th></th>
</tr>

<?php if($tareas && $tareas->num_rows > 0): ?>
    <?php while($t = $tareas->fetch_assoc()): ?>
<tr>
    <td><?= $t['id']; ?></td>
    <td><?= $t['descripcion']; ?></td>
    <td><?= $t['fecha']; ?></td>
    <td><?= $t['nombre_lote']; ?></td>
    <td><?= $t['nombre_cultivo']; ?></td>
    <td>
        <a class="boton modificar" href="modificar_tarea.php?id=<?= $t['id']; ?>">Modificar</a>
        <a class="boton eliminar" href="?eliminar=<?= $t['id']; ?>" onclick="return confirm('¿Seguro que deseas eliminar esta tarea?');">Eliminar</a>
    </td>
</tr>
    <?php endwhile; ?>
<?php else: ?>
<tr>
    <td colspan="6">No hay tareas registradas</td>
</tr>
<?php endif; ?>
</table>

<a href="../php/dashboard.php">Volver al Panel</a>

</body>
</html>
