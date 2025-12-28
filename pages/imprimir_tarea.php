<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}
include "../php/conexion.php";

$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$sql = "SELECT t.id, t.descripcion, t.fecha, 
               l.nombre AS lote, 
               c.nombre AS cultivo
        FROM tareas t
        LEFT JOIN lotes l ON t.lote_id = l.id
        LEFT JOIN cultivos c ON t.cultivo_id = c.id
        WHERE t.id = $id";

$result = $conn->query($sql);
$tarea = $result ? $result->fetch_assoc() : null;
if (!$tarea) {
    echo "Tarea no encontrada.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Imprimir Tarea</title>
    <style>
        body { 
            font-family: Arial;
            margin: 40px;
        }
        h2 {
            text-align: center;
            margin-bottom: 40px;
        }
        .campo {
            margin-bottom: 15px;
            font-size: 18px;
        }
    </style>
</head>
<body onload="window.print()">

<h2>Tarea Nº <?= $tarea['id'] ?></h2>

<div class="campo"><strong>Descripción:</strong> <?= $tarea['descripcion'] ?></div>
<div class="campo"><strong>Fecha:</strong> <?= $tarea['fecha'] ?></div>
<div class="campo"><strong>Lote:</strong> <?= $tarea['lote'] ?></div>
<div class="campo"><strong>Cultivo:</strong> <?= $tarea['cultivo'] ?></div>

</body>
</html>
