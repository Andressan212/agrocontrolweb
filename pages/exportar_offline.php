<?php
include("../php/conexion.php");

$data = [];

// Tareas
$tareas = $conn->query("SELECT * FROM tareas");
while($t=$tareas->fetch_assoc()) $data['tareas'][] = $t;

// Cultivos
$cultivos = $conn->query("SELECT * FROM cultivos");
while($c=$cultivos->fetch_assoc()) $data['cultivos'][] = $c;

// Insumos
$insumos = $conn->query("SELECT * FROM insumos");
while($i=$insumos->fetch_assoc()) $data['insumos'][] = $i;

file_put_contents("offline_data.json", json_encode($data, JSON_PRETTY_PRINT));

echo "Datos exportados correctamente";
