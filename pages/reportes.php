<?php
include("../php/conexion.php");
session_start();

$desde = $_GET['desde'] ?? date('Y-m-01');
$hasta = $_GET['hasta'] ?? date('Y-m-d');

$ventas = $conn->query("
    SELECT SUM(cantidad * precio) total 
    FROM ventas 
    WHERE fecha BETWEEN '$desde' AND '$hasta'
")->fetch_assoc()['total'] ?? 0;

$gastos = $conn->query("
    SELECT SUM(monto) total 
    FROM gastos 
    WHERE fecha BETWEEN '$desde' AND '$hasta'
")->fetch_assoc()['total'] ?? 0;
?>

<h2>Reportes 📈</h2>

<form method="GET">
    Desde <input type="date" name="desde" value="<?= $desde ?>">
    Hasta <input type="date" name="hasta" value="<?= $hasta ?>">
    <button>Ver</button>
</form>

<p><strong>Ventas:</strong> $<?= number_format($ventas,2) ?></p>
<p><strong>Gastos:</strong> $<?= number_format($gastos,2) ?></p>
<p><strong>Resultado:</strong>
    <span style="color:<?= ($ventas-$gastos)>=0?'green':'red' ?>">
        $<?= number_format($ventas-$gastos,2) ?>
    </span>
</p>

<button onclick="window.print()">🖨 Imprimir</button>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>
    
</body>
</html>