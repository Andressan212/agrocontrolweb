<?php
include("conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    exit("Acceso denegado");
}

$nombreArchivo = "backup_$fecha.sql";
$archivo = "../backups/$nombreArchivo";

$comando = "mysqldump -h $host -u $usuario $bd > $archivo";
system($comando);

$conn->query("
    INSERT INTO backups (archivo, fecha)
    VALUES ('$nombreArchivo', NOW())
");

