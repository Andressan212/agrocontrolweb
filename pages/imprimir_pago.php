<?php
include("../php/conexion.php");
$id = $_GET['id'];

$pago = $conn->query("
    SELECT p.*, t.nombre, t.cargo, t.telefono
    FROM pagos_trabajadores p
    JOIN trabajadores t ON p.trabajador_id = t.id
    WHERE p.id = $id
")->fetch_assoc();
?>

<h2>Recibo de Pago</h2>
<hr>
<p><strong>Trabajador:</strong> <?= $pago['nombre'] ?></p>
<p><strong>Cargo:</strong> <?= $pago['cargo'] ?></p>
<p><strong>Teléfono:</strong> <?= $pago['telefono'] ?></p>
<p><strong>Fecha:</strong> <?= $pago['fecha'] ?></p>
<p><strong>Monto:</strong> $<?= $pago['monto'] ?></p>
<p><strong>Detalle:</strong> <?= $pago['detalle'] ?></p>

<script>
window.print();
</script>
