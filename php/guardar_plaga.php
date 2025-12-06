<?php
include("conexion.php");

$nombre = $_POST['nombre'];
$desc = $_POST['descripcion'];

$conexion->query("INSERT INTO plagas(nombre,descripcion)
VALUES('$nombre','$desc')");

header("Location: ../pages/plagas.php");
