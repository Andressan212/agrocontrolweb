<?php
include("conexion.php");
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: ../index.php");
    exit();
}

$id = $_GET['id'];

$conn->query("DELETE FROM pagos WHERE id = $id");

header("Location: pago_listar.php");
exit();
?>
