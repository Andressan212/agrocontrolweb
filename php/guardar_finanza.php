<?php
include("conexion.php");

$concepto = $_POST['concepto'];
$monto = $_POST['monto'];
$tipo = $_POST['tipo'];
$fecha = $_POST['fecha'];

$conexion->query("INSERT INTO finanzas(concepto,monto,tipo,fecha)
VALUES('$concepto','$monto','$tipo','$fecha')");

header("Location: ../modulos/finanzas.php");
