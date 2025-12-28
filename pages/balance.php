<?php
include("../php/conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

// INGRESOS (VENTAS)
$ventas = $conn->query("
    SELECT IFNULL(SUM(cantidad * precio), 0) AS total_ventas 
    FROM ventas
")->fetch_assoc()['total_ventas'];

// GASTOS
$gastos = $conn->query("
    SELECT IFNULL(SUM(monto), 0) AS total_gastos 
    FROM gastos
")->fetch_assoc()['total_gastos'];

// PAGOS A TRABAJADORES
$pagos = $conn->query("
    SELECT IFNULL(SUM(monto), 0) AS total_pagos 
    FROM pagos_trabajadores
")->fetch_assoc()['total_pagos'];

// INSUMOS (valor del stock actual)
$insumos = $conn->query("
    SELECT IFNULL(SUM(stock * precio), 0) AS total_insumos 
    FROM insumos
")->fetch_assoc()['total_insumos'];

$egresos = $gastos + $pagos;
$resultado = $ventas - $egresos;
?>

<!DOCTYPE html>
<html>
<head>
    <title>Balance General</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<h2>Balance General 📊</h2>

<table border="1" width="60%">
<tr>
    <th>Concepto</th>
    <th>Monto</th>
</tr>

<tr>
    <td>Total Ventas</td>
    <td>$<?= number_format($ventas, 2) ?></td>
</tr>

<tr>
    <td>Total Gastos</td>
    <td>$<?= number_format($gastos, 2) ?></td>
</tr>

<tr>
    <td>Pagos a Trabajadores</td>
    <td>$<?= number_format($pagos, 2) ?></td>
</tr>

<tr>
    <td><strong>Total Egresos</strong></td>
    <td><strong>$<?= number_format($egresos, 2) ?></strong></td>
</tr>

<tr>
    <td><strong>Resultado Final</strong></td>
    <td>
        <strong style="color:<?= $resultado >= 0 ? 'green' : 'red' ?>">
            $<?= number_format($resultado, 2) ?>
        </strong>
    </td>
</tr>
</table>

<br>
<button onclick="window.print()">🖨 Imprimir Balance</button>
<br><br>

<a href="../php/dashboard.php">Volver al Panel</a>

</body>
</html>
