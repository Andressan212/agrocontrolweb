<?php
include("conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("ID no proporcionado.");
}

$id = intval($_GET['id']);

$sql = "DELETE FROM ventas WHERE id = $id";
$conn->query($sql);

header("Location: ventas_listar.php");
exit();
?>
