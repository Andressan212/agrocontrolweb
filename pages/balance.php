<?php
include("../php/conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

// Total ventas
$qVentas = $conn->query("SELECT IFNULL(SUM(precio * cantidad),0) AS total FROM ventas");
$totalVentas = $qVentas->fetch_assoc()['total'];

// Total gastos
$qGastos = $conn->query("SELECT IFNULL(SUM(monto),0) AS total FROM gastos");
$totalGastos = $qGastos->fetch_assoc()['total'];

// Total pagos a trabajadores
$qPagos = $conn->query("SELECT IFNULL(SUM(monto),0) AS total FROM pagos");
$totalPagos = $qPagos->fetch_assoc()['total'];

// Balance final
$balance = $totalVentas - ($totalGastos + $totalPagos);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Balance General - AgroSystem</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<h2>📊 Balance General</h2>

<table border="1" width="50%">
    <tr>
        <th>Concepto</th>
        <th>Total</th>
    </tr>
    <tr>
        <td>Ingresos por ventas</td>
        <td>$ <?php echo number_format($totalVentas, 2); ?></td>
    </tr>
    <tr>
        <td>Gastos</td>
        <td>$ <?php echo number_format($totalGastos, 2); ?></td>
    </tr>
    <tr>
        <td>Pagos a trabajadores</td>
        <td>$ <?php echo number_format($totalPagos, 2); ?></td>
    </tr>
    <tr>
        <th>Balance final</th>
        <th>$ <?php echo number_format($balance, 2); ?></th>
    </tr>
</table>

<br>

<a href="../php/dashboard.php">Volver al Panel</a>

</body>
</html>
