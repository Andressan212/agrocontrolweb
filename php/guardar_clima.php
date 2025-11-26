<?php
include("conexion.php");

$fecha = $_POST['fecha'];
$temp  = $_POST['temperatura'];
$lluvia = $_POST['lluvia'];

$conexion->query("INSERT INTO clima(fecha,temperatura,lluvia)
VALUES('$fecha','$temp','$lluvia')");

header("Location: ../modulos/clima.php");
