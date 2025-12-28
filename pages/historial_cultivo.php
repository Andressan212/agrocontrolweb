<?php
include("../php/conexion.php");
session_start();
if (!isset($_SESSION['usuario'])) exit();

$cultivo_id = $_GET['id'] ?? 0;

// DATOS DEL CULTIVO
$cultivo = $conn->query("
    SELECT c.*, l.nombre AS lote
    FROM cultivos c
    LEFT JOIN lotes l ON c.lote_id = l.id
    WHERE c.id = $cultivo_id
")->fetch_assoc();

// TAREAS
$tareas = $conn->query("
    SELECT descripcion, fecha
    FROM tareas
    WHERE cultivo_id = $cultivo_id
");

// PLAGAS
$plagas = $conn->query("
    SELECT p.nombre, p.tratamiento, l.nombre AS lote
    FROM plagas p
    LEFT JOIN lotes l ON p.lote_id = l.id
    WHERE p.lote_id = {$cultivo['lote_id']}
");

// VENTAS
$ventas = $conn->query("
    SELECT cantidad, precio, fecha
    FROM ventas
    WHERE cultivo_id = $cultivo_id
");

// INGRESOS
$total_ventas = 0;
while($v = $ventas->fetch_assoc()){
    $total_ventas += $v['cantidad'] * $v['precio'];
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Historial del Cultivo</title>
    <link rel="stylesheet" href="../css/estilo.css">
</head>
<body>

<h2>🌱 Historial del Cultivo</h2>

<h3>Datos generales</h3>
<p><strong>Nombre:</strong> <?= $cultivo['nombre'] ?></p>
<p><strong>Variedad:</strong> <?= $cultivo['variedad'] ?></p>
<p><strong>Lote:</strong> <?= $cultivo['lote'] ?></p>
<p><strong>Siembra:</strong> <?= $cultivo['fecha_siembra'] ?></p>
<p><strong>Cosecha:</strong> <?= $cultivo['fecha_cosecha'] ?></p>

<hr>

<h3>📋 Tareas realizadas</h3>
<ul>
<?php while($t = $tareas->fetch_assoc()){ ?>
    <li><?= $t['fecha'] ?> – <?= $t['descripcion'] ?></li>
<?php } ?>
</ul>

<hr>

<h3>🐛 Plagas detectadas</h3>
<ul>
<?php while($p = $plagas->fetch_assoc()){ ?>
    <li><?= $p['nombre'] ?> | Tratamiento: <?= $p['tratamiento'] ?></li>
<?php } ?>
</ul>

<hr>

<h3>💰 Ventas</h3>
<p><strong>Total vendido:</strong> $<?= number_format($total_ventas,2) ?></p>

<br>
<a href="../pages/cultivos.php">Volver</a>
</body>
</html>
