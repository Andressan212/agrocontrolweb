<?php
include("../php/conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

// REGISTRAR PAGO
if (isset($_POST['guardar'])) {
    $trabajador_id = $_POST['trabajador_id'];
    $monto = $_POST['monto'];
    $fecha = $_POST['fecha'];
    $descripcion = $_POST['descripcion'];

    if (!empty($trabajador_id) && is_numeric($monto)) {
        $stmt = $conn->prepare("INSERT INTO pagos(trabajador_id, monto, fecha, descripcion) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("idss", $trabajador_id, $monto, $fecha, $descripcion);
        $stmt->execute();
        $stmt->close();
    }
}

// ELIMINAR
if (isset($_GET['eliminar'])) {
    $id = intval($_GET['eliminar']);
    $conn->query("DELETE FROM pagos WHERE id = $id");
    header("Location: pagos.php");
    exit();
}

// SELECT TRABAJADORES
$trabajadores = $conn->query("SELECT * FROM trabajadores ORDER BY nombre ASC");

// LISTA PAGOS
$pagos = $conn->query("
    SELECT p.id, p.monto, p.fecha, p.descripcion,
           t.nombre, t.cargo
    FROM pagos p
    LEFT JOIN trabajadores t ON p.trabajador_id = t.id
    ORDER BY p.id DESC
");
?>

<!DOCTYPE html>
<html>
<head>
<title>Pagos</title>
<link rel="stylesheet" href="../css/estilo.css">

<script>
function imprimirFactura(id) {
    let fila = document.getElementById("factura-" + id).innerHTML;
    let w = window.open('', '', 'width=800,height=600');
    w.document.write("<h2>Factura de Pago</h2>");
    w.document.write("<table border='1' style='width:100%; padding:10px'>" + fila + "</table>");
    w.print();
    w.close();
}
</script>

</head>
<body>

<h2>Pagos a Trabajadores 💵</h2>

<form method="POST">
    <label>Trabajador:</label>
    <select name="trabajador_id" required>
        <option value="">Seleccione</option>
        <?php while($t = $trabajadores->fetch_assoc()) { ?>
            <option value="<?= $t['id'] ?>"><?= $t['nombre'] ?> (<?= $t['cargo'] ?>)</option>
        <?php } ?>
    </select>

    <label>Monto:</label>
    <input type="number" step="0.01" name="monto" required>

    <label>Fecha:</label>
    <input type="date" name="fecha" required>

    <label>Descripción:</label>
    <textarea name="descripcion"></textarea>

    <button type="submit" name="guardar">Registrar Pago</button>
</form>

<h3>Pagos Registrados</h3>

<table border="1" width="100%">
<tr>
    <th>ID</th>
    <th>Trabajador</th>
    <th>Cargo</th>
    <th>Monto</th>
    <th>Fecha</th>
    <th>Descripción</th>
    <th></th>
</tr>

<?php while($p = $pagos->fetch_assoc()) { ?>
<tr id="factura-<?= $p['id'] ?>">
    <td><?= $p['id'] ?></td>
    <td><?= $p['nombre'] ?></td>
    <td><?= $p['cargo'] ?></td>
    <td>$<?= number_format($p['monto'],2) ?></td>
    <td><?= $p['fecha'] ?></td>
    <td><?= $p['descripcion'] ?></td>
    <td>
        <a href="pagos.php?eliminar=<?= $p['id'] ?>" onclick="return confirm('¿Eliminar pago?')">Eliminar</a> |
        <a href="#" onclick="imprimirFactura(<?= $p['id'] ?>)">Factura</a>
    </td>
</tr>
<?php } ?>
</table>

<a href="../php/dashboard.php">Volver</a>

</body>
</html>
