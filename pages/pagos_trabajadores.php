<?php
include("../php/conexion.php");
session_start();
if (!isset($_SESSION['usuario'])) exit();

if (isset($_POST['guardar'])) {
    $trabajador = $_POST['trabajador_id'];
    $monto = $_POST['monto'];
    $fecha = $_POST['fecha'];
    $detalle = $_POST['detalle'];

    $stmt = $conn->prepare("
        INSERT INTO pagos_trabajadores(trabajador_id, monto, fecha, detalle)
        VALUES(?,?,?,?)
    ");
    $stmt->bind_param("idss", $trabajador, $monto, $fecha, $detalle);
    $stmt->execute();
}

$trabajadores = $conn->query("SELECT * FROM trabajadores");
$pagos = $conn->query("
    SELECT p.*, t.nombre 
    FROM pagos_trabajadores p
    JOIN trabajadores t ON p.trabajador_id = t.id
    ORDER BY p.id DESC
");
?>

<h2>💰 Pagos a Trabajadores</h2>

<form method="POST">
    <select name="trabajador_id" required>
        <option value="">Trabajador</option>
        <?php while($t=$trabajadores->fetch_assoc()){ ?>
        <option value="<?= $t['id'] ?>"><?= $t['nombre'] ?></option>
        <?php } ?>
    </select>

    <input type="number" step="0.01" name="monto" placeholder="Monto" required>
    <input type="date" name="fecha" required>
    <textarea name="detalle" placeholder="Detalle del pago"></textarea>
    <button name="guardar">Guardar</button>
</form>

<table border="1" width="100%">
<tr>
<th>Trabajador</th>
<th>Monto</th>
<th>Fecha</th>
<th>Detalle</th>
<th>Factura</th>
</tr>

<?php while($p=$pagos->fetch_assoc()){ ?>
<tr>
<td><?= $p['nombre'] ?></td>
<td>$<?= $p['monto'] ?></td>
<td><?= $p['fecha'] ?></td>
<td><?= $p['detalle'] ?></td>
<td>
    <a href="imprimir_pago.php?id=<?= $p['id'] ?>" target="_blank">
        🧾 Imprimir
    </a>
</td>
</tr>
<?php } ?>
</table>
