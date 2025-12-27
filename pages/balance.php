<?php
include("../php/conexion.php");
session_start();
if (!isset($_SESSION['usuario'])) exit();

// INGRESOS (VENTAS)
$ventas = $conn->query("SELECT SUM(cantidad * precio) AS total FROM ventas");
$total_ventas = $ventas->fetch_assoc()['total'] ?? 0;

// GASTOS
$gastos = $conn->query("SELECT SUM(monto) AS total FROM gastos");
$total_gastos = $gastos->fetch_assoc()['total'] ?? 0;

// PAGOS A TRABAJADORES
$pagos = $conn->query("SELECT SUM(monto) AS total FROM pagos_trabajadores");
$total_pagos = $pagos->fetch_assoc()['total'] ?? 0;

// BALANCE
$egresos = $total_gastos + $total_pagos;
$balance = $total_ventas - $egresos;
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Balance General - AgroControl</title>
    <link rel="stylesheet" href="../css/estilo.css">
    <style>table{border-collapse:collapse} th,td{padding:8px}</style>
</head>
<body>

<h2>📊 Balance General</h2>

<table border="1" width="50%">
    <tr><th>Concepto</th><th>Total</th></tr>
    <tr><td>Ingresos por Ventas</td><td>$ <?php echo number_format((float)$total_ventas, 2); ?></td></tr>
    <tr><td>Gastos</td><td>$ <?php echo number_format((float)$total_gastos, 2); ?></td></tr>
    <tr><td>Pagos a Trabajadores</td><td>$ <?php echo number_format((float)$total_pagos, 2); ?></td></tr>
    <tr>
        <th>Balance Final</th>
        <th style="color:<?php echo ($balance >= 0) ? 'green' : 'red'; ?>">$ <?php echo number_format((float)$balance, 2); ?></th>
    </tr>
</table>

<br>
<a href="imprimir_balance.php" target="_blank">🧾 Imprimir Balance</a>
<br><br>
<a href="../php/dashboard.php">Volver al Panel</a>

</body>
</html>
