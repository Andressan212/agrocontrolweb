<?php
session_start();

// Protección: solo usuarios logueados
if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

// DATOS DE LA BASE (ajustá si usás otros)
$usuario = "root";
$password = "";
$bd = "agrocontrol";

// Fecha actual
$fecha = date("Y-m-d_H-i-s");

// Nombre del archivo
$archivo = "backup_{$bd}_{$fecha}.sql";

// Comando mysqldump (Windows)
$comando = "C:\\xampp\\mysql\\bin\\mysqldump -u{$usuario} {$bd} > {$archivo}";

// Ejecutar backup
system($comando);

// Descargar archivo
header("Content-Type: application/sql");
header("Content-Disposition: attachment; filename=$archivo");
readfile($archivo);

// Borrar archivo temporal
unlink($archivo);
exit();
