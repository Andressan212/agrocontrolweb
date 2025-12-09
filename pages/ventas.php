<?php
include("../php/conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

// Guardar venta
if (isset($_POST['guardar'])) {
    $cultivo_id = $_POST['cultivo_id'] ?? '';
    $cantidad = $_POST['cantidad'] ?? '';
    $precio = $_POST['precio'] ?? '';
    $fecha = $_POST['fecha'] ?? '';

    if (!empty($cultivo_id) && is_numeric($cantidad) && is_numeric($precio) && !empty($fecha)) {
        $stmt = $conn->prepare("
            INSERT INTO ventas(cultivo_id, cantidad, precio, fecha)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->bind_param("idds", $cultivo_id, $cantidad, $precio, $fecha);
        $stmt->execute();
        $stmt->close();
    }
}

// Eliminar venta
if (isset($_GET['eliminar'])) {
    $idEliminar = intval($_GET['eliminar']);
    $conn->query("DELETE FROM ventas WHERE id = $idEliminar");
    header("Location: ventas.php");
    exit();
}

// Obtener cultivos para el select
$cultivos = $conn->query("SELECT id, nombre FROM cultivos ORDER BY nombre ASC");

// Obtener ventas con nombre del cultivo
$ventas = $conn->query("
    SELECT v.id, v.cantidad, v.precio, v.fecha, 
           c.nombre AS cultivo
    FROM ventas v
    LEFT JOIN cultivos c ON v.cultivo_id = c.id
    ORDER BY v.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Ventas - AgroSystem</title>
<link rel="stylesheet" href="../css/estilo.css">

<style>
table { width:100%; border-collapse: collapse; margin-top:20px; }
th, td { border:1px solid #ccc; padding:8px; }
th { background:#222; color:white; }
a.btn { padding:5px 10px; color:white; border-radius:5px; text-decoration:none; }
.eliminar { background:#d9534f; }
.modificar { background:#f0ad4e; }
button.print { margin:10px 0; padding:8px 15px; background:#28a745; color:white; border:none; border-radius:5px; cursor:pointer; }
</style>

<script>
function imprimirTabla() {
    var contenido = document.getElementById("tablaVentas").outerHTML;
    var w = window.open('', '', 'width=800,height=600');
    w.document.write("<h2>Registro de Ventas</h2>");
    w.document.write(contenido);
    w.print();
    w.close();
}
</script>

</head>
<body>

<h2>Registro de Ventas 📦</h2>

<form method="POST">

<label>Cultivo:</label>
<select name="cultivo_id" required>
    <option value="">Seleccione un cultivo</option>
    <?php while($c = $cultivos->fetch_assoc()) { ?>
        <option value="<?= $c['id']; ?>"><?= $c['nombre']; ?></option>
    <?php } ?>
</select>

<label>Cantidad:</label>
<input type="number" step="0.01" name="cantidad" required>

<label>Precio (por unidad):</label>
<input type="number" step="0.01" name="precio" required>

<label>Fecha:</label>
<input type="date" name="fecha" required>

<button type="submit" name="guardar">Registrar Venta</button>
</form>

<h3>Ventas registradas</h3>

<button class="print" onclick="imprimirTabla()">Imprimir Todas</button>

<table id="tablaVentas">
<tr>
    <th>ID</th>
    <th>Cultivo</th>
    <th>Cantidad</th>
    <th>Precio</th>
    <th>Total</th>
    <th>Fecha</th>
    <th></th>
</tr>

<?php if ($ventas->num_rows > 0): ?>
    <?php while($v = $ventas->fetch_assoc()): ?>
<tr>
    <td><?= $v['id']; ?></td>
    <td><?= $v['cultivo']; ?></td>
    <td><?= number_format($v['cantidad'],2) ?></td>
    <td>$<?= number_format($v['precio'],2) ?></td>
    <td><b>$<?= number_format($v['cantidad'] * $v['precio'], 2) ?></b></td>
    <td><?= $v['fecha']; ?></td>

    <td>
        <a class="btn modificar" href="modificar_venta.php?id=<?= $v['id']; ?>">Modificar</a>
        <a class="btn eliminar" href="?eliminar=<?= $v['id']; ?>" onclick="return confirm('¿Eliminar esta venta?');">Eliminar</a>
    </td>
</tr>
    <?php endwhile; ?>
<?php else: ?>
<tr><td colspan="7">No hay ventas registradas</td></tr>
<?php endif; ?>

</table>

<a href="../php/dashboard.php">Volver al Panel</a>

</body>
</html>
