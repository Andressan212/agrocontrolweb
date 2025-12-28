<?php
include("../php/conexion.php");

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=ventas.xls");

echo "Cultivo\tCantidad\tPrecio\tFecha\n";

$ventas = $conn->query("
    SELECT c.nombre, v.cantidad, v.precio, v.fecha
    FROM ventas v
    JOIN cultivos c ON v.cultivo_id = c.id
");

while($v=$ventas->fetch_assoc()){
    echo "{$v['nombre']}\t{$v['cantidad']}\t{$v['precio']}\t{$v['fecha']}\n";
}
