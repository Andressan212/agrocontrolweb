<?php
include("../php/conexion.php");

$v = $conn->query("SELECT SUM(cantidad * precio) AS t FROM ventas")->fetch_assoc()['t'] ?? 0;
$g = $conn->query("SELECT SUM(monto) AS t FROM gastos")->fetch_assoc()['t'] ?? 0;
$p = $conn->query("SELECT SUM(monto) AS t FROM pagos_trabajadores")->fetch_assoc()['t'] ?? 0;
$b = $v - ($g + $p);
?>

<h2>Balance General</h2>
<hr>
<p>Ingresos: $<?= number_format($v,2) ?></p>
<p>Gastos: $<?= number_format($g,2) ?></p>
<p>Pagos: $<?= number_format($p,2) ?></p>
<h3>Balance Final: $<?= number_format($b,2) ?></h3>

<script>window.print();</script>
