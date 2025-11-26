<?php
include("conexion.php");

$nombre = $_POST['nombre'];
$tipo = $_POST['tipo'];
$estado = $_POST['estado'];

$conexion->query("INSERT INTO maquinaria(nombre,tipo,estado)
VALUES('$nombre','$tipo','$estado')");

header("Location: ../modulos/maquinaria.php");
